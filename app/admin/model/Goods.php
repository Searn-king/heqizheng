<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */

namespace app\admin\model;

use app\admin\model\Keywords;
use think\Model;

class Goods extends Model
{
    // 获取商品详情
	public function detail($id)
	{
		$detail = \think\facade\Db::name('Goods')->where(['id'=>$id])->find();
		if(!empty($detail)) {			
			//轮播图
			if(!empty($detail['banner'])) {
				$detail['banner_array'] = explode(',',$detail['banner']);
			}
			//关键字
			$keywrod_array = \think\facade\Db::name('GoodsKeywords')
				->field('i.aid,i.keywords_id,k.title')
				->alias('i')
				->join('keywords k', 'k.id = i.keywords_id', 'LEFT')
				->order('i.create_time asc')
				->where(array('i.aid' => $id, 'k.status' => 1))
				->select()->toArray();

			$detail['keyword_ids'] = implode(",", array_column($keywrod_array, 'keywords_id'));
			$detail['keyword_names'] = implode(',', array_column($keywrod_array, 'title'));
			
			//标签设置
			$detail['tag1'] = $detail['tag2'] = $detail['tag3'] = $detail['tag4'] = $detail['tag5'] = $detail['tag6'] =0;
			if(!empty($detail['tag_values'])) {
				$tag_values_array = explode(',', $detail['tag_values']);
				if(in_array('1', $tag_values_array)){
					$detail['tag1'] = 1;
				}
				if(in_array('2', $tag_values_array)){
					$detail['tag2'] = 1;
				}
				if(in_array('3', $tag_values_array)){
					$detail['tag3'] = 1;
				}
				if(in_array('4', $tag_values_array)){
					$detail['tag4'] = 1;
				}
				if(in_array('5', $tag_values_array)){
					$detail['tag5'] = 1;
				}
				if(in_array('6', $tag_values_array)){
					$detail['tag6'] = 1;
				}
			}
		}
		
		return $detail;
	}

    //插入关键字
    public function insertKeyword($keywordArray = [], $aid = 0)
    {
        $insert = [];
        $time = time();
        foreach ($keywordArray as $key => $value) {
            if (!$value) {
                continue;
            }
            $keywords_id = (new Keywords())->increase($value);
            $insert[] = ['aid' => $aid,
                'keywords_id' => $keywords_id,
                'create_time' => $time,
            ];
        }
        $res = \think\facade\Db::name('GoodsKeywords')->strict(false)->field(true)->insertAll($insert);
        return $res;
    }
}
