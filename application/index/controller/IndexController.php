<?php
namespace app\index\controller;
use think\Request;
use think\Controller;
use app\admin\model\VideoList;
class IndexController extends Controller
{
	//首页
    public function index()
    {
        $obj = new VideoList();
        // 开发浏览
        $where['status'] = ['=','2'];
        $data = Request::instance()->param();

        //选择导航栏
        if(isset($data['keyword'])){
            switch ($data['keyword']) {
                case '1':
                    $where['area'] = ['like','%韩国%'];
                    break;
                case '2':
                    $where['type'] = ['like','%美国%'];
                    break;
                case '3':
                    $where['type'] = ['like','%喜剧%'];
                    break;
                case '4':
                    $where['type'] = ['like','%爱情%'];
                    break;
                case '5':
                    $where['type'] = ['like','%香港%'];
                    break;
                case '6':
                    $where['type'] = ['like','%青春%'];
                    break;
                case '7':
                    $where['type'] = ['like','%犯罪%'];
                    break;
                default:
                    # code...
                    break;
            }
            
        }

        //搜索
        if(isset($data['title']) && $data['title'] != ''){
            $where['title'] = ['like','%'.$data['title'].'%'];
        }

        $data_INFO = $obj->where($where)->order('id','asc')->paginate(18,false,['query'=>request()->param()]);
        foreach ($data_INFO as $key => $value) {
            if(substr($value['image'],0,'1') == '_'){
                $str = 'v'.$value['image'];
                VideoList::update(['id'=>$value['id'],'image'=>$str]);
            }
        }
        $this->assign(['data' => $data_INFO]);
        return $this->fetch();



    }

    //电影详情页
    public function videoInfo($id)
    {
    	$info = VideoList::get($id);
    	$this->assign(['info' => $info]);
    	return $this->fetch();
    }

    //播放页面
    public function player($id)
    {
    	$info = VideoList::get($id);
    	$obj = new VideoList();
    	$news_video = $obj->order('id','desc')->limit(12)->select();
    	$this->assign(['info' => $info,'news_video'=>$news_video]);
    	return $this->fetch();
    }
}
