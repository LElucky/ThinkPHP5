<?php
namespace app\admin\validate;
use think\Validate;
class AuthCheckedValidate extends Validate
{
	// 验证规则
    protected $rule =   [
        'title'  => 'require|max:50|min:0|unique:auth_rule',
        'name'   => 'require|max:50|unique:auth_rule',
        'id'	 => 'number',
        'sort'	 => 'number',
    ];

    //验证提示
    protected $message  =   [
        'title.require'		=> '规则描述必须',
        'title.max'    		=> '规则描述最多不能超过50个字符', 
        'title.unique'     	=> '规则描述已存在',
        'title.min'      	=> '规则描述不得少于三个字符',
        'name.require'  	=> '规则描述必填',
        'name.max'      	=> '规则描述最大不得超过50字符',
        'name.unique'		=> '规则描述唯一',
        'id.number'			=> 'ID必须为数字',
        'sort.number'		=> '排序必须为数字',
    ];

    //验证场景
    protected $scene = [
    	'ajax_sort' => ['sort','id'],
    	'add_edit'  => ['sort','title','name']
    ];
}
?>