<?php ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <?php echo form_open("#", "id='new_user_form' class='form-horizontal'") ?>
            <div class="form-group">
                <label for="ref" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-9">
                    <input type="text" id="username" name="username" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Email Address</label>
                <div class="col-sm-9">
                    <input type="text" id="email" name="email" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-9">
                    <input type="password" id="pass" name="pass" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">Confirm Password</label>
                <div class="col-sm-9">
                    <input type="password" id="c_pass" name="c_pass" class="form-control input-sm" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">User Type</label>
                <div class="col-sm-9">
                    <select class="form-control input-sm" id="u_type" name="u_type">
                        <optgroup>
                            <option value="-1">--SELECT--</option>
                        </optgroup>
                        <optgroup>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-2">
                    <input type="button" class="btn btn-success" id="save_user" value="Save"/>
                </div>
                <div class="col-sm-7 text-danger" id="errors_user">
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <table class="table table-bordered text-uppercase">
                    <thead>
                        <tr>
                            <td>Username</td>
                            <td>Email</td>
                            <td class="text-uppercase">User Type</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($users)) {
                            foreach ($users as $user) {
                                ?>
                                <tr>
                                    <td><?php echo $user->username ?></td>
                                    <td><?php echo $user->email ?></td>
                                    <td><?php echo $user->company ?></td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="reset_password(<?php echo $user->id ?>)"><i class="fa fa-recycle text-warning"></i></a>
                                        <a href="javascript:void(0)" onclick="remove_user(<?php echo $user->id ?>)"><i class="fa fa-remove text-danger"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#save_user").click(function () {
            W_L.disable_btn("save_user", "Saving");
            $.ajax({
                url: "<?php echo base_url("users/save_user") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_user_form").serialize(),
                success: function (data) {
                    W_L.enable_btn("save_user", "Save");
                    if (data.msg_type == "OK") {
                        location.reload();
                    } else {
                        $("#errors_user").html(data.msg);
                    }
                }
            });
        });
    });

    function remove_user(id) {
        W_L.Confirm("Do you want to delete this user", function () {
            $.ajax({
                url: "<?php echo base_url("users/remove_user") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        location.reload();
                    }
                }
            });
        });
    }
    function reset_password(id) {
        W_L.Confirm("Do you want to Reset the Password for this user", function () {
            $.ajax({
                url: "<?php echo base_url("users/reset_password") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        W_L.Overlay_notify("Information", data.msg, "success");
                    }
                }
            });
        });
    }
</script>
