<?php
namespace app\admin\model;
use think\Model;
use think\Request;
class AuthUser extends Model
{
	
	// 用户表
	protected $name = 'auth_user';
	//开启自动写入时间戳
	protected $autoWriteTimestamp = true;
	//定义时间戳字段名
	protected $createTime = 'create_time';
	//关闭自动写入updataTime
	protected $updateTime = false;

    protected static function init()
    {
    	// 钩子  插入数据库前如果选择了上传图片
        AuthUser::event('before_insert', function ($data) {
        	$file = Request::instance()->file('file');
        	if($file){
        		$info = $file->validate(['ext'=>'jpg,png,jpeg,git','size'=>'200000000'])->move(AUTH_USER_UPLOADS_IMG);
        		if($info){
        			$data['user_img'] = $info->getSaveName();
        		}else{
                    $data['user_img'] = $file->getError();
        		}
        	}
        });



        // 钩子  修改数据库前如果选择了上传图片
        AuthUser::event('before_update', function ($data) {
            if($data['id']){
                $OBJ = new AuthUser();
        //获取旧的图片 删掉
                $user_img = $OBJ->field('user_img')->where('id',$data['id'])->value('user_img');
                $user_img ? @unlink(AUTH_USER_UPLOADS_IMG.$user_img) : '' ;
            }
            $file = Request::instance()->file('file');
            if($file){
                $info = $file->validate(['ext'=>'jpg,png,jpeg,git','size'=>'200000000'])->move(AUTH_USER_UPLOADS_IMG);
                if($info){
                    $data['user_img'] = $info->getSaveName();
                }else{
                    $data['user_img'] = $file->getError();
                }
            }
        });


    }

}

?>