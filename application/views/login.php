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
        <input type="text" class="form-control" placeholder="用户名">
        <br>
        <input type="password" class="form-control" placeholder=密码>
        <br>
            <div class="input-group">
                            <span class="input-group-addon">
                                <img id="ns-captcha-img" src="<?=base_url().'auth/captcha'?>" alt="加载失败" title="点击刷新验证码">
                            </span>
                <input type="text" class="form-control" name="captcha" placeholder="验证码" maxlength="5">
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-md-offset-4">
                <input type="hidden" name="ret" value="<?php echo isset($ret) ? htmlspecialchars($ret) : '/'; ?>">
                <button class="btn btn-large btn-primary log-tips" type="button"
                        title="登陆失败" data-container="body"
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
        $(function() {
            $("#ns-captcha-img").click(function(){
                $(this).attr("src", "/auth/captcha/" + (new Date()).getTime() );
            });
        });
        $(function () { $('.log-tips').popover('show');});
    })(jQuery);
</script>
