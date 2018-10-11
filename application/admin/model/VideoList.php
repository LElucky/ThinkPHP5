<?php
namespace app\admin\model;
use think\Model;
use think\Request;
class VideoList extends Model
{
	
	// 用户表
	protected $name = 'video_list';
	//开启自动写入时间戳
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';

    protected static function init()
    {
    	// 钩子  插入数据库前如果选择了上传图片
        VideoList::event('before_insert', function ($data) {
        	$file = Request::instance()->file('file');
        	if($file){
        		$info = $file->validate(['ext'=>'jpg,png,jpeg,git','size'=>'200000'])->move(VIDEO_UPLOADS_IMG);
        		if($info){
        			$data['image'] = $info->getSaveName();
        		}else{
                    $data['image'] = $file->getError();
        		}
        	}
        });



        // 钩子  修改数据库前如果选择了上传图片
        VideoList::event('before_update', function ($data) {
            $file = Request::instance()->file('file');
            if($data['id'] != '0' && $file != false){
                $OBJ = new VideoList();
        //获取旧的图片 删掉
                $image = $OBJ->field('image')->where('id',$data['id'])->value('image');
                $image ? @unlink(VIDEO_UPLOADS_IMG.$image) : '' ;
            // }
            // if($file){
                $info = $file->validate(['ext'=>'jpg,png,jpeg,git','size'=>'200000'])->move(VIDEO_UPLOADS_IMG);
                if($info){
                    $data['image'] = $info->getSaveName();
                }else{
                    $data['image'] = $file->getError();
                }
            }
        });


    }

}

?>