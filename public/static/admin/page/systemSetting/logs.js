layui.use(['table'],function(){
	var table = layui.table;

	//系统日志
    table.render({
        elem: '#logs',
        url : '/log/logInfo',
        cellMinWidth : 95,
        page : true,
        height : "full-20",
        limit : 20,
        limits : [10,15,20,25],
        id : "systemLog",
        cols : [[
            {type: "checkbox", fixed:"left", width:50},
            {field: 'id', title: '序号', width:60, align:"center",sort: true},
            {field: 'url', title: '请求地址', width:350},
            {field: 'method', title: '操作方式', align:'center',templet:function(d){
                if(d.method.toUpperCase() == "GET"){
                    return '<span class="layui-blue">'+d.method+'</span>'
                }else{
                    return '<span class="layui-red">'+d.method+'</span>'
                }
            }},
            {field: 'type', title: '请求方式', align:'center',templet:function(d){
                if(d.type.toUpperCase() == "AJAX"){
                    return '<span class="layui-blue">'+d.type+'</span>'
                }else{
                    return '<span class="layui-red">'+d.type+'</span>'
                }
            }},
            {field: 'ip', title: '操作IP',  align:'center',minWidth:130},
            {field: 'timeConsuming', title: '耗时', align:'center',templet:function(d){
                return '<span class="layui-btn layui-btn-normal layui-btn-xs">'+d.timeConsuming+'</span>'
            }},
            {field: 'code', title: '状态码', align:'center',templet:function(d){
               
                    return '<span class="layui-btn layui-btn-green layui-btn-xs">'+d.code+'</span>'
               
            }},
            {field: 'user',title: '操作人', minWidth:100, templet:'#newsListBar',align:"center"},
            {field: 'logtime', title: '操作时间', align:'center', width:170,sort: true}
        ]]
    });
 	
})
