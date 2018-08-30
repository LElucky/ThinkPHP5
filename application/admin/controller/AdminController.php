<?php
namespace app\admin\controller;
//后台 首页
class AdminController extends BaseController
{
	public function index()
	{
		return $this->fetch();
	}

	public function main()
	{
		return $this->fetch();
	}
}

?>