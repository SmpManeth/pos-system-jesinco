<title>Login - The iDea Hub Inventory</title>
<div class="logo">
</div>
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php echo form_open("users/login", "id='login_form' class='login-form'"); ?>
    <?php echo form_hidden("is_ajax_request") ?>
    <h3 class="form-title">Login to your account</h3>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <div class="input-icon">
            <i class="fa fa-user"></i>
            <input class="form-control input-sm placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" id="login_form_name" name="login_form_name" /> </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <div class="input-icon">
            <i class="fa fa-lock"></i>
            <input id="login_form_password" class="form-control input-sm placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="login_form_password" /> </div>
    </div>
    <div class="form-actions">
        <label class="rememberme mt-checkbox mt-checkbox-outline">
            <input type="checkbox"  name="remember" value="1" /> Remember me
            <span></span>
        </label>
        <button type="button" class="btn btn-primary btn-sm pull-right" id="signin">
            <i class="fa fa-sign-in"></i> <span>Sign In</span>
        </button>
    </div>
    <div class="forget-password">
        <h4>Forgot your password ?</h4>
        <p> no worries, click
            <a href="javascript:;" id="forget-password"> here </a> to reset your password. </p>
    </div>
    <?php echo form_close() ?>
    <!-- END LOGIN FORM -->
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <?php echo form_open("users/forgot_form", "id='forgot_form' class='forget-form'"); ?>
    <?php echo form_hidden("is_ajax_request") ?>
    <h3>Forget Password ?</h3>
    <p> Please Contact your System Administrator Enter to reset your password. </p>
    <div class="form-actions">
        <button type="button" id="back-btn" class="btn red btn-outline">Back </button>
    </div>
    <?php echo form_close() ?>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<script>
    $(document).ready(function () {
        $("#signin").click(function () {
            DJ.disable_btn_fa("signin", "Signin in");
            $.ajax({
                url: "<?php echo base_url("users/login") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#login_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("signin", "Sign In");
                    if (data.msg_type == "OK") {
                        DJ.disable_btn("signin", "Redirecting");
                        DJ.disable_btn("login_form_btn_login2", "Redirecting");
                        window.location = data.url;
                    } else {
                        DJ.Notify(data.msg, "danger", true);
                    }
                }
            });
        });
        $("#login_form_password").keypress(function (e) {
            if (e.keyCode === 13) {
                $("#signin").trigger("click");
            }
        });
        $("#login_form_name").keypress(function (e) {
            if (e.keyCode === 13) {
                $("#login_form_password").focus();
                $("#login_form_password").select();
            }
        });
        $('#forget-password').click(function () {
            $('.login-form').hide();
            $('.forget-form').show();
        });
        $('#back-btn').click(function () {
            $('.login-form').show();
            $('.forget-form').hide();
        });
    });
</script>