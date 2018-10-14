layui.use(['form','layer','table','laytpl'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery,
        laytpl = layui.laytpl,
        table = layui.table;

    //用户列表
    var tableIns = table.render({
        elem: '#userList',
        url : '/u/indexinfo',
        cellMinWidth : 95,
        page : true,
        height : "full-125",
        limits : [5,10,15,20,25],
        limit : 5,
        id : "userListTable",
        cols : [[
            {type: "checkbox", fixed:"left", width:50},
            {field: 'nickname', title: '用户名', minWidth:100, align:"center"},
            {field: 'email', title: '用户邮箱', minWidth:200, align:'center',templet:function(d){
                return '<a class="layui-blue" href="mailto:'+d.email+'">'+d.email+'</a>';
            }},
            {field: 'sex', title: '用户性别', align:'center',templet:function(d){
                    switch(d.sex)
                    {
                        case 0:
                            d.sex = '女';
                            break;
                        case 1:
                            d.sex = '男';
                            break;
                        case 2:
                            d.sex = '保密';
                            break;
                        default:
                            d.sex = '未知';
                            break;
                    }

                    return d.sex;
            }},
            {field: 'status', title: '用户状态',  align:'center',templet:function(d){
                return d.status == "1" ? "<span class='"+d.id+"_status'>正常使用</span>" : "<span class='"+d.id+"_status'>限制使用</span>";
            }},
            {field: 'status', title: '用户等级', align:'center',templet:function(d){
                if(d.status == "0"){
                    return "注册会员";
                }else if(d.status == "1"){
                    return "中级会员";
                }else if(d.status == "2"){
                    return "高级会员";
                }else if(d.status == "3"){
                    return "钻石会员";
                }else if(d.status == "4"){
                    return "超级会员";
                }
            }},
            {field: 'last_login', title: '最后登录时间', align:'center',templet:function(d){
                return timestampToTime(d.last_login)
                },minWidth:150,sort: true},
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
            {title: '操作', minWidth:95,templet:'#userListBar',fixed:"right",align:"center"}
        ]]
    });

    //搜索【此功能需要后台配合，所以暂时没有动态效果演示】
    $(".search_btn").on("click",function(){
        layer.load();
        setTimeout(function(){
            layer.closeAll('loading');
        }, 500);

        if($(".searchVal").val() != ''){
            table.reload("userListTable",{
                page: {
                    curr: 1 //重新从第 1 页开始
                },
                where: {
                    key  : {
                        nickname: $(".searchVal").val(),  //搜索的关键字
                        email : $('.searchVal').val()
                    }
                }
            })



        }else{
            layer.msg("请输入搜索的内容");
        }
    });

    //添加用户
    function addUser(title,edit){
        var index = layui.layer.open({
            title : title,
            type : 2,
            content : "/u/adduser.html",
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                if(edit){
                    switch(edit.sex){
                        case '女':
                            edit.sex = '0';
                            break;
                        case '男':
                            edit.sex = '1';
                            break;
                        case '保密':
                            edit.sex = '2';
                            break;
                    }
                    // body.find('.userPassword').attr('placeholder','为空则表示密码不变').attr('lay-verify','');
                    if(edit.user_img != null){
                        body.find('#user_img').attr('value','更换头像')
                        body.find('#demo1').attr('src','/static/admin/user_img/'+edit.user_img).css('display','block')
                    }
                    // 修改的话 赋值id 表明修改操作
                    body.find('[name="id"]').val(edit.id)
                    body.find(".userName").val(edit.nickname);  //登录名
                    body.find(".userEmail").val(edit.email);  //邮箱
                    body.find("input[name=sex][value="+edit.sex+"]").prop("checked","checked");  //性别
                    body.find(".userGrade").val(edit.status);  //会员等级
                    body.find(".userStatus").val(edit.status);    //用户状态
                    body.find(".userDesc").text(edit.userinfo);    //用户简介
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
        addUser('添加用户');
    })

    //批量删除
    $(".delAll_btn").click(function(){
        var checkStatus = table.checkStatus('userListTable'),
            data = checkStatus.data,
            id = [];
        if(data.length > 0) {
            for (var i in data) {
                id.push(data[i].id);
            }
            layer.confirm('确定删除选中的用户？', {icon: 3, title: '提示信息'}, function (index) {
                var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
                $.get("/u/delete",{
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
    table.on('tool(userList)', function(obj){
        var layEvent = obj.event,
            data = obj.data;
        if(layEvent === 'edit'){ //编辑
            addUser('编辑用户',data);
        }else if(layEvent === 'usable'){ //启用禁用
            var _this = $(this),
                usableText = "是否确定禁用此用户？",
                btnText = "已禁用";

            if(_this.text()=="已禁用"){
                usableText = "是否确定启用此用户？",
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
            layer.confirm('确定删除此用户？',{icon:3, title:'提示信息'},function(index){
                $.get("/u/delete",{
                    id : data.id  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                    tableIns.reload();
                    layer.close(index);
                })
            });
        }
    });




    // 监听用户状态 改变
    form.on('switch(status)', function(data){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        var val = data.elem.checked //开关是否开启，true或者false
        if(val == true){
            var value = '1'
        }else{
            var value = '0'
        }
        var id  = $(this).attr('data-index')
        $.post('/u/ajaxStatus',{'id':id,'val':value},function(data){
            var data = JSON.parse(data)
            setTimeout(function(){
                var val = value == '1' ? '正常使用' : '限制使用'
                $("."+id+'_status').text(val)
                top.layer.msg(data.msg)
                layer.close(index);
            },500)
        })
    });

})
