var form, $,areaData;
layui.config({
    base : "/static/admin/js/"
}).extend({
    "address" : "address"
})
layui.use(['form','layer','upload','laydate',"address","jquery"],function(){
    form = layui.form;
    $ = layui.jquery;
    var layer = parent.layer === undefined ? layui.layer : top.layer,
        upload = layui.upload,
        laydate = layui.laydate,
        address = layui.address;

    // //上传头像
    // upload.render({
    //     elem: '.userFaceBtn',
    //     // url: '/static/admin/json/userface.json',
    //     // method : "get",  //此处是为了演示之用，实际使用中请将此删除，默认用post方式提交
    //     // done: function(res, index, upload){
    //     //     var num = parseInt(4*Math.random());  //生成0-4的随机数，随机显示一个头像信息
    //     //     $('#userFace').attr('src',res.data[num].src);
    //     //     window.sessionStorage.setItem('userFace',res.data[num].src);
    //     // }
    // });

    //添加验证规则
    form.verify({
        userBirthday : function(value){
            if(!/^(\d{4})[\u4e00-\u9fa5]|[-\/](\d{1}|0\d{1}|1[0-2])([\u4e00-\u9fa5]|[-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/.test(value)){
                return "出生日期格式不正确！";
            }
        }
    })

    $.ajax({
        url:'/au/adminData',
        type:'get',
        success:function(res){
            var res = JSON.parse(res)
            fillData(res)
        },
        error:function(){
            layer.msg('系统异常')
        }
    })

    function fillData(data){
        $("[name='nickname']").val(data.nickname);
        $("[name='group_user']").val(data.user_group.title);
        $(".userSex input[name=sex][value="+data.sex+"]").prop('checked','checked');
        $("[name='phone']").val(data.phone);
        $("[name='birthday']").val(data.birthday);
        $("[name='username']").val(data.username);
        $("[name='email']").val(data.email);
        $("[name='userinfo']").val(data.userinfo);
        $("[name='id']").val(data.id)
        var src = '/static/admin/auth_user_img/'+data.user_img
        $("#userFace").attr('src',src)
        form.render(); //更新全部  
    }



    //选择出生日期
    laydate.render({
        elem: '.userBirthday',
        format: 'yyyy-MM-dd',
        trigger: 'click',
        max : 0,
        mark : {"0-12-15":"生日"},
        done: function(value, date){
            if(date.month === 12 && date.date === 15){ //点击每年12月15日，弹出提示语
                layer.msg('今天是马哥的生日，也是layuicms2.0的发布日，快来送上祝福吧！');
            }
        }
    });

    //获取省信息
    // address.provinces();

    //提交个人资料
    form.on("submit(changeUser)",function(data){
        // var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});


        from = new FormData(document.getElementById("user_save"))
        console.log(from)
        $.ajax({
            url:'/au/saveInfo',
            type:'POST',
            data:from,
            processData:false,
            contentType:false,
            beforeSend:function(){
               index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.5});
            },
            success:function(result){
                layer.msg(result)
            },
            error:function(){
                layer.msg('系统故障')
            }
        })


        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })

    //修改密码
    form.on("submit(changePwd)",function(data){
        var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            layer.close(index);
            layer.msg("密码修改成功！");
            $(".pwd").val('');
        },2000);
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })


// 上传图片预览
$("[name='file']").change(function(){  
     var objUrl = getObjectURL(this.files[0]) ;//获取文件信息  
      if (objUrl) {  
        console.log(objUrl)
      $("#userFace").attr("src", objUrl); 
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