<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */

namespace app\admin\model;

use app\admin\model\Keywords;
use think\Model;

class Article extends Model
{
    // 获取文章详情
    public function detail($id)
    {
        $detail = \think\facade\Db::name('Article')->where(['id' => $id])->find();
        if (!empty($detail)) {       
			$keyword_array = \think\facade\Db::name('ArticleKeywords')
				->field('i.aid,i.keywords_id,k.title')
				->alias('i')
				->join('keywords k', 'k.id = i.keywords_id', 'LEFT')
				->order('i.create_time asc')
				->where(array('i.aid' => $id, 'k.status' => 1))
				->select()->toArray();

			$detail['keyword_ids'] = implode(",", array_column($keyword_array, 'keywords_id'));
			$detail['keyword_names'] = implode(',', array_column($keyword_array, 'title'));
			$detail['keyword_array'] = $keyword_array;
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
        $res = \think\facade\Db::name('ArticleKeywords')->strict(false)->field(true)->insertAll($insert);
        return $res;
    }
}
