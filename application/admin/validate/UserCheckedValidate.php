<?php
namespace app\admin\validate;
use think\Validate;
class UserCheckedValidate extends Validate
{
    protected $rule =   [
        'nickname'  => 'require|max:10|min:3|unique:admin',
        'email'     => 'require|email',
        'password'  => 'require|max:10|min:3'
    ];

    protected $message  =   [
        'nickname.require' => '名称必须',
        'nickname.max'     => '名称最多不能超过10个字符', 
        'nickname.unique'  => '用户已存在',
        'nickname.min'     => '用户名不得少于三个字符',
        'email.require'    => '邮箱必填',
        'email.email'      => '邮箱格式错误',
        'password.require' => '密码必填',
        'password.max'     => '密码长度最多不超过10个字符'
    ];

    protected $scene = [
    	'add'	=>  ['nickname'],
        'edit'  =>  ['nickname'],
    ];


}
?>