<?php
namespace app\admin\model;
use think\Model;
class Log extends Model
{
	protected $name = 'log';
    //开启自动写入时间戳
    protected $autoWriteTimestamp = false;
    //定义时间戳字段名
    protected $createTime = false;
    //关闭自动写入updataTime
    protected $updateTime = false;
}


?>