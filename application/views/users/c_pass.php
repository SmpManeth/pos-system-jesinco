<?php ?>
<title>M&D User Settings</title>
<div class="container text-center">
    <legend>User Account</legend>
    <br/>
    <br/>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-2">
            <div class="form-horizontal">
                <?php echo form_open("#", "id='c_pass_form'") ?>
                <?php echo form_hidden("is_ajax_request") ?>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Username <span class="required">*</span></label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control input-sm" name="uname" id="uname" value="<?php echo $user->username ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Current Password <span class="required">*</span></label>
                    <div class="col-lg-8">
                        <input type="password" class="form-control input-sm" name="c_pass" id="c_pass" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">New Password <span class="required">*</span></label>
                    <div class="col-lg-8">
                        <input type="password" class="form-control input-sm" name="n_pass" id="n_pass" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Confirm Password <span class="required">*</span></label>
                    <div class="col-lg-8">
                        <input type="password" class="form-control input-sm" name="con_pass" id="con_pass" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"></label>
                    <div class="col-lg-8">
                        <button type="button" class="btn btn-success" id="c_pass_btn"><span>Update</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#c_pass_btn").click(function() {
            W_L.disable_btn("c_pass_btn", "Updating");
            $.ajax({
                url: "<?php echo base_url("users/update_pass") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#c_pass_form").serialize(),
                success: function(data) {
                    W_L.enable_btn("c_pass_btn", "Update");
                    if (data.msg_type == "OK") {
                        W_L.Notify(data.msg, "success");
                        $("#c_pass").val("");
                        $("#n_pass").val("");
                        $("#con_pass").val("");
                        location.reload();
                    } else {
                        W_L.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>