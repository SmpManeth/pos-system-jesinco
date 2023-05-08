<?php
//Sep 19, 2018 9:18:15 AM 
?>
<title>Edit User</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/jquery-multi-select/css/multi-select.css") ?>" />
<style>
    .ms-container .ms-list{height: 350px;}
    .ms-container{width: 100%;}
    .ms-container .ms-optgroup-label{background-color: aliceblue;}
</style>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#menu1">Account Settings</a></li>
                <li><a data-toggle="tab" href="#access-manager">Access Manager</a></li>
                <li><a data-toggle="tab" href="#change-password">Change password</a></li>
            </ul>
            <div class="tab-content">
                <div id="menu1" class="tab-pane fade in active">
                    <div class="portlet-body form">
                        <?php echo form_open("#", "class='form-horizontal' id='edit_user_form'"); ?>
                        <?php echo form_hidden("is_ajax_request"); ?>
                        <div class="form-body">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <input type="hidden" value="<?php echo $user->id ?>" name="id" id="user_id" />
                                    <label class="control-label">User Account Type :</label>
                                    <select class="form-control input-sm select-picker" name="acc_type" id="acc_type">
                                        <optgroup>
                                            <option value="">--SELECT--</option>
                                        </optgroup>
                                        <optgroup>
                                            <?php
                                            if (isset($levels)) {
                                                foreach ($levels as $ul) {
                                                    ?>
                                                    <option <?php echo $user->user_type == $ul->user_type ? "selected" : "" ?> value="<?php echo $ul->user_type ?>"><?php echo $ul->display_val ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                    <label class="control-label">User Account Type :</label>
                                    <select class="form-control input-sm select-picker" name="active" id="active">
                                        <optgroup>
                                            <option value="1">Active</option>
                                            <option value="0">In-active</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">First Name :</label>
                                    <input type="text" class="form-control input-sm" name="first_name" value="<?php echo $user->first_name ?>" />
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Username :</label>
                                    <input type="text" class="form-control input-sm" name="username" value="<?php echo $user->username ?>"/>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">User Allowed Branches :</label>
                                    <select class="form-control input-sm select-picker" name="branches[]" id="branches" multiple>
                                        <optgroup>
                                            <?php
                                            if (isset($branches)) {
                                                $ids = json_decode(isset($user->branches)?$user->branches: json_encode(array()));
                                                dump($ids);     
                                                foreach ($branches as $branch) {
                                                    ?>
                                                    <option <?php echo count($ids) > 0 && in_array($branch->id, $ids) ? "selected" : "" ?> value="<?php echo $branch->id ?>"><?php echo $branch->branch_name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">&nbsp;</label><br/>
                                    <button type="button" class="btn green btn-sm" id="save_btn"><i class="fa fa-save"></i> <span>Save Settings</span></button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <div id="change-password" class="tab-pane ">
                    <div class="portlet-body form">
                        <div class="portlet-body form">
                            <?php echo form_open("#", "class='form-horizontal' id='edit_pass_form'"); ?>
                            <?php echo form_hidden("is_ajax_request"); ?>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?php echo $user->id ?>" name="id" />
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label"> Confirm Password</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" name="c_pass" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4">
                                    <button type="button" class="btn green" id="change_pass"><i class="fa fa-save"></i> <span>Change</span></button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <div id="access-manager" class="tab-pane ">
                    <div class="portlet-body form">
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <button type="button" class="btn btn-primary btn-sm" id="add_all"><span>Add All</span></button>
                                <button type="button" class="btn btn-danger  btn-sm" id="remove_all"><span>Remove All</span></button>
                                <br/><br/>
                            </div>
                            <div class="col-lg-8 col-lg-offset-2 text-center">
                                <select multiple="multiple" id="my-select" name="my-select[]" class="ms">
                                    <?php
                                    if (isset($directories)) {
                                        foreach ($directories as $directory) {
                                            $sub_menus = $directory["subs"];

                                            if (isset($sub_menus)) {
                                                foreach ($sub_menus as $sub_menu) {
                                                    $sub_name = $sub_menu["name"];
                                                    $menus = $sub_menu["menus"];
                                                    if (!empty($sub_name)) {
                                                        ?>
                                                        <optgroup label="<?php echo $sub_name ?>">
                                                            <?php
                                                            foreach ($menus as $menu) {
                                                                ?>
                                                                <option value='<?php echo $menu->menu_id ?>'><?php echo $menu->menu_name ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-12 text-center">
                                <br/>
                                <button type="button" class="btn btn-success btn-sm" id="save_config"><i class="fa fa-save"></i> <span>Save Settings</span></button>
                                <br/><br/>
                                <!--<button type="button" class="btn btn-default btn-sm" id="reset"><span>Reset</span></button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url("assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js") ?>"></script>
<script>
    $(document).ready(function () {
        var user_linfs = <?php
                                    if (isset($user_directories)) {
                                        echo $user_directories;
                                    }
                                    ?>;
        $('#my-select').multiSelect({selectableOptgroup: true, keepOrder: true});
        if (user_linfs) {
            $('#my-select').multiSelect('select', user_linfs);
        }

        $("#add_all").click(function () {
            $('#my-select').multiSelect('select_all');
        });
        $("#remove_all").click(function () {
            $('#my-select').multiSelect('deselect_all');
        });

        $("#save_config").click(function () {
            DJ.disable_btn_fa("save_config", "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/user-manager/save_interfaces") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {data: $('#my-select').val(), user: $("#user_id").val()},
                success: function (data) {
                    DJ.enable_btn_fa("save_config", "Save Settings");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#save_btn").click(function () {
            DJ.disable_btn_fa("save_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/user-manager/save-user-meta") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#edit_user_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("save_btn", "Save Settings");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#change_pass").click(function () {
            DJ.disable_btn_fa("change_pass", "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/user-manager/change_pass") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#edit_pass_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("change_pass", "Change");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

    });
</script>