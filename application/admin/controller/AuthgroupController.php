<?php
namespace app\admin\controller;
use think\Request;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
class AuthgroupController extends BaseController
{

    //添加管理组的展示页面
	public function authGroup()
	{
		return $this->fetch();
	}


	public function authList()
    {
        $data = [];
        $obj = new AuthGroup();
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
        $data['data'] = $obj->whereOr($where)->order('create_time','desc')->limit($page,$limit)->select();
        echo json_encode($data);
    }

	//管理组的保存方法
	public function authGroupSave()
    {
        $result = [];
        $data = json_decode(Request::instance()->param()['data'],true);
        if($data['id'] == '0'){
            unset($data['0']);
            $result['code'] = AuthGroup::create($data) ? '添加成功' : '添加失败';
        }else{
            $result['code'] = AuthGroup::update($data) ? '修改成功' : '修改失败';
        }
        echo json_encode($result);
    }

    //管理组添加展示页面
    public function authGroupAdd()
    {
        return $this->fetch();
    }

    //修改规则状态
    public function ajaxStatus()
    {
        $data = Request::instance()->param();
        $msg['msg'] = AuthGroup::where('id',$data['id'])->update(['status'=>$data['val']]) ? '修改成功' : '修改失败';
        return json_encode($msg);
    }


    //删除管理组
    public function delete($id)
    {
        return AuthGroup::destroy($id) ? '删除成功' : '删除失败';
    }

    //编辑权限页面展示
    public function authGroupRule()
    {
        $obj = new AuthRule();
        //一级权限节点
        $data_one = $obj->where('pid','0')->order('sort','asc')->select();
        //二级权限节点
        $data_two = $obj->where('pid','neq','0')->order('sort','asc')->select();

        //管理员权限
        $id = Request::instance()->param()['id'];
        $ok_rule = AuthGroup::get($id);
        $arr = explode(',',$ok_rule['rules']);

        $this->assign(['data_one'=>$data_one,'data_two'=>$data_two,'ok_rule'=>$arr]);
        return $this->fetch();
    }

    //编辑权限修改数据
    public function authjGroupRuleSave()
    {
        $data = Request::instance()->param();
        $str = implode($data['data'], ',');
        $obj = new AuthGroup();
        $msg['msg'] =  $obj->where('id',$data['id'])->update(['rules'=>$str]) ? '配置成功' : '配置失败';
        return json_encode($msg);
    }   




}

?>