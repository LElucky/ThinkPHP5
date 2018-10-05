<?php
namespace app\admin\controller;
/**
 * Created by PhpStorm.
 * User: lucky
 * Date: 2018/9/1
 * Time: 23:06
 */
use app\admin\model\AuthRule;
use think\Loader;
use think\Request;
class AuthconfigController extends BaseController
{
	// 
    public function authRule()
    {
    	return $this->fetch();
    }

    //规则展示页面接口
    public function authRuleInfo()
    {
    	$data  = [];
    	$obj   = new AuthRule();
    	$param = Request::instance()->param();
        $page  = $param['page'];
        $limit = $param['limit'];
        $page  = $page == '1' ? '0' : ($page - 1) * $limit;


        //判断是否有条件
        $where = [];
        if(isset($param['key'])){
            foreach ($param['key'] as $key => $value):
                $where[$key] = ['like','%'.$value.'%'];
            endforeach;
        }

        $data['code']  = 0;
        $data['msg']   = "";
        $data['count'] = $obj->count();
        // $data['data'] = $obj->whereOr($where)->order('create_time','desc')->limit($page,$limit)->select();

        $pid = 0;
        $data['data'] = $obj->getAuthRule($pid,$where);
        echo json_encode($data);
    }

    //规则添加/修改 页面
    public function authRuleAdd()
    {
        $obj = new AuthRule();
        $option = $obj->getAuthRule();

        $options = '';
        foreach($option as $key => $value){
            $options .= '<option value='.$value['id'].' lay-value='.$value['id'].'>'.str_repeat('--', $value['space']).$value['title'].'<option>';
        }
        $this->assign(['option'=>$options]);
    	return $this->fetch();
    }


    //修改规则状态 和是否显示
    public function ajaxStatus()
    {
        $data = Request::instance()->param();
        $msg['msg'] = AuthRule::where('id',$data['id'])->update([$data['type']=>$data['val']]) ? '修改成功' : '修改失败';
        return json_encode($msg);
    }

    //保存规则添加数据
    public function addSave()
    {
    	$data = Request::instance()->param();


        //数据验证类
        $validate = Loader::Validate('AuthChecked');
        if(!$validate->scene('add_edit')->check($data)){
            $res['msg']  = $validate->getError();
            $res['code'] = 0;
            echo json_encode($res);
            exit;
        }

    	if($data['id'] != '0'){
    	    $msg['msg'] = AuthRule::where('id',$data['id'])->update($data) ? '修改成功' : '修改失败';
        }else{
            unset($data['id']);
            $msg['msg'] = AuthRule::create($data) ? '添加成功' : '添加失败';
        }
    	return json_encode($msg);
    }

    //删除规则
    public function delete($id)
    {
        return AuthRule::destroy($id) ? '删除成功' : '删除失败';
    }

    //Ajax 修改排序状态
    public function sortEdit()
    {
        $data = Request::instance()->param();
        //数据验证类
        $validate = Loader::Validate('AuthChecked');
        if(!$validate->scene('ajax_sort')->check($data)){
            $res['msg']  = $validate->getError();
            $res['code'] = 0;
            echo json_encode($res);
            exit;
        }

        $res['msg'] = AuthRule::update($data) ? '修改成功' : '修改失败';
        $res['code'] = 1;
        return json_encode($res);
    }

}