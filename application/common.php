<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
 
 
 

 //根据ip获取城市、网络运营商等信息
function findCityByIp($ip){
  $data = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
  return json_decode($data,$assoc=true);
 }



  //写入日志 
 /**
  * [writeLog 写入日志]
  * @param  [type] $filename [文件名称]
  * @param  [type] $ip      [ip]
  * @param  [type] $地址     [地址]
  * @param  [type] $time   [时间]
  */
 function writeLog($filename,$ip,$area,$time){ 
		 $res = array(); 
		 $res['time']    = $time; 
	  	 $res['area']    = $area;
	  	 $res['ip']		 = $ip;
		 //如果日志文件超过了指定大小则备份日志文件  1M
		 if(file_exists($filename) && (abs(filesize($filename)) > 1024000)){ 
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
		 file_put_contents($filename, $content, FILE_APPEND); 
	 } 幸福的成功的非官方的