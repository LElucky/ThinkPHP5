layui.use(['form','layer','laydate','table','laytpl','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery,
        laydate = layui.laydate,
        laytpl = layui.laytpl,
        table = layui.table;

    //添加验证规则
    form.verify({
        oldPwd : function(value, item){
            if(value != "123456"){
                return "密码错误，请重新输入！";
            }
        },
        newPwd : function(value, item){
            if(value.length < 6){
                return "密码长度不能小于6位";
            }
        },
        confirmPwd : function(value, item){
            if(!new RegExp($("#oldPwd").val()).test(value)){
                return "两次输入密码不一致，请重新输入！";
            }
        }
    })

//监听修改内容
table.on('edit(videoType)', function(obj){ //注：edit是固定事件名，videoType是table原始容器的属性 lay-filter="对应的值"
  // console.log(obj.value); //得到修改后的值
  // console.log(obj.field); //当前编辑的字段名
  var field = obj.field;
  // console.log(obj.data); //所在行的所有相关数据  
  $.post('/video/sortEdit',{'id':obj.data.id,'field':field,'value':obj.value},function(result){
    var res = JSON.parse(result);
    //等于0 则表示处理异常
    if(res.code == '0'){
        top.layer.msg(res.msg)
    }
  })
});


    //用户等级
    tableVideoType = table.render({
        elem: '#videoType',
        url : '/video/typeindex',
        cellMinWidth : 95,
        cols : [[
            {field:"id", title: 'ID', width: 60, fixed:"left",sort:"true", align:'center', edit: 'text'},
            {field: 'title', title: 'API名称', edit: 'text', align:'center'},
            {field: 'url', title: 'URL', edit: 'text',sort:"true", align:'center'},
            {field: 'sort', title: '排序', edit: 'text',sort:"true", align:'center'},
            {field: 'create_time', title: '添加时间', sort:"true", align:'center'},
            {field: 'status', title: '状态管理', align:'center',templet:function(d){
                switch (d.status)
                {
                    case 0:
                        var status = '';
                        break;
                    case 1:
                        var status = 'checked';
                        break;
                }
                    return '<input type="checkbox" name="status" data-index="'+d.id+'" lay-filter="status" lay-skin="switch" lay-text="Yes|No" '+status+'>';
                },minWidth:80},
                {title: '操作', minWidth:95,templet:'#ruleListBar',fixed:"right",align:"center"}
        ]]
    });

    form.on('switch(status)', function(data){
        //获取数据的id
        var id  = $(this).attr('data-index')

        var tipText = '确定禁用当前分类？';
        if(data.elem.checked){
            tipText = '确定启用当前分类？'
        }
        layer.confirm(tipText,{
            icon: 3,
            title:'系统提示',
            cancel : function(index){
                data.elem.checked = !data.elem.checked;
                form.render();
                layer.close(index);
            }
        },function(index){
            layer.close(index);

            var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
            var val = data.elem.checked //开关是否开启，true或者false
            //获取要改变成的状态
            if(val == true){
                var value = '1'
            }else{
                var value = '0'
            }
            
            $.post('/video/ajaxStatus',{'id':id,'val':value,'type':'status'},function(data){
                var data = JSON.parse(data)
                setTimeout(function(){
                    var val = value == '1' ? '正常使用' : '限制使用'
                    $("."+id+'_status').text(val)
                    top.layer.msg(data.msg)
                    layer.close(index);
                },200)
            })


        },function(index){
            data.elem.checked = !data.elem.checked;
            form.render();
            layer.close(index);
        });
    });
    //新增等级
    $(".addGrade").click(function(){
            $.post('/video/addSave',function(result){
                var result = JSON.parse(result)
                //弹出提示
                top.layer.msg(result.msg)
                //刷新表格
                tableVideoType.reload()
            })

    });

    //控制表格编辑时文本的位置【跟随渲染时的位置】
    $("body").on("click",".layui-table-body.layui-table-main tbody tr td",function(){
        $(this).find(".layui-table-edit").addClass("layui-"+$(this).attr("align"));
    });


 //列表操作
    table.on('tool(videoType)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
            console.log(layEvent)
        if(layEvent === 'del'){ //删除
            layer.confirm('确定删除此规则？',{icon:3, title:'提示信息'},function(index){
                $.get("/video/delete",{
                    id : data.id  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                                    //刷新表格
                    tableVideoType.reload()
                    layer.close(index);
                })
            });
        }
    });


})