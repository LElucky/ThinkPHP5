<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];


use think\Route;

//后台管理模块登录
Route::rule('login_admin','admin/login/login');

Route::Group('l',[
	'login_validate' => ['admin/login/loginValidate' , ['method'=>'post']]
]);
//内容管理模块
Route::group('a',[
		'index'      => ['admin/admin/index'     ,  ['method' => 'get' ]],
		'main'		 => ['admin/admin/main'		 ,  ['method' => 'get' ]],
		'news'		 => ['admin/news/index'		 ,  ['method' => 'get' ]],
		'newsAdd' 	 => ['admin/news/newsAdd' 	 ,  ['method' => 'get' ]],
		'image'		 => ['admin/image/index' 	 ,  ['method' => 'get' ]],
		'error'		 => ['admin/error/index'	 ,  ['method' => 'get' ]],
		'navList'	 => ['admin/admin/navList'	 ,  ['method' => 'get' ]],
		'getParam'	 => ['admin/admin/getParam'	 ,  ['method' => 'get' ]],
]);



//用户管理模块
Route::group('u',[
		'index'		 => ['admin/user/index'      ,  ['method' => 'get' ]],
		'indexinfo'  => ['admin/user/indexinfo'  ,  ['method' => 'get' ]],
		'addUser'	 => ['admin/user/addUser'	 ,  ['method' => 'get' ]],
		'addSave'	 => ['admin/user/userSave'	 ,  ['method' => 'post']],
        'delete'     => ['admin/user/delete'     ,  ['method' => 'get' ]   , ['id'=>'\d+']],
        'ajaxStatus' => ['admin/user/ajaxStatus' ,  ['method' => 'post']],
]);


// 权限系统	rule
Route::group('auth',[
		'authRule'	 	=> ['admin/authconfig/authRule' 	, ['method' => 'get' ]],
		'authRuleAdd'	=> ['admin/authconfig/authRuleAdd'  , ['method' => 'get' ]],
		'authRuleInfo'  => ['admin/authconfig/authRuleInfo' , ['method' => 'get' ]],
		'ajaxStatus' 	=> ['admin/authconfig/ajaxStatus'   , ['method' => 'post']],
		'addSave'		=> ['admin/authconfig/addSave'	  	, ['method'	=> 'post']],
        'delete'        => ['admin/authconfig/delete'       , ['method' => 'get' ]   , ['id'=>'\d+']],
        'sortEdit'		=> ['admin/authconfig/sortEdit'     , ['method' => 'post']],
]);

Route::group('authg',[
		'authGroup'   		=> ['admin/authgroup/authGroup'			, ['method' => 'get' ]],
        'authGroupAdd' 	 	=> ['admin/authgroup/authGroupAdd'  	, ['method' => 'get' ]],
        'authGroupSave' 	=> ['admin/authgroup/authGroupSave' 	, ['method' => 'post']],
        'authlist'      	=> ['admin/authgroup/authList'      	, ['method' => 'get' ]],
        'ajaxStatus'		=> ['admin/authgroup/ajaxStatus'		, ['method' => 'post']],
        'delete'       		=> ['admin/authgroup/delete'        	, ['method' => 'get' ]   , ['id'=>'\d+']],
        'authGroupRule' 	=> ['admin/authgroup/authGroupRule' 	, ['method' => 'get' ]],
        'authjGroupRuleSave'=> ['admin/authgroup/authjGroupRuleSave', ['method' => 'post']]
]);

//日志接口
Route::group('log',[
		'logInfo'			=> ['admin/log/logInfo'					, ['method' => 'get' ]],
]);

//用户等级
Route::group('r',[
		'index'  	 => ['admin/role/index' 	 ,  ['method' => 'get' ]],
]);


//系统设置
Route::group('s',[
		'index'		 => ['admin/system/index'	 ,  ['method' => 'get' ]],
		'log'		 => ['admin/system/log'	     ,  ['method' => 'get' ]],
		'friend'	 => ['admin/system/friend'	 ,  ['method' => 'get' ]],
		'icons'		 => ['admin/system/icons'	 ,  ['method' => 'get' ]],
]);


//使用文档
Route::group('h',[
		'one'		 => ['admin/helper/one'		 ,	['method' => 'get' ]],
		'two'		 => ['admin/helper/two'		 ,  ['method' => 'get' ]],
		'three'		 => ['admin/helper/three' 	 ,  ['method' => 'get' ]],
]);