<?php
namespace app\admin\controller;
use think\Db;
use think\Loader;
use think\Request;
use app\admin\model\User;
// 用户中心
class UserController extends BaseController
{
	//用户信息数据展示页面
	public function index()
    {
		return $this->fetch();
	}


	//用户列表信息数据接口
	public function indexinfo()
	{

        //分页和搜索的条件的参数
	    $param = Request::instance()->param();
	    $page  = $param['page'];
	    $limit = $param['limit'];
	    $page = $page == '1' ? '0' : ($page - 1) * $limit;

        //判断是否有条件
        $where = [];
        if(isset($param['key'])){
            foreach ($param['key'] as $key => $value):
                $where[$key] = ['like','%'.$value.'%'];
            endforeach;
        }

		$obj = new User();
		$user = [];
        $user['code']  = 0;
        $user['msg']   = "";
        $user['count'] = $obj->whereOr($where)->count();
        $user['data']  = $data = $obj->field('nickname,create_time,id,email,sex,status,last_login,userinfo,user_img')->whereOr($where)->order('create_time','desc')->limit($page,$limit)->select();
		$json = json_encode($user);
		echo $json;
	}

	//添加用户页面展示
	public function addUser()
	{
		return $this->fetch();
	}

	//保存用户信息注册X修改
	public function userSave()
	{
		$data = Request::instance()->param();
		//数据验证类
		$validate = Loader::Validate('UserChecked');
		if(!$validate->check($data)){
			$res['msg']  = $validate->getError();
			$res['code'] = 0;
			echo json_encode($res);
			exit;
		}
		$res = [];
		if($data['id'] == 0){
			// 注册
			unset($data['id']);
			$data['password'] = md5($data['password']);
			$result = User::create($data);
			$res['msg'] = $result ? '添加成功' : '添加失败';
		}else{
			//修改
			if($data['password'] == ''){
				unset($data['password']);
			}else{
			    $data['password'] = md5($data['password']);
            }
			$result = User::update($data);
			$res['msg'] = $result ? '修改成功' : '修改失败';
		}
		$res['sta'] = $result ? 1 : 0;
		return json_encode($res);
	}

//	删除用户 单个删除string / 多个删除array
	public function delete($id)
    {
        return User::destroy($id) ? '删除成功' : '删除失败';
    }

//   修改用户状态角色
    public function ajaxStatus()
    {
        $data = Request::instance()->param();
        $msg['msg'] = User::where('id',$data['id'])->update(['status'=>$data['val']]) ? '修改成功' : '修改失败';
        return json_encode($msg);
    }

}




?>