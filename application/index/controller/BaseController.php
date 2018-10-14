<?php
namespace app\index\controller;
use think\Controller;
use think\View;
use app\admin\model\System;
use think\Request;
class BaseController extends Controller
{
	public function _initialize()
	{
		$obj = new System();
		$web_info = $obj->field('webtitle,webdesc,webkeys')->find();
		View::share([
			'web_info' => $web_info,
		]);
	}


}



?>