<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */
declare (strict_types = 1);
namespace app\api\controller;

use app\api\BaseController;
use app\api\middleware\Auth;
use app\api\service\JwtAuth;
use think\facade\Db;
use think\facade\Request;

class Calculation extends BaseController
{
    protected $middleware = [

        Auth::class => ['except'    => ['serviceData','getDownUrl','getBoxType','getSystemParameter','getFacialList','getTileList','getCalculation'] ]

    ];

    /**
     * 获取系统参数
     * @return mixed
     */
    protected function systemParameter()
    {
        $systemParameter = Db::name('system_parameter')->select();
        return $systemParameter;
    }

    /**
     * 获取系统参数
     * @return mixed
     */
    public function getSystemParameter()
    {
        $systemParameter = Db::name('system_parameter')->select()->toArray();
        foreach ($systemParameter as $k=>$v){
            $data = explode(',',$v['desc']);
            $systemParameter[$k]['desc'] = $data;
        }
        $this->apiSuccess('success',$systemParameter);
    }

    /**
     * 获取面纸参数列表
     * @return mixed
     */
    public function getFacialList()
    {
        $list = Db::name('facial_tissue')->field('id,title,square_price,ton_price')->select();
        return $list;
    }
    /**
     * 获取面纸参数
     * @param $id
     * @return mixed
     */
    public function getFacial($id)
    {
        $data = Db::name('facial_tissue')->where(['id'=>$id])->find();
        return $data;
    }
    /**
     * 获取瓦纸参数列表
     * @return mixed
     */
    public function getTileList()
    {
        $list = Db::name('tile_paper')->field('id,title,square_price')->select();
        return $list;
    }
    /**
     * 获取瓦纸参数
     * @param $id
     * @return mixed
     */
    public function getTile($id)
    {
        $data = Db::name('tile_paper')->where(['id'=>$id])->find();
        return $data;
    }

    /**
     * 获取盒型列表
     * @return mixed
     */
    public function getBoxType()
    {
        $data = Db::name('GoodsSpecs')
            ->field('id,title,sort,pid,keywords,desc,status')
            ->where(['status'=>'1'])
            ->order('id desc')
            ->select();
        $data = $this->dealListToTree($data,'id','pid','child','id');
        $this->apiSuccess('success',$data);
        return $data;
    }

    /**
     * 方法 dealListToTree，一维数组根据$parent_id的值转为多维数组
     *
     * @param array $data 待处理的一维数组
     * @param string $pkName 用于转化为多维数组的主键字段
     * @param string $pIdName 用于转化为多维数组的字段（根据该字段值转换）
     * @param string $childName 子级的字段名
     * @param string $sortKey 排序字段（不需要可以单独去掉）
     * @param bool $is_empty_childrens 是否返回空的子数组（childrens[]）（true:是，false：否）
     * @param string $rootId 根节点$pkName值
     *
     * @return array $new_data 返回处理好的（多层级）多维数组
     *
     */
    public function dealListToTree($data, $pkName='id', $pIdName='pid', $childName='children', $sortKey='sort', $is_empty_childrens=false, $rootId=0)
    {
        $new_data = [];
        foreach($data as $sorData){
            if($sorData[$pIdName] == $rootId){
                $res = $this->dealListToTree($data, $pkName, $pIdName, $childName, $sortKey, $is_empty_childrens, $sorData[$pkName]);
                if(!empty($res) && !$is_empty_childrens){
                    if(array_key_exists($childName, $sorData)) {
                        if(array_key_exists($childName, $sorData)){
                            $sorData[$childName]['sort'] = $res[0];
                        }else{
                            $sorData[$childName]['sort'] = $res;
                        }
                    }else{
                        $sorData[$childName] = $res;
                    }
                    //对子数组做排序
                    array_multisort(array_column($sorData[$childName], $sortKey),SORT_ASC, $sorData[$childName]);
                }
                $new_data[] = $sorData;
            }
        }
        //对最外层数组做排序
        array_multisort(array_column($new_data, $sortKey),SORT_ASC, $new_data);
        return $new_data;
    }



    /**
     * 获取报价
     */
    public function getCalculation()
    {
        $param = $this->request->param();

        //手提袋下所有分类计算
        $specs_id = $param['specs_id'];
        if($specs_id=='9' || $specs_id=='10' || $specs_id=='11' || $specs_id=='12' || $specs_id=='13' || $specs_id=='14' || $specs_id=='15')
            $jisuan = $this->std_ty($param);
        //双盖盒下所有分类计算
        if($specs_id=='16' || $specs_id=='17' || $specs_id=='18' || $specs_id=='19' || $specs_id=='20' || $specs_id=='21' || $specs_id=='22')
            $jisuan = $this->sgh_ty($param);

        //获取 $jisuan ['rule']：计算规则  ['result']：计算结果   $this->apiSuccess('success',$jisuan);
        if(is_null($jisuan['rule']))
            $this->apiError('计算规则错误，请联系管理员处理');
//        $this->apiSuccess('success',$jisuan);

        //获取系统参数
        $systemParameter = $this->systemParameter();
//        $this->apiSuccess('success',$systemParameter);

//系统参数  *********************************************str
        //机器选择  ---------str
        //$h3   =IF(A6<=74,0,IF(A6<10000000000,INT((1)/1)))
        if($jisuan['result'][0]<74)
            $h3 = 0;
        elseif ($jisuan['result'][0]>=74 && $jisuan['result'][0]<10000000000)
            $h3 = 1;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //$i3   =IF(B6<=102,0,IF(B6<10000000000,INT((1)/1)))
        if($jisuan['result'][1]<=102)
            $i3 = 0;
        elseif ($jisuan['result'][1]>102 && $jisuan['result'][1]<10000000000)
            $i3 = 1;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //$h4   =IF(B6<=74,0,IF(B6<10000000000,INT((1)/1)))
        if($jisuan['result'][1]<=74)
            $h4 = 0;
        elseif ($jisuan['result'][1]>74 && $jisuan['result'][1]<10000000000)
            $h4 = 1;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //$i4   =IF(A6<=102,0,IF(A6<10000000000,INT((1)/1)))
        if($jisuan['result'][0]<=102)
            $i4 = 0;
        elseif ($jisuan['result'][0]>102 && $jisuan['result'][0]<10000000000)
            $i4 = 1;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //机器选择---------end
//系统参数  *********************************************end


//报价参数    *********************************************str
        //订单数量 $param['order_num']
        //长度   $jisuan['result'][0]
        //幅宽   $jisuan['result'][1]
        //拼数   $spell_number
        $spell_number=1;
        //印刷抛数  =IF(B4<3000,120,IF(B4<10000000000,INT((B4-3000)/1)*1%+120))
        if($param['order_num']<3000)
            $printing_throw = 120;
        elseif ($param['order_num']>=3000 && $param['order_num']<10000000000)
            $printing_throw = (($param['order_num']-3000)/1)*0.01+120;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //印刷机 对开/全开   $open  =IF((H3+H4+I3+I4)<2,"对开",IF((H3+H4+I3+I4)>=2,"全开","全开"))
        if(($h3+$h4+$i3+$i4)<2)
            $open ='对开';
        elseif (($h3+$h4+$i3+$i4)>=2)
            $open ='全开';
        else
            $open ='全开';

        //颜色    $color
        $color=1;
        //印版    $printing_plate = ['0','1','2','3','4']
        $printing_plate=0;
        //版面    $page
        $page = explode(',',$systemParameter[1]['desc']);

        //面纸克重
        //获取面纸参数
        if(!empty($param['facial_id']))
            $facial_data = $this->getFacial($param['facial_id']);
        //获取瓦纸参数
        if(!empty($param['tile_id']))
            $tile_data = $this->getTile($param['tile_id']);



//报价参数    *********************************************end

        //设计费
        if($page=='新版')
            $design_fee = 25*$color;
        else
            $design_fee = 0;
        //印刷基数
        if($open=='对开')
            $printing_base = '3000';
        else
            $printing_base = '4000';
        //印刷版费
        if($open=='对开')
            $printing_fee = 25*$color;
        elseif($open=='全开')
            $printing_fee = 75*$color;
        else
            $printing_fee = 0;

        //印刷数量
        $print_quantity = $param['order_num']/$spell_number+$printing_throw;

        //超数单价
        if($open=='对开')
            $excess_unit_price = 0.025*$color;
        elseif($open=='全开')
            $excess_unit_price = 0.05*$color;
        else
            $excess_unit_price = 0;


        //系统参数  1000以下  ****str
        //$f3 =IF(G10<(1000+A8+1),-50,IF(G10<10000000000,INT((0)/1)))
        if($print_quantity<(1000+$printing_throw+1))
            $f3 = -50;
        elseif ($print_quantity<10000000000)
            $f3 = 0;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //$f4 =IF(G10<(1000+A8+1),-200,IF(G10<10000000000,INT((0)/1)))
        if($print_quantity<(1000+$printing_throw+1))
            $f4 = -200;
        elseif ($print_quantity<10000000000)
            $f4 = 0;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //系统参数  1000以下  ****end
        //系统参数  颜色      ****str
        //$g3 =IF(C8<4,0,IF(C8<10000000000,INT((C8-4)/1)*75))
        if($color<4)
            $g3 = 0;
        elseif ($color<10000000000)
            $g3 = (($color-4)/1)*75;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //$g4 =IF(C8<4,0,IF(C8<10000000000,INT((C8-4)/1)*200))
        if($color<4)
            $g4 = 0;
        elseif ($color<10000000000)
            $g4 = (($color-4)/1)*200;
        else
            $this->apiError('参数有误，请输入正确的参数');
        //系统参数  颜色      ****end


        //纸张价格
        $paper_price = $facial_data['square_price'];
        //纸张数量
        $paper_quantity = $jisuan['result'][0]*$jisuan['result'][1]/10000*$paper_price*$print_quantity;

        //印刷保底价 =IF(B8="对开",300+F3+G3,IF(B8="全开",800+F4+G4,0))
        if($open=='对开')
            $minimum_printing_price = 300+$f3+$g3;
        elseif ($open=='全开')
            $minimum_printing_price = 800+$f4+$g4;
        else
            $minimum_printing_price = 0;

        //开机费
        if($print_quantity<$printing_base)
            $startup_fee = $minimum_printing_price;
        elseif ($print_quantity<10000000000)
            $startup_fee = (($print_quantity-$printing_base-$printing_throw)/1)*$excess_unit_price+$minimum_printing_price;
        else
            $this->apiError('参数有误，请输入正确的参数');

        //印刷费  =(G6+G5+G7)*B9
        $printing_expenses = ($design_fee+$printing_fee+$startup_fee)*$printing_plate;
//加工参数    *********************************************str



//加工参数    *********************************************end


        $quotation_list = [
            //费用合计
            'total_expenses'=>$printing_expenses,
            //单个成本
            'single_cost'=>'',
            //建议售价
            'suggested_selling_price'=>'',
        ];
        $this->apiSuccess('success',$quotation_list);
    }

    /**
     * 手提袋 ( 通用查询 )
     */
    private function std_ty($param)
    {
        //根据分类id获取计价方式
        $specs = Db::name('goods_specs')->where('id',$param['specs_id'])->find();
        //计算规则
        $num = explode("\n",$specs['calculation']);
        $n=[];
        $result = [];
        foreach ($num as $k=>$v){
            if($v=='')continue;
            $n[$k] = $v;
            //转换计算公式 (替换相对应)
            $th = ['长','宽','高','糊口','上折','下折','拼距'];
            $to = [$param['long'],$param['wide'],$param['high'],$param['life'],$param['fold_up'],$param['fold_down'],$param['splicing_distance']];
            $t = str_replace($th,$to,$v);
            $result[$k] = eval("return $t;");
        }
        return ['rule'=>$n,'result'=>$result];
    }

    /**
     * 双盖盒 ( 通用查询 )
     */
    private function sgh_ty($param)
    {
        //根据分类id获取计价方式
        $specs = Db::name('goods_specs')->where('id',$param['specs_id'])->find();
        $num = explode("\n",$specs['calculation']);
        $n=[];
        $result = [];
        //最大值
        $max = max(($param['a_hook']+$param['safety_buckle']),($param['tongue_insertion']+$param['wide']));
        //拼距最大值
        $splicing_distance_max = max(($param['a_hook']+$param['safety_buckle']),(($param['tongue_insertion']+$param['wide'])*2+$param['splicing_distance']));
        foreach ($num as $k=>$v){
            if($v=='')continue;
            $n[$k] = $v;
            //转换计算公式 (替换相对应)
            $th = ['长','宽','高','糊口','插舌','翼盖','安全扣','拼距','挂钩','横向拼数','纵向拼数','最大值','拼距最大值'];
            $to = [$param['long'],$param['wide'],$param['high'],$param['life'],$param['tongue_insertion'],$param['wing_cover'],$param['safety_buckle'],$param['splicing_distance'],$param['a_hook'],$param['horizontal_spelling'],$param['vertical_spelling'],$max,$splicing_distance_max];
            $t = str_replace($th,$to,$v);
            $result[$k] = eval("return $t;");
        }
        return ['rule'=>$n,'result'=>$result];
    }


}
