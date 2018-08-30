<?php
namespace app\admin\controller;

class HelperController extends BaseController
{
	// 三级联动
	public function one()
	{
		return $this->fetch();
	}

	// bodyTab模块
	public function two()
	{
		return $this->fetch();
	}

	//三级菜单
	public function three()
	{
		return $this->fetch();
	}
}



?>