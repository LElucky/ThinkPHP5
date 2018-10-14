// 权限管理组

layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        laypage = layui.laypage,
        $ = layui.jquery;

    var systemParameter;
    form.on("submit(systemParameter)",function(data){
        systemParameter = '{"title":"'+$(".title").val()+'",';  //模版名称
        systemParameter += '"status":"'+$("[name='status']").val()+'",'; //默认关键字
        systemParameter +='"id":"'+$("[name='id']").val()+'"}';
        window.sessionStorage.setItem("systemParameter",systemParameter);
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            $.ajax({
                url : "/authg/authGroupSave",
                type : "POST",
                data : {'data':systemParameter},
                success : function(data){
                    var data = JSON.parse(data)
                    layer.close(index)
                    layer.msg(data.code)
            //刷新父页面
            parent.location.reload();                   
                },
                error:function(){
                    alert('系统异常')
                }
            })
        },500);
        return false;
    })
})
