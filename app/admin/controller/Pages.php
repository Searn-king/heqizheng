<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */
 
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\BaseController;
use app\admin\model\Pages as PagesList;
use app\admin\model\Keywords;
use app\admin\validate\PagesCheck;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\View;

class Pages extends BaseController
{
    public function index()
    {
        if (request()->isAjax()) {
            $param = get_params();
            $where = array();
            if (!empty($param['keywords'])) {
                $where[] = ['id|title|keywords|desc|content', 'like', '%' . $param['keywords'] . '%'];
            }
            $where[] = ['status', '>=', 0];
            $rows = empty($param['limit']) ? get_config('app . page_size') : $param['limit'];
            $content = PagesList::where($where)
                ->field('id,title,status,name,read,template,create_time')
                ->order('create_time desc')
                ->paginate($rows, false, ['query' => $param]);
            return table_assign(0, '', $content);
        }
        else{
            return view();
        } 
    }

    //添加&&编辑
    public function add()
    {
        $param = get_params();
        if (request()->isAjax()) {
			if (isset($param['table-align'])) {
				unset($param['table-align']);
			}
			if (isset($param['content'])) {
				$param['md_content'] = '';
			}
			if (isset($param['docContent-html-code'])) {
				$param['content'] = $param['docContent-html-code'];
				$param['md_content'] = $param['docContent-markdown-doc'];
				unset($param['docContent-html-code']);
				unset($param['docContent-markdown-doc']);
			}
			$DbRes=false;
            if (!empty($param['id']) && $param['id'] > 0) {
                try {
                    validate(PagesCheck::class)->scene('edit')->check($param);
                } catch (ValidateException $e) {
                    // 验证失败 输出错误信息
                    return to_assign(1, $e->getError());
                }
                $param['update_time'] = time();				
				Db::startTrans();
                try {
					$res = PagesList::strict(false)->field(true)->update($param);						
					$aid = $param['id'];
					if ($res) {
                        //关联关键字
						if (isset($param['keyword_names']) && $param['keyword_names']) {
							Db::name('PagesKeywords')->where(['aid'=>$aid])->delete();
							$keywordArray = explode(',', $param['keyword_names']);
							$res_keyword = (new PagesList())->insertKeyword($keywordArray,$aid);
						}
                        else{
                            $res_keyword == true;
                        }
                        if($res_keyword!== false){
                            add_log('edit', $param['id'], $param);
                            Db::commit();
                            $DbRes=true;
                        }
					} else {
                         Db::rollback();
                    }
                }
                catch (\Exception $e) { ##这里参数不能删除($e：错误信息)
                     Db::rollback();
                }
            } else {
                try {
                    validate(PagesCheck::class)->scene('add')->check($param);
                } catch (ValidateException $e) {
                    // 验证失败 输出错误信息
                    return to_assign(1, $e->getError());
                }
                $param['create_time'] = time();
                Db::startTrans();
                try {
                    if(empty($param['desc'])){
                        $param['desc'] = getDescriptionFromContent($param['content'], 100);
                    }
                    $aid = PagesList::strict(false)->field(true)->insertGetId($param);
                    if ($aid) {
                        //关联关键字
						if (isset($param['keyword_names']) && $param['keyword_names']) {
							Db::name('PagesKeywords')->where(['aid'=>$aid])->delete();
							$keywordArray = explode(',', $param['keyword_names']);
							$res_keyword = (new PagesList())->insertKeyword($keywordArray,$aid);
						}
                        else{
                            $res_keyword == true;
                        }
                        if($res_keyword!== false){
                            add_log('add', $aid, $param);
                            Db::commit();
                            $DbRes=true;
                        }
                    } else {
                         Db::rollback();
                    }
                }
                catch (\Exception $e) { ##这里参数不能删除($e：错误信息)
                     Db::rollback();
                }
            }
			if($DbRes){
				return to_assign();
			}
			else{
				return to_assign(1,'操作失败');
			}
        }
		else{
			$id = isset($param['id']) ? $param['id'] : 0;
			View::assign('id', $id);
			View::assign('editor', get_system_config('other','editor'));
			$templates = get_file_list(CMS_ROOT . '/app/home/view/pages/');
			View::assign('templates', $templates);
			if ($id > 0) {
				$detail = (new PagesList())->detail($id);
				if(empty($detail['md_content'])){
                    View::assign('editor',1);
                }
				View::assign('detail', $detail);
				return view('edit');
			}
			return view();
		}
    }

    //删除
    public function delete()
    {
        $id = get_params("id");
        $data['status'] = '-1';
        $data['id'] = $id;
        $data['update_time'] = time();
        if (Db::name('Pages')->update($data) !== false) {
            add_log('delete', $id);
            return to_assign(0, "删除成功");
        } else {
            return to_assign(1, "删除失败");
        }
    }
}
