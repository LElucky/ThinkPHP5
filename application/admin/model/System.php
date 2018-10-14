<?php
namespace app\admin\model;
use think\Model;

class System extends Model
{
	protected $name = 'www_web';

	//开启自动写入时间戳
	protected $autoWriteTimestamp = true;
	//关闭自动写入
	protected $createTime = false;
	//定义时间戳字段名
	protected $updateTime = 'update_time';
}



?>