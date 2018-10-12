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

	public function clearCache()
	{
		$path = "./../runtime/temp/";

		if(is_dir($path)){
			    //扫描一个文件夹内的所有文件夹和文件并返回数组
			$p = scandir($path);
			foreach($p as $val){
			//排除目录中的.和..
				if($val !="." && $val !=".."){
				//如果是目录则递归子目录，继续操作
				     if(is_dir($path.$val)){
					      //子目录中操作删除文件夹和文件
					      deldir($path.$val.'/');
					      //目录清空后删除空文件夹
					      @rmdir($path.$val.'/');
				     }else{
					      //如果是文件直接删除
					      unlink($path.$val);
				     }
			    }
			}
			echo '1';
		}else{
			echo '0';
		}
	}
}



?>