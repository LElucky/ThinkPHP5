<?php
namespace app\index\controller;
use think\Request;

use app\admin\model\VideoList;
use app\admin\model\VideoType;
class IndexController extends BaseController
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

        $data_INFO = $obj->where($where)->order('top desc,update_time desc')->paginate(18,false,['query'=>request()->param()]);


        // $data_INFO = $obj->where($where)->order('id desc')->paginate(200);


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

        $obj = new VideoList();

    	$info = VideoList::get($id);

        //侧边
        $where['status'] = ['=','2'];
        $where['top']    = ['=','1'];
        $six = $obj->where($where)->order('update_time','desc')->limit(6)->select();

        //备用地址
        $obj_type = new VideoType();
        $back_url = $obj_type->where('status','1')->order('sort','desc')->limit(5)->select();

    	$this->assign(['info' => $info,'six' => $six, 'back_url' => $back_url]);
    	return $this->fetch();
    }

    //播放页面
    public function player()
    {
        $api_obj = new VideoType();
        $url = Request()->path();
        $arr = explode('/', $url);
        //id = 视频id
        //type = 视频前缀api  id
        if(count($arr) == '3'){
            $id = $arr['2'];
            $api_url = $api_obj->field('id,url')->where('status','1')->order('sort','desc')->find();
        }elseif(count($arr) == '4'){
            $id = $arr['2'];
            $api_url = $api_obj->field('id,url')->where('id',$arr['3'])->where('status','1')->find();
        }

        //备用地址
        $obj_type = new VideoType();
        $back_url = $obj_type->where('status','1')->order('sort','desc')->limit(5)->select();


        $info = VideoList::get($id);
        $obj = new VideoList();
                //侧边
        $where['status'] = ['=','2'];
        $where['top']    = ['=','1'];
        $news_video = $obj->where($where)->order('update_time','desc')->limit(12)->select();

        //seo优化
        $web_info = [];
        $web_info['webtitle'] = $info['webtitle'];
        $web_info['webdesc']  = substr($info['webdesc'],0);
        $web_info['webkeys']  = $web_info['webdesc']; 
        $this->assign([
            'info'       => $info,
            'news_video' =>$news_video,
            'api_url'    =>$api_url,
            'back_url'   => $back_url,
            'web_info'   =>$web_info
        ]);
    	return $this->fetch();
    }

    public function error404()
    {
        return $this->fetch();
    }
}
