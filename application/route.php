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

//内容管理模块
Route::group('a',[
		'index'      => ['admin/admin/index'     ,  ['method' => 'get' ]],
		'news'		 => ['admin/news/index'		 ,  ['method' => 'get' ]],
		'newsAdd' 	 => ['admin/news/newsAdd' 	 ,  ['method' => 'get' ]],
		'image'		 => ['admin/image/index' 	 ,  ['method' => 'get' ]],
		'error'		 => ['admin/error/index'	 ,  ['method' => 'get' ]],
]);



//用户管理模块
Route::group('u',[
		'index'		 => ['admin/user/index'      ,  ['method' => 'get' ]],
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