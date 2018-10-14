// 权限管理组

layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        laypage = layui.laypage,
        $ = layui.jquery;

    form.on("submit(systemParameter)",function(data){

    var arr = [];
    $("input:checkbox[name='rule_id']:checked").each(function() { // 遍历name=test的多选框
        arr.push($(this).val()); // 每一个被选中项的值
    });

    var id = $('[name="rule_group_id"]').val()


        window.sessionStorage.setItem("systemParameter",arr);
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            $.ajax({
                url : "/authg/authjGroupRuleSave",
                type : "POST",
                data : {'data':arr,'id':id},
                success : function(data){
                    var data = JSON.parse(data)
                    layer.close(index)
                    layer.msg(data.msg)

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
