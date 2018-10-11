<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use auth\Auth;
use think\Request;
// use app\admin\model\Log;
use app\admin\controller\LogController;
//后台 基类
class BaseController extends Controller
{

        public function _initialize(){
                if(!Session::has('admin_user_info')){
                        $this->redirect(url('/login_admin'));
                }
                $auth       = new Auth();
                $request    = Request::instance();
                $model      = $request->module();
                $controller = $request->controller();
                $function   = $request->action();
                $url        = $model.'/'.$controller.'/'.$function;
                $data = json_decode(session('admin_user_info'),true);
                
                if(!$auth->check($url,$data['id'])){
//                        echo json_encode(['msg'=>'没有权限']);
//                        exit;
                        
                        // return $this->error('没有权限','/login_admin');      
                }else{
                        $obj = new LogController();
                        //监听所有请求链接
                        $str =  'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
                        $filename = './logs/'.date('Y-m',time()).'.json';
                        $method = $this->getMethod();
                        $type = $this->getType();
                        $user = $data['nickname'];
                        $code = $_SERVER['REDIRECT_STATUS'];


                        $ip   = Request::instance()->ip();
                        $obj->writeLog($filename,$str,$type,$method,$user,$code,$ip);  
                }


        }




        //判断请求类型 get/post
        public function getMethod()
        {
                if(isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='POST'){
                        return 'POST';
                }elseif(isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='GET'){
                        return 'GET';
                }
        }
        //判断请求方法
        public function getType(){
                if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 
                    return 'AJAX';
                }else{ 
                    return 'URL'; 
                };
        }

}