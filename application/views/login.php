<?php
/**
 * Created by PhpStorm.
 * User: arvin
 * Date: 2015/10/7
 * Time: 14:38
 */
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container signin-container">

    <form class="form-signin">
        <h2 class="form-signin-heading">请先登陆</h2>
        <input type="text" class="form-control" id="username" placeholder="用户名">
        <br>
        <input type="password" class="form-control" id="password" placeholder=密码>
        <br>
            <div class="input-group">
                            <span class="input-group-addon">
                                <img id="ns-captcha-img" src="<?=base_url().'auth/captcha'?>" alt="加载失败" title="点击刷新验证码">
                            </span>
                <input type="text" class="form-control" id="captcha" placeholder="验证码" maxlength="5">
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-4">
                <input type="hidden" id="ret" value="<?php echo isset($ret) ? htmlspecialchars($ret) : '/'; ?>">
                <button class="btn btn-large btn-primary" type="button" id="login"
                        title="" data-container="body"
                        data-toggle="popover" data-placement="bottom"
                        data-content="错误原因:验证码错误">
                        登陆
                </button>
            </div>
        </div>
    </form>

</div>

<script type="text/javascript">
    (function($){
        // 刷新验证码
        $(function() {
            $("#ns-captcha-img").click(function(){
                $(this).attr("src", "/auth/captcha/" + (new Date()).getTime() );
            });
        });

        // 不显示错误提示
        $(function () { $("#login").popover('destroy');});

        // ajax登陆
        $("#login").click(function() {
            $.ajax({
                "type": "POST",
                "url": "/auth/do_login",
                "data": {
                    "username": $.trim($("#username").val()),
                    "password": $.trim($("#password").val()),
                    "captcha": $.trim($("#captcha").val()),
                    "ret": $.trim($("#ret").val())
                },
                "dataType": "json",
                "success": function (data) {
                    // -12: 验证码失效, -11: 验证码过期, -13: 验证码错误
                    // -1,-2: 用户名或密码错误, -3: 用户被禁用
                    // 0: 登陆成功
                    switch (data["error_no"]) {
                        case -12:
                            $("#login").attr("title","登陆失败");
                            $("#login").attr("data-content","验证码失效");
                            $("#login").popover('show');
                            $("#ns-captcha-img").attr("src", "/auth/captcha/" + (new Date()).getTime() );
                            break;
                        case -11:
                            $("#login").attr("title","登陆失败");
                            $("#login").attr("data-content","验证码过期");
                            $("#login").popover('show');
                            $("#ns-captcha-img").attr("src", "/auth/captcha/" + (new Date()).getTime() );
                            break;
                        case -13:
                            $("#login").attr("title","登陆失败");
                            $("#login").attr("data-content","验证码错误");
                            $("#login").popover('show');
                            $("#ns-captcha-img").attr("src", "/auth/captcha/" + (new Date()).getTime() );
                            break;
                        case -1:
                        case -2:
                            $("#login").attr("title","登陆失败");
                            $("#login").attr("data-content","用户名或密码错误");
                            $("#login").popover('show');
                            $("#ns-captcha-img").attr("src", "/auth/captcha/" + (new Date()).getTime() );
                            break;
                        case -3:
                            $("#login").attr("title","登陆失败");
                            $("#login").attr("data-content","用户被禁用");
                            $("#login").popover('show');
                            $("#ns-captcha-img").attr("src", "/auth/captcha/" + (new Date()).getTime() );
                            break;
                        case 0:
                            var ret_url = decodeURI($.trim($("#ret").val()));
                            console.log(ret_url);
                            window.location.href = ret_url;
                    }
                }
            });
        });
    })(jQuery);
</script>
