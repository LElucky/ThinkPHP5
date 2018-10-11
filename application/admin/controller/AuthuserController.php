<?php
namespace app\admin\controller;
use think\Db;
use think\Loader;
use think\Request;
use app\admin\model\AuthUser;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
// 管理员中心中心
class AuthuserController extends BaseController
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

		$obj = new AuthUser();
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
		$obj = new AuthGroup();
		$data = $obj->where('status','1')->column('id,title');
		$str = '';
		foreach ($data as $key => $value) {
			$str .= "<option value=".$key.">".$value."</option>";
		}
		$this->assign(['option'=>$str]);
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
			$group_access['group_id'] = $data['group_id'];
			unset($data['group_id']);
			$result = AuthUser::create($data);
			$group_access['uid'] = $result->id;
			$result2 = AuthGroupAccess::create($group_access);
			//拿取用户关联
			$res['msg'] = (true == $result && $result2) ? '添加成功' : '添加失败';
		}else{
			//修改
			if($data['password'] == ''){
				unset($data['password']);
			}else{
			    $data['password'] = md5($data['password']);
            }
            $group_access['group_id'] = $data['group_id'];
			$group_access['uid']      = $data['id'];
			$result2 = AuthGroupAccess::where('uid',$group_access['uid'])->update(['group_id'=>$group_access['group_id']]);

            unset($data['group_id']);
			$result = AuthUser::update($data);
			$res['msg'] = $result || $result2  ? '修改成功' : '修改失败';
		}
		$res['sta'] = $result ? 1 : 0;
		return json_encode($res);
	}

//	删除用户 单个删除string / 多个删除array
	public function delete($id)
    {
		// 判断是否批量删除
    	if(is_array($id)):
    		//批量删除
            foreach($id  as $key => $value):
                $data = AuthUser::get($value);
                $data['user_img'] != '' ? @unlink(AUTH_USER_UPLOADS_IMG.$data['user_img']) : '';
            endforeach;
            $str = implode($id,',');
            $status = AuthUser::destroy($id) ? AuthGroupAccess::where('uid','in',"$str")->delete() ? '删除成功' : '删除失败' : '删除失败';
        else:
    		//单个删除 删除管理员信息+管理员权限关联
    		//删除管理员头像
            $data = AuthUser::get($id);
            $data['user_img'] != '' ? @unlink(AUTH_USER_UPLOADS_IMG.$data['user_img']) : '';
            $status = AuthUser::destroy($id) ? AuthGroupAccess::where('uid',$id)->delete() ?  '删除成功' : '删除失败' : '删除失败';
        endif;
        return  $status;
    }

//   修改用户状态角色
    public function ajaxStatus()
    {
        $data = Request::instance()->param();
        $msg['msg'] = AuthUser::where('id',$data['id'])->update(['status'=>$data['val']]) ? '修改成功' : '修改失败';
        return json_encode($msg);
    }

    //管理员列表页获取管理员的身份 / 超级管理员/管理员啊
    //管理员编辑页获取管理员管理员身份的id
    public function getAuthUser()
    {
    	$data = Request::instance()->param();
    	$obj_group_access = new AuthGroupAccess();
    	$group_ids = $obj_group_access->where('uid',$data['type'])->value('group_id');
    	$obj_group = new AuthGroup();
    	$value = ($data['count'] == '1') ? $obj_group->where('id',$group_ids)->column('title') : $obj_group->where('id',$group_ids)->column('id');
    	echo json_encode($value);
    }

    //登录当前管理员的信息
    public function adminInfo()
    {
    	$data = json_decode(session('admin_user_info'),true);
    	var_dump($data);
    }

}




?>