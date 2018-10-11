<?php
namespace app\admin\controller;
use app\admin\model\AuthUser;
use think\Controller;
use think\Request;

//登陆
class LoginController extends Controller
{
	public function login()
	{
	    session('admin_user_info',null);
		return $this->fetch();
	}

	public function loginValidate()
    {
    	$mes = [];
        $data = Request::instance()->param();
        $ip = Request::instance()->ip();
        if (!captcha_check($data['captcha'])) {
        	$mes['mes'] = '验证码错误';
        	$mes['sta'] = '1';

        }else{
        	$obj = new AuthUser();
        	$where['nickname'] = ['=',$data['nickname']];
        	$where['password'] = ['=',md5($data['password'])];
        	$result = $obj->where($where)->find();
        	//记录登录时间
            $obj->where('id',$result['id'])->update(['last_login'=>time(),'ip'=>$ip]);
            $mes['mes'] = $result ? '跳转中...' : '用户信息错误';
            $mes['sta'] = $result ? '0' : '1';
        }

        if($mes['sta'] == '0'){
            if($result['status'] ==1){
                $admin_json_info = json_encode($result);
                session('admin_user_info',$admin_json_info);
            }else{
                $mes['mes'] ='账号被限制';
                $mes['sta'] = '1';
            }

        }

        return json_encode($mes);
    }
} 


?>