<?php
namespace app\admin\model;
use think\Model;

class AuthGroupAccess extends Model
{
	protected $name = 'auth_group_access';
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    //定义时间戳字段名
    protected $createTime = 'create_time';
    //关闭自动写入updataTime
    protected $updateTime = false;
}


?>