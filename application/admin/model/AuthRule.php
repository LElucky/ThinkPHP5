<?php
namespace app\admin\model;
use think\Model;
class AuthRule extends Model
{
	protected $name = 'auth_rule';
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    //定义时间戳字段名
    protected $createTime = 'create_time';
    //关闭自动写入updataTime
    protected $updateTime = false;

	//递归的排序 无限极
	public function getAuthRule($pid=0,$where=[])
	{
		static $arr = [];
		static $n = 0;
		$rowset = $this->where('pid',$pid)->where($where)->order('sort asc')->select();
		foreach($rowset  as $key => $row){
			$row['space'] = $n;
			$arr[] = $row;
			$n++;
			$func = __FUNCTION__;
			$this->$func($row['id']);
			$n--;
		}
		return $arr	;
	}

}


?>