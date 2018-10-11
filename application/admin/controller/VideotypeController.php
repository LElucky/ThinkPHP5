<?php
namespace app\admin\controller;
use app\admin\model\VideoType;
use think\Loader;
use think\Request;
class VideotypeController extends BaseController
{
    /*电影分类页面展示
     * */
    public function index()
    {
        return $this->fetch();
    }


    /*电影分类页面接口*/
    public function typeindex()
    {
    	$obj = new VideoType();
        $data['code']  = 0;
        $data['msg']   = "";
        $data['count'] = $obj->count();
        $data['data'] = $obj->order('sort','desc')->select();
        echo json_encode($data);
    }


    //修改规则状态
    public function ajaxStatus()
    {
        $data = Request::instance()->param();
        $msg['msg'] = VideoType::where('id',$data['id'])->update(['status'=>$data['val']]) ? '修改成功' : '修改失败';
        return json_encode($msg);
    }





    //Ajax 修改分类内容
    public function sortEdit()
    {
        $data = Request::instance()->param();

        //数据验证类
        $check = [$data['field']=>$data['value'],'id'=>$data['id']];
        $validate = Loader::Validate('VideotypeChecked');
        if(!$validate->scene('edit')->check($check)){
            $res['msg']  = $validate->getError();
            $res['code'] = 0;
            echo json_encode($res);
            exit;
        }else{    	
	        $res['msg'] = VideoType::where('id',$data['id'])->update([$data['field']=>$data['value']]) ? '修改成功' : '修改失败';
	        if($res['msg'] == '修改成功'):
	        	$res['code'] = 1;
	        else:
	        	$res['code'] = 0;
	        endif;
	        return json_encode($res);
        }
    }

    public function addSave()
    {
    	$data['url']   = 'https://www.baidu.com/';
    	$data['title'] = '标题';
    	$data['count'] = 0;
    	$data['create_time'] = time();
    	$data['status']	 = '1';
        //数据验证类
        $validate = Loader::Validate('VideotypeChecked');
        if(!$validate->scene('add')->check($data)){
            $res['msg']  = $validate->getError();
            $res['code'] = 0;
            echo json_encode($res);
            exit;
        }else{    	
	        $res['msg'] = VideoType::create($data) ? '添加成功' : '添加失败';
	        if($res['msg'] == '添加成功'):
	        	$res['code'] = 1;
	        else:
	        	$res['code'] = 0;
	        endif;
	        return json_encode($res);
        }
    	
    }
    //删除规则
    public function delete($id)
    {
        return VideoType::destroy($id) ? '删除成功' : '删除失败';
    }

}


?>