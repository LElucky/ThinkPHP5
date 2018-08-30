<?php
namespace app\admin\controller;

// 系统设置
class SystemController extends BaseController
{
	// 系统设置首页
	public function index()
	{
		return $this->fetch();
	}

	//系统日志
	public function log()
	{
		return $this->fetch();
	}

	// 友情链接
	public function friend()
	{
		return $this->fetch();
	}

	// 图标管理
	public function icons()
	{
		return $this->fetch();
	}
}


?>