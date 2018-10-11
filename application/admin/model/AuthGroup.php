<?php
namespace app\admin\model;
use think\Model;
class AuthGroup extends Model
{
	protected $name = 'auth_group';
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    //定义时间戳字段名
    protected $createTime = 'create_time';
    //关闭自动写入updataTime
    protected $updateTime = false;
}


?>