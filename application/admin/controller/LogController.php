<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\model\Log;
/* 
 * 日志类 
 * 每天生成一个日志文件，当文件超过指定大小则备份日志文件并重新生成新的日志文件 
*/
class LogController extends Controller{ 
  
 private $maxsize = 1024000; //最大文件大小1M 
  
 //写入日志 
 /**
  * [writeLog 写入日志]
  * @param  [type] $filename [文件名称]
  * @param  [type] $msg      [记录链接]
  * @param  [type] $type     [类型/ajax/url]
  * @param  [type] $method   [get/post]
  * @param  [type] $user     [操作者]
  * @param  [type] $code     [状态码]
  * @param  [type] $ip   	 [操作ip]
  * @return [type]           [description]
  */
	 public function writeLog($filename,$msg,$type,$method,$user,$code,$ip){ 
		 $res = array(); 
		 $res['url']     = $msg; 
		 $res['logtime'] = date("Y-m-d H:i:s",time()); 
	  	 $res['type']    = $type;
	  	 $res['method']  = $method;
	  	 $res['user'] 	 = $user;
	  	 $res['code']    = $code;
	  	 $res['ip']		 = $ip;
		 //如果日志文件超过了指定大小则备份日志文件 
		 if(file_exists($filename) && (abs(filesize($filename)) > $this->maxsize)){ 
		  $newfilename = dirname($filename).'/'.time().'-'.basename($filename); 
		  rename($filename, $newfilename); 
		 } 
	  
		 //如果是新建的日志文件，去掉内容中的第一个字符逗号 
		 if(file_exists($filename) && abs(filesize($filename))>0){ 
		  $content = ",".json_encode($res); 
		 }else{ 
		  $content = json_encode($res); 
		 }
		 //往日志文件内容后面追加日志内容 
		 // file_put_contents($filename, $content, FILE_APPEND); 

		 //写入数据库
		 Log::create($res);
	 } 
  
  
	 //读取日志 
	 public function readLog($filename){ 
		 if(file_exists($filename)){ 
		  $content = file_get_contents($filename); 
		  $json = json_decode('['.$content.']',true); 
		 }else{ 
		  $json = '{"msg":"The file does not exist."}'; 
		 } 
	 	 return $json; 
	 } 



	// //数据库提供log日志接口
	 public function logInfo()
	 {
        //分页和搜索的条件的参数
	    $param = Request::instance()->param();
	    $page  = $param['page'];
	    $limit = $param['limit'];
	    $page = $page == '1' ? '0' : ($page - 1) * $limit;


		$obj = new Log();
		$log = [];
        $log['code']  = 0;
        $log['msg']   = "";
        $log['count'] = $obj->count();
        $log['data']  = $data = $obj->order('id','desc')->limit($page,$limit)->select();
		$json = json_encode($log);
		echo $json;
	 }


} 


?>