<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://www.gougucms.com
 */

namespace app\admin\validate;

use think\Validate;

class PagesCheck extends Validate
{
    protected $rule = [
        'title' => 'require|unique:pages',
        'content' => 'require',
		'name' => 'lower|min:3|unique:pages',
        'id' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'title.unique' => '同样的页面标题已经存在',
        'content.require' => '页面内容不能为空',
		'name.lower' => 'URL文件名称只能是小写字符',
        'name.min' => 'URL文件名称至少需要3个小写字符',
        'name.unique' => '同样的URL文件名称已经存在',
        'id.require' => '缺少更新条件',
        'status.require' => '状态为必选',
    ];

    protected $scene = [
        'add' => ['title', 'content', 'name', 'status'],
        'edit' => ['title', 'content', 'id', 'name', 'status'],
    ];
}
