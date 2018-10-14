layui.use(['form','layer','upload'],function(){
    var form = layui.form
        layer = parent.layer === undefined ? layui.layer : top.layer,
        upload = layui.upload;
        $ = layui.jquery;


    form.on("submit(addUser)",function(data){
        //弹出loading
        // var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 实际使用时的提交信息
        from = new FormData(document.getElementById("user_save"))
        $.ajax({
            url:'/u/addSave',
            type:'POST',
            data:from,
            processData:false,
            contentType:false,
            beforeSend:function(){
               index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.5});
            },
            success:function(res){
                var res = JSON.parse(res)
                status(res.msg)
            },
            error:function(res){
                status('系统异常')
            }
        })
        return false;
    })

    //提示状态
    function status(mes){
        setTimeout(function(){
            top.layer.close(index);
            top.layer.msg(mes);
            layer.closeAll("iframe");
            //刷新父页面
            parent.location.reload();
        },1000);
    }

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

})

