layui.use(['form','layer','laydate','table','laytpl'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer,
        $ = layui.jquery,
        laydate = layui.laydate,
        laytpl = layui.laytpl,
        table = layui.table;

    //新闻列表
    var tableIns = table.render({
        elem: '#newsList',
        url : '/videos/videoList',
        cellMinWidth : 95,
        page : true,
        height : "full-125",
        limit : 15,
        limits : [10,15,20,25],
        id : "newsListTable",
        cols : [[
            {type: "checkbox", fixed:"left", width:50},
            {field: 'id', title: 'ID', width:60, align:"center",sort:true},
            {field: 'title', title: '电影名称', width:350},
            {field: 'online_time', title: '上线时间', align:'center'},
            // {field: 'score', title: '评分',  align:'center',templet:"#newsStatus"},
            {field: 'score', title: '评分',  align:'center'},
            {field: 'status', title: '状态', align:'center',templet:function(d){
                if(d.status == '0'){
                    var status = '<span class="layui-blue">保存草稿</span>';
                }else if(d.status == '1'){
                    var status = '<span class="layui-red">等待审核</span>';
                }else if(d.status == '2'){
                    var status = '<span style="color:green;">开放浏览</span>';
                }else if(d.status == '3'){
                    var status = '<span style="color:black">私密浏览</span>';
                }
                return status;
            }},
            {field: 'top', title: '是否置顶', align:'center', templet:function(d){
                if(d.top == '1'){
                   var status = 'checked';
                }else{
                   var status = '';
                }
                return '<input type="checkbox" name="newsTop" lay-filter="newsTop" lay-skin="switch" lay-text="是|否" '+status+'>'
            }},
            {field: 'create_time', title: '添加时间-/-最近修改', align:'center', minWidth:110, templet:function(d){
                return d.create_time.substring(0,10) +'-/-'+ d.update_time.substring(0,10);
            }},
            {title: '操作', width:170, templet:'#newsListBar',fixed:"right",align:"center"}
        ]]
    });

    //是否置顶
    form.on('switch(newsTop)', function(data){
        var index = layer.msg('修改中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){

            //当前元素    
            var obj = $(data.elem);    //遍历父级tr，取第一个，然后查找第二个td，取值   
            var id = obj.parents('tr').first().find('td').eq(1).text();    
            if(data.elem.checked){
                var top = '1';    
            }else{
                var top = '0';
            }
            $.ajax({
                url:'/videos/topStatus',
                type:'POST',
                data:{'id':id,'top':top},
                success:function(res){
                    layer.msg(res)
                },
                error:function(){
                    layer.msg('系统异常!!!!')
                }
            })
            layer.close(index);
        },500);
    })

    //搜索【此功能需要后台配合，所以暂时没有动态效果演示】
    $(".search_btn").on("click",function(){

        var value = $('.searchVal').val()
        var id    = $('.searchId').val()
        var top   = $('[name="top"]').val()
        var status= $('[name="status"]').val()
        var order = $('[name="order"]').val()
        var field = $('[name="field"]').val()

        if(value != '' || id != ''|| top != '' || status != '' || order != '' || field != '' ){
            table.reload("newsListTable",{
                page: {
                    curr: 1 //重新从第 1 页开始
                },
                where: {
                    key:  {
                        title: value,  //搜索的关键字
                        id : id,
                        top: top,
                        status:status,
                        order : order,
                        field : field,
                    }
                }
            })
        }else{
            layer.msg("请输入搜索的内容");
        }
    });

    //添加文章
    function addNews(edit){
        var index = layui.layer.open({
            title : "添加文章",
            type : 2,
            content : "videoAdd.html",
            success : function(layero, index){
                var body = layui.layer.getChildFrame('body', index);
                if(edit){
                    console.log(edit)
                    body.find('[name="id"]').val(edit.id)
                    body.find(".title").val(edit.title);
                    body.find(".online_time").val(edit.online_time);
                    body.find(".score").val(edit.score);
                    body.find(".url").val(edit.url);
                    body.find(".desc").val(edit.desc);
                    var img_str = '/static/admin/video_img/'+edit.image.replace(/\\/g,"/");
                    body.find(".thumbImg").attr("src",img_str);
                    body.find(".status").val(edit.status);
                    body.find(".daoyan").val(edit.daoyan);
                    body.find(".zhuyan").val(edit.zhuyan.replace(/\s[10]/g, ""));
                    body.find(".type").val(edit.type);
                    if(edit.top == '1'){
                        body.find(".top").prop("checked","checked");
                    }else{
                        body.find(".top").prop("checked","");
                    }                    
                    form.render();
                }
                setTimeout(function(){
                    layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        })
        layui.layer.full(index);
        //改变窗口大小时，重置弹窗的宽高，防止超出可视区域（如F12调出debug的操作）
        $(window).on("resize",function(){
            layui.layer.full(index);
        })
    }
    $(".addNews_btn").click(function(){
        addNews();
    })

    //批量删除
    $(".delAll_btn").click(function(){
        var checkStatus = table.checkStatus('newsListTable'),
            data = checkStatus.data,
            newsId = [];
        if(data.length > 0) {
            for (var i in data) {
                newsId.push(data[i].id);
            }
            layer.confirm('确定删除选中的文章？', {icon: 3, title: '提示信息'}, function (index) {
                $.get("/videos/delete",{
                    id : newsId  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                    tableIns.reload();
                    layer.close(index);
                })
            })
        }else{
            layer.msg("请选择需要删除的文章");
        }
    })

    //列表操作
    table.on('tool(newsList)', function(obj){
        var layEvent = obj.event,
            data = obj.data;

        if(layEvent === 'edit'){ //编辑
            addNews(data);
        } else if(layEvent === 'del'){ //删除
            layer.confirm('确定删除此文章？',{icon:3, title:'提示信息'},function(index){
               $.get("/videos/delete",{
                    id : data.id  //将需要删除的newsId作为参数传入
                },function(data){
                    top.layer.msg(data)
                    tableIns.reload();
                    layer.close(index);
                })
            });
        } else if(layEvent === 'look'){ //预览
            //弹出即预览
                var index = layer.open({
                  type: 2,
                  content: 'http://thinkphpcms.com/VIDEO/player/'+data.id+'.html#player',
                  area: ['60%', '60%'],
                  maxmin: true
                });
        }
    });
})