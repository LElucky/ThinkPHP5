<?php
namespace app\admin\validate;
use think\Validate;
class SystemCheckedValidate extends Validate
{
	// 验证规则
    protected $rule =   [
        'company'   => 'max:50|unique:www_web',
        'version'   => 'max:50|unique:www_web',
        'www'       => 'max:50|unique:www_web',
        'system'    => 'max:50|unique:www_web',       
        'dataBase'  => 'max:50|unique:www_web',
        'powerby'   => 'max:50|unique:www_web',
        'record'    => 'max:50|unique:www_web',
        'author'    => 'max:50|unique:www_web',
        'id'        => 'number',
    ];

    //验证提示
    protected $message  =   [
        'company.max'     => '公司名称不得超过50字符',
        'company.unique'  => '公司名称唯一',
        'version.max'     => '版本号最大不得超过50字符',
        'version.unique'  => '版本号唯一',
        'www.max'         => '域名最长不得超过50字符',
        'www.unique'      => '域名唯一',
        'system.max'      => '服务器环境最长不得超过50字符',
        'system.unique'   => '服务器环境唯一',
        'dataBase.max'    => '数据库版本最长不得超过50字符',
        'dataBase.unique' => '数据库版本唯一',
        'powerby.max'     => '版权信息最长不得超过50字符',
        'powerby.unique'  => '版权信息唯一',
        'record.max'      => '网站备案号最长不得超过50字符',
        'record.unique'   => '网站备案号唯一',
        'author.max'      => '作者最长不得超过50字符',
        'author.unique'   => '作者唯一',
        'id.number'       => 'id必须为数字',
    ];

    //验证场景
    protected $scene = [
    	'edit' => ['id','company','version','www','system','dataBase','powerby','record','author']
    ];
}
?>