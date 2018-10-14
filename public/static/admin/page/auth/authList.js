// 权限节点
layui.use(['form','layer','table','laytpl'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery,
        laytpl = layui.laytpl,
        table = layui.table;

    //规则列表
    var tableIns = table.render({
        elem: '#ruleList',
        url : '/auth/authRuleInfo.html',
        cellMinWidth : 95,
        page : false,
        height : "full-125",
        limits : [5,10,15,20,25],
        limit : 15,
        id : "ruleListTable",
        cols : [[
            {type: "checkbox", fixed:"left", width:50},
            {field: 'title', title: '规则描述', minWidth:100, align:"center"},
            {field: 'name', title: '认证规则', minWidth:200, align:'center',templet:function(d){
                return d.name;
            }},
            {field: 'type', title: 'TYPE', minWidth:80,align:'center'},
            {field: 'level', title: '规则等级', align:'center',templet:function(d){
                if(d.level == "1"){
                    return "<span style='color:red;'><b>一级栏目</b></span>";
                }else if(d.level == "2"){
                    return "<span style='color:#1CE348;'>二级栏目</span>";
                }else if(d.level == '0'){
                    // return "<span style='color:red;'><b>顶级栏目</b></span>";
                }
            }},
            {field: 'create_time', title: '创建时间', align:'center',templet:function(d){
                return d.create_time
                },minWidth:150,sort: true},
            {field: 'status', title: '状态',  align:'center',templet:function(d){
                return d.status == "1" ? "<span class='"+d.id+"_status'>正常使用</span>" : "<span class='"+d.id+"_status'>限制使用</span>";
            }},
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
            {field: 'is_show', title: '是否显示', align:'center',templet:function(d){
                switch (d.is_show)
                {
                    case 0:
                        var is_show = '';
                        break;
                    case 1:
                        var is_show = 'checked';
                        break;
                }
                    return '<input type="checkbox" name="is_show" data-index="'+d.id+'" lay-filter="is_show" lay-skin="switch" lay-text="Yes|No" '+is_show+'>';
                },minWidth:70},


            {field: 'sort', title: '排序', edit: 'text',sort:"true", align:'center',minWidth:20},
            {title: '操作', minWidth:95,templet:'#ruleListBar',fixed:"right",align:"center"}
        ]]
    });

    //搜索【此功能需要后台配合，所以暂时没有动态效果演示】
    $(".search_btn").on("click",function(){
        layer.load();
        setTimeout(function(){
            layer.closeAll('loading');
        }, 500);

        if($(".searchVal").val() != ''){
            table.reload("ruleListTable",{
                page: {
                    curr: 1 //重新从第 1 页开始
                },
                where: {
                    key  : {
                        name  : $(".searchVal").val(),  //搜索的关键字
                        title : $('.searchVal').val()
                    }
                }
            })



        }else{
            layer.msg("请输入搜索的内容");
        }
    });

    //添加用户
    function addUser(title,edit){
        var param = '';
        if(edit){
            var param = '?id='+edit.id;
        }
        var index = layui.layer.open({
            title : title,
            type : 2,
            content : "/auth/authRuleAdd.html"+param,
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                if(edit){
                    body.find('[name="id"]').val(edit.id)
                    body.find(".title").val(edit.title);            //规则描述
                    body.find(".name").val(edit.name);              //规则
                    body.find(".route").val(edit.route);
                    body.find(".status").val(edit.status);          //规则状态
                    body.find(".level").val(edit.level);            //规则等级
                    body.find(".type").val(edit.type);              //type
                    body.find(".sort").val(edit.sort);              //排序
                    body.find(".icon").val(edit.icon);              //icon
                    body.find(".is_show").val(edit.is_show);
                    body.find(".level input[value="+edit.level+"]").prop("checked","checked"); //默认选中 等级
                    body.find(".status input[value="+edit.status+"]").prop("checked","checked"); //默认选中 状态
                    body.find(".is_show input[value="+edit.is_show+"]").prop("checked","checked"); //默认选中 是否显示
                    body.find(".pid option[value="+edit.pid+"]").prop("selected","selected"); //默认选中 状态
                    form.render();
                }
                setTimeout(function(){
                    layui.layer.tips('点击此处返回用户列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        })
        layui.layer.full(index);
        window.sessionStorage.setItem("index",index);
        //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
        $(window).on("resize",function(){
            layui.layer.full(window.sessionStorage.getItem("index"));
        })
    }

    //时间戳转换成时间
    function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        var Y = date.getFullYear() + '-';
        var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        var D = date.getDate() + ' ';
        var h = date.getHours() + ':';
        var m = date.getMinutes() + ':';
        var s = date.getSeconds();
        return Y+M+D+h+m+s;
    }



    $(".addNews_btn").click(function(){
        addUser('添加规则');
    })

    //批量删除
    $(".delAll_btn").click(function(){
        var checkStatus = table.checkStatus('ruleListTable'),
            data = checkStatus.data,
            id = [];
        if(data.length > 0) {
            for (var i in data) {
                id.push(data[i].id);
            }
            layer.confirm('确定删除选中的用户？', {icon: 3, title: '提示信息'}, function (index) {
                $.get("/auth/delete",{
                    id : id  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                    tableIns.reload();
                    layer.close(index);
                })
            })
        }else{
            layer.msg("请选择需要删除的用户");
        }
    })

    //列表操作
    table.on('tool(ruleList)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'edit'){ //编辑
            addUser('编辑规则',data);
        }else if(layEvent === 'usable'){ //启用禁用
            var _this = $(this),
                usableText = "是否确定禁用此规则？",
                btnText = "已禁用";

            if(_this.text()=="已禁用"){
                usableText = "是否确定启用此规则？",
                btnText = "已启用";
            }
            layer.confirm(usableText,{
                icon: 3,
                title:'系统提示',
                cancel : function(index){
                    layer.close(index);
                }
            //    确定按钮
            },function(index){
                _this.text(btnText);
                layer.close(index);
                if(btnText == '已禁用'){
                    $(_this).removeClass('layui-btn-warm').addClass('layui-btn-normal')
                }else{
                    $(_this).removeClass('layui-btn-normal').addClass('layui-btn-warm')
                }
            //    取消按钮
            },function(index){
                layer.close(index);
            });
        }else if(layEvent === 'del'){ //删除
            layer.confirm('确定删除此规则？',{icon:3, title:'提示信息'},function(index){
                $.get("/auth/delete",{
                    id : data.id  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                    tableIns.reload();
                    layer.close(index);
                })
            });
        }
    });





    // 监听规则状态改变  //修改状态
    form.on('switch(status)', function(data){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        var val = data.elem.checked //开关是否开启，true或者false
        if(val == true){
            var value = '1'
        }else{
            var value = '0'
        }
        var id  = $(this).attr('data-index')
        $.post('/auth/ajaxStatus',{'id':id,'val':value,'type':'status'},function(data){
            var data = JSON.parse(data)
            setTimeout(function(){
                var val = value == '1' ? '正常使用' : '限制使用'
                $("."+id+'_status').text(val)
                top.layer.msg(data.msg)
                layer.close(index);
            },200)
        })
    });

    // 监听规则状态改变  修改是否显示
    form.on('switch(is_show)', function(data){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        var val = data.elem.checked //开关是否开启，true或者false
        if(val == true){
            var value = '1'
        }else{
            var value = '0'
        }
        var id  = $(this).attr('data-index')
        $.post('/auth/ajaxStatus',{'id':id,'val':value,'type':'is_show'},function(data){
            var data = JSON.parse(data)
            setTimeout(function(){
                top.layer.msg(data.msg)
                layer.close(index);
            },200)
        })
    });


//监听修改的排序
table.on('edit(ruleList)', function(obj){ //注：edit是固定事件名，ruleList是table原始容器的属性 lay-filter="对应的值"
  // console.log(obj.value); //得到修改后的值
  // console.log(obj.field); //当前编辑的字段名
  // console.log(obj.data); //所在行的所有相关数据  
  $.post('/auth/sortEdit',{'id':obj.data.id,'sort':obj.value},function(result){
    var res = JSON.parse(result);
    //等于0 则表示处理异常
    if(res.code == '0'){
        top.layer.msg(res.msg)
    }
  })
});



})
