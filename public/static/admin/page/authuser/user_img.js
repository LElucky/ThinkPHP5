
// 没用
// 用户图片上传
layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;
  
  //普通图片上传
  var uploadInst = upload.render({
     elem: '#user_img'
    ,url: '/au/addUser' 					//后台接收地址
	,auto: false						//不自动上传设置
	,accept: 'file'				 		//允许上传的文件类型
	,exts: 'png|jpg|jpeg'				//设置智能上传图片格式文件
	,size: 5000 						//最大允许上传的文件大小
	,multiple: false					//设置是否多个文件上传    
    ,before: function(obj){
      //预读本地文件示例，不支持ie8
      obj.preview(function(index, file, result){
        $('#demo1').attr('src', result); //图片链接（base64）
      });
    }
    ,done: function(res){
      //如果上传失败
      if(res.code > 0){
        return layer.msg('上传失败');
      }

      //上传成功
    }
    ,error: function(){
      //演示失败状态，并实现重传
      var demoText = $('#demoText');
      demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
      demoText.find('.demo-reload').on('click', function(){
        uploadInst.upload();
      });
    }
  });

})