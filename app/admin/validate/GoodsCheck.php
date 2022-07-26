<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */

namespace app\admin\validate;

use think\Validate;

class GoodsCheck extends Validate
{
    protected $rule = [
        'title' => 'require|unique:goods',
        'content' => 'require',
        'id' => 'require',
        'cate_id' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'title.require' => '商品标题不能为空',
        'title.unique' => '同样的商品标题已经存在',
        'cate_id.require' => '所属商品分类为必选',
		'content.require' => '商品内容不能为空',
        'id.require' => '缺少更新条件',
        'status.require' => '状态为必选',
    ];

    protected $scene = [
        'add' => ['title', 'cate_id', 'content', 'status'],
        'edit' => ['title', 'cate_id', 'content', 'id', 'status'],
    ];
}
