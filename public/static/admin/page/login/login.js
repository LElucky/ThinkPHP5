layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer
        $ = layui.jquery;

    $(".loginBody .seraph").click(function(){
        layer.msg("这只是做个样式，至于功能，你见过哪个后台能这样登录的？还是老老实实的找管理员去注册吧",{
            time:5000
        });
    })

    //登录按钮
    form.on("submit(login)",function(data){
        $(this).text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        var that = $(this)
        setTimeout(function(){
            var email = $('#userName').val()
            var password = $('#password').val()
            var captcha = $('#code').val()
            $.ajax({
                url:'/l/login_validate',
                type:'POST',
                data:{'nickname':email,'password':password,'captcha':captcha},
                beforeSend:function(){
                    $(that).text('验证中...')
                },
                success:function(re){
                    re = JSON.parse(re)
                    layer.msg(re.mes,{time:3000})
                    if(re.sta == '0'){
                        setTimeout(function(){
                            window.location.href="/a/index"
                        },2000);
                    }else{
                        setTimeout(function(){
                            $(that).text("登录").attr("disabled",false).removeClass("layui-disabled")
                        },2000);
                    }
                },
                error:function(){
                    layer.msg('系统繁忙 稍后重试',{time:3000})
                    setTimeout(function(){
                        $(that).text("登录").attr("disabled",false).removeClass("layui-disabled")
                    },2000);                    
                }
            })
        },1000);
        return false;
    })

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    })
})
