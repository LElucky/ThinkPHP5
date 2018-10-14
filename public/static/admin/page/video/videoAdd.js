layui.use(['form','layer','layedit','laydate','upload','laydate'],function(){
    var form = layui.form
        layer = parent.layer === undefined ? layui.layer : top.layer,
        laypage = layui.laypage,
        upload = layui.upload,
        layedit = layui.layedit,
        laydate = layui.laydate,
        laydate = layui.laydate,
        $ = layui.jquery;

    //用于同步编辑器内容到textarea
    layedit.sync(editIndex);


    //上线字段点击出现 日期选择框
    laydate.render({
        elem: '#online_time',
        // type: 'datetime',
        trigger : "click",
        done : function(value, date, endDate){
            lay('#online_time').val(value);
        }
    });
    //格式化时间
    function filterTime(val){
        if(val < 10){
            return "0" + val;
        }else{
            return val;
        }
    }
    //定时发布
    var time = new Date();
    var submitTime = time.getFullYear()+'-'+filterTime(time.getMonth()+1)+'-'+filterTime(time.getDate())+' '+filterTime(time.getHours())+':'+filterTime(time.getMinutes())+':'+filterTime(time.getSeconds());
    laydate.render({
        elem: '#release',
        type: 'datetime',
        trigger : "click",
        done : function(value, date, endDate){
            submitTime = value;
        }
    });
    form.on("radio(release)",function(data){
        if(data.elem.title == "定时发布"){
            $(".releaseDate").removeClass("layui-hide");
            $(".releaseDate #release").attr("lay-verify","required");
        }else{
            $(".releaseDate").addClass("layui-hide");
            $(".releaseDate #release").removeAttr("lay-verify");
            submitTime = time.getFullYear()+'-'+(time.getMonth()+1)+'-'+time.getDate()+' '+time.getHours()+':'+time.getMinutes()+':'+time.getSeconds();
        }
    });

    form.verify({
        title : function(val){
            if(val == ''){
                return "文章标题不能为空";
            }
        },
        content : function(val){
            if(val == ''){
                return "文章内容不能为空";
            }
        }
    })
    form.on("submit(addNews)",function(data){
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});


        from = new FormData(document.getElementById("video_add"))
        $.ajax({
            url:'/videos/addSave',
            type:'POST',
            data:from,
            processData:false,
            contentType:false,
            beforeSend:function(){
               index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.5});
            },
            success:function(res){
                var res = JSON.parse(res)
                 top.layer.close(index);
                top.layer.msg(res);
                layer.closeAll("iframe");
                //刷新父页面
                parent.location.reload();
            },
            error:function(res){
                top.layer.msg(res.msg);
        }
    })
        // return false;
    })

    //提示状态
    function status(mes){
        setTimeout(function(){
           
        },1000);
    }


    //预览
    form.on("submit(look)",function(){
        layer.alert("此功能需要前台展示，实际开发中传入对应的必要参数进行文章内容页面访问");
        return false;
    })

    //创建一个编辑器
    var editIndex = layedit.build('news_content',{
        height : 535,
        uploadImage : {
            url : "../../json/newsImg.json"
        }
    });


// 上传图片预览
$("#video_img_file").change(function(){  
     var objUrl = getObjectURL(this.files[0]) ;//获取文件信息  
      if (objUrl) {  
        console.log(objUrl)
      $(".thumbImg").attr("src", objUrl); 
     }   
}) ;  
function getObjectURL(file) {  
     var url = null;   
     if (window.createObjectURL!=undefined) {  
      url = window.createObjectURL(file) ;  
     } else if (window.URL!=undefined) { // mozilla(firefox)  
      url = window.URL.createObjectURL(file) ;  
     } else if (window.webkitURL!=undefined) { // webkit or chrome  
      url = window.webkitURL.createObjectURL(file) ;  
     }  
     return url;  
}  

})