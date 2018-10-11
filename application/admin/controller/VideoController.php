<?php
namespace app\admin\controller;
use think\Request;
use app\admin\model\VideoList;
class VideoController extends BaseController
{
	// 电影列表页面
	public function index()
	{
		return $this->fetch();
	}

	//电影列表接口
	public function videoList()
	{
    	$obj = new VideoList();
    	$param = Request::instance()->param();

    	//条件
    	$where = [];
    	if(isset($param['key'])){
    		$where['title'] = ['like','%'.$param['key'].'%'];
    	}

    	//分页
        $page  = $param['page'];
        $limit = $param['limit'];
        $page  = $page == '1' ? '0' : ($page - 1) * $limit;

        $data['code']  = 0;
        $data['msg']   = "";
        $data['count'] = $obj->count();
        $data['data'] = $obj->where($where)->order('id','desc')->limit($page,$limit)->select();
        echo json_encode($data);
	}

	// 添加页面展示
	public function videoAdd()
	{
		return $this->fetch();
	}

	//保存添加数据
	public function addSave()
	{
		$data = Request::instance()->param();
		$res = [];
		if(isset($data['top']) && $data['top'] == 'on'){
			$data['top'] = 1;
		}else{
			$data['top'] = 0;
		}
		if($data['id'] == '0'){
			unset($data['id']);
			$res['msg'] = VideoList::create($data) ? '添加成功' : '添加失败';
		}else{
			$res['msg'] = VideoList::update($data) ? '修改成功' : '修改失败';
		}
		echo json_encode($res['msg']);
	}

	//视频删除
	public function delete($id)
	{
		if(is_array($id)){
			foreach ($id as $key => $value) {
				$data = VideoList::get($value);
				$data['image'] != '' ? @unlink(VIDEO_UPLOADS_IMG.$data['image']) : '';
			}
			$str = VideoList::destroy($id) ? '删除成功' : '删除失败';
		}else{
			$result = VideoList::get($id);
			$result['image'] != '' ? @unlink(VIDEO_UPLOADS_IMG.$result['image']) : '';
			$str = VideoList::destroy($id) ? '删除成功' : '删除失败';
		}
		echo $str;
	}

	//ajax顶置操作
	public function topStatus()
	{
		$data = Request::instance()->param();
		$str = VideoList::update($data) ? '成功' : '失败';
		$strs = $data['top'] == '1' ? '顶置' : '取消顶置';
		echo $strs.$str;
	}
}


?>