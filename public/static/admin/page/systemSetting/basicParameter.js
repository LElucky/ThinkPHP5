layui.use(['form','layer','jquery'],function(){
	var form = layui.form,
		layer = parent.layer === undefined ? layui.layer : top.layer,
		laypage = layui.laypage,
		$ = layui.jquery;

 	var systemParameter;
 	form.on("submit(systemParameter)",function(data){
 		systemParameter = '{"company":"'+$(".company").val()+'",';     //网站名称
 		systemParameter += '"version":"'+$(".version").val()+'",';	   //当前版本
 		systemParameter += '"author":"'+$(".author").val()+'",';       //开发作者
 		systemParameter += '"www":"'+$(".www").val()+'",';             //网站域名
 		systemParameter += '"system":"'+$(".system").val()+'",';       //服务器环境
 		systemParameter += '"dataBase":"'+$(".dataBase").val()+'",';   //数据库版本
 		systemParameter += '"webtitle":"'+$(".webtitle").val()+'",';   //站点title
 		systemParameter += '"webkeys":"'+$(".webkeys").val()+'",';     //站点keys
 		systemParameter += '"webdesc":"'+$(".webdesc").val()+'",';     //站点desc
 		systemParameter += '"powerby":"'+$(".powerby").val()+'",';     //版权信息
 		systemParameter += '"record":"'+$(".record").val()+'"}';       //网站备案号
 		// window.sessionStorage.setItem("systemParameter",systemParameter);
 		//弹出loading
 		var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.5});
        setTimeout(function(){
        	$.ajax({
        		url:'/s/editInfo',
        		type:'get',
        		data:{'data':systemParameter},
        		success:function(res){
        			console.log(res)
        		},
        		error:function(){
        			layer.msg('系统故障')
        		}
        	});
            layer.close(index);
			layer.msg("系统基本参数修改成功！");
        },500);
 		return false;
 	})


 	//加载默认数据
 		$.ajax({
			url : "/s/indexInfo",
			type : "get",
			dataType : "json",
			success : function(data){
				fillData(data);
			}
		})


 	//填充数据方法
 	function fillData(data){
 		$(".company").val(data.company);         //网站名称
		$(".version").val(data.version);         //当前版本号
		$(".author").val(data.author);           //作者
		$(".www").val(data.www);       			 //域名
		$(".dataBase").val(data.dataBase);       //数据库版本
		$(".system").val(data.system);           //服务器环境
		$(".webtitle").val(data.webtitle);       //网站title
		$(".webkeys").val(data.webkeys);         //网站keys
		$(".webdesc").val(data.webdesc);         //网站desc
		$(".powerby").val(data.powerby);         //版权信息
		$(".record").val(data.record);           //网站备案号
 	}
 	
})
