<?php
namespace app\admin\controller;
use think\Request;
use think\Loader;
use app\admin\model\System;

// 系统设置
class SystemController extends BaseController
{
	// 系统设置首页
	public function index()
	{
		return $this->fetch();
	}

	//index页面 系统基本参数接口
	public function indexInfo()
	{
		$info = System::get(1);
		echo json_encode($info);
	}

	//修改保存接口
	public function editInfo()
	{
		$data = Request::instance()->param();
		$info = json_decode($data['data'],true);
		$info['id'] = 1;

		//数据验证类
		$validate = Loader::Validate('SystemChecked');
		if(!$validate->scene('edit')->check($data)){
			$res['msg']  = $validate->getError();
			echo json_encode($res['msg']);
			exit;
		}

		$msg = System::update($info) ? '系统基本参数修改成功！' : '系统基本参数修改失败！';
		echo $msg;
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