<?php
namespace app\admin\controller;
use app\admin\model\AuthRule;
use think\Request;
//后台 首页
class AdminController extends BaseController
{
	//后台界面 首页
	public function index()
	{
		$obj = new AuthRule();
		$one_menu = $obj->field('title,name,icon')->where('pid','0')->where('name','<>','ADMIN')->order('sort asc')->select();
		$this->assign([
			'one_menu' => $one_menu,
		]);
		return $this->fetch();
	}

	//'后台首页中的嵌套页面'
	public function main()
	{
		return $this->fetch();
	}

	// nav接口
	public function navList()
	{
		$obj = new AuthRule();
		$where['is_show'] = ['=','1'];
		$where['status']  = ['=','1'];
		$where['level']   = ['=','1'];
		$where['name']    = ['<>','ADMIN'];
		//最大 导航横着的栏目
		$navdata = $obj->where($where)->order('sort asc')->select();
		$nav = [];
		$where_son['is_show'] = ['=','1'];
		$where_son['status']  = ['=','1'];
		$where_son['level']   = ['=','2'];
		//循环查找每个大栏目下的第一级栏目
 		foreach ($navdata as $key => $value) {
			$son_data = $obj->where($where_son)->where('pid',$value['id'])->order('sort asc')->select();
			$nav[$value['name']] = [];
			//循环拼接
			foreach ($son_data as $k_son => $v_son) {
				$temp_data = $obj->where('pid',$v_son['id'])->select();
				if($temp_data){
				//如果有二级栏目
					$array = [];
					//循环拼接
					foreach ($temp_data as $k1 => $v1) {
						$temp_array = ['title'=>$v1['title'],'href'=>$v1['route'],'icon'=>$v1['icon'],'spread'=>'false'];
						array_push($array, $temp_array);
					}
					$temp = ['title'=>$v_son['title'],'href'=>'','icon'=>$v_son['icon'],'spread'=>'false','children'=>$array];
				}else{
					$temp = ['title'=>$v_son['title'],'href'=>$v_son['route'],'icon'=>$v_son['icon'],'spread'=>'false'];
				}
				array_push($nav[$value['name']], $temp);
			}
		}
		// print_r($nav);
		echo json_encode($nav);
	}


}

?>