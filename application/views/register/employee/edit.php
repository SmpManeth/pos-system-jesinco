<title>Edit Employee</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-9 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_emp_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name :</label>
                            <div class="col-md-2">
                                <select class="form-control input-sm" name="emp_prefix">
                                    <optgroup>
                                        <option value="">No Prefix</option>
                                    </optgroup>
                                    <optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($user_prefixes)) {
                                            foreach ($user_prefixes as $u_pref) {
                                                ?>
                                                <option <?php echo $employee->emp_prefix == $u_pref ? "selected" : "" ?> value="<?php echo $u_pref ?>"><?php echo $u_pref ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control input-sm" placeholder="Enter Name" name="emp_name" id="emp_name" value="<?php echo $employee->emp_name ?>">
                                <input type="hidden"  name="id" id="id" value="<?php echo $employee->id ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Designation :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control input-sm" placeholder="Enter Designation Here" name="emp_desig" id="emp_desig" value="<?php echo $employee->designation ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Address :</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="PO Box" name="address_po_box" id="address_po_box" value="<?php echo $employee->address_po_box ?>">
                                <input type="text" class="form-control input-sm" placeholder="Street Name" name="address_line1" id="address_line1" value="<?php echo $employee->address_line1 ?>">
                                <input type="text" class="form-control input-sm" placeholder="Address Line 2" name="address_line2" id="address_line2" value="<?php echo $employee->address_line2 ?>">
                                <input type="text" class="form-control input-sm" placeholder="City / Town" id="address_city" name="address_city" value="<?php echo $employee->address_city ?>">

                                <select class="form-control input-sm" name="counrty" id="counrty">
                                    <?php
                                    if (isset($countries)) {
                                        foreach ($countries->result_array() as $country):
                                            ?>
                                            <option <?php echo $country["country_name"] == $employee->counrty ? "selected" : "" ?> value="<?php echo $country["country_name"] ?>"><?php echo $country["country_name"] ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Telephone" name="tp1" id="tp1" value="<?php echo $employee->tp1 ?>">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Mobile " id="tp2" name="tp2" value="<?php echo $employee->tp2 ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Office Extension :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Office Ext" id="of_ext" name="of_ext" value="<?php echo $employee->office_ext ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Address :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="Personal Email" name="p_email" id="p_email" value="<?php echo $employee->p_email ?>">
                                <input type="text" class="form-control input-sm" placeholder="Office Email" name="o_email" id="o_email" value="<?php echo $employee->o_email ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">N I C Number:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="NIC" name="nic" id="nic" value="<?php echo $employee->nic ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9 text-right">
                                <div class="mt-checkbox-outline text-left">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" name="login_data" id="login_data" value="1">&nbsp;With Login Credentials
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($user->user_type == "superadmin" || $user->user_type == "admin") {
                        ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <div class="portlet box bg-info" id="login_data_div"  style="display: none">
                                    <div class="portlet-title">
                                        <div class="caption font-blue-madison">
                                            <i class="fa fa-unlock"></i>Login Data </div>
                                    </div>
                                    <div class="portlet-body tabs-below">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Username :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Username" name="username" id="username" value="<?php echo $employee->username ?>">
                                                    <small class="muted"><i>Only 15 letters allowed. and no spaces</i></small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Password :</label>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control input-sm" placeholder="Enter Password" name="pass" id="pass">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Confirm Password :</label>
                                                <div class="col-md-8">
                                                    <input type="password" class="form-control input-sm" placeholder="Re-Enter Password Here" id="c_pass" name="c_pass">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status :</label>
                        <div class="col-md-9 text-right">
                            <div class="mt-radio-inline text-left">
                                <label class="mt-radio font-green-jungle">
                                    <input name="status" value="1" <?php echo $employee->status == "1" ? "checked=''" : "" ?> type="radio"> Active
                                    <span></span>
                                </label>
                                <label class="mt-radio font-red-thunderbird">
                                    <input name="status" value="2" <?php echo $employee->status == "2" ? "checked=''" : "" ?> type="radio"> Inactive
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn btn-primary" id="add_emp_btn"><i class="fa fa-save"></i> <span>Submit</span></button>
                                <button type="reset" class="btn default">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<script src="<?php echo base_url("assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $("#login_data").change(function () {
            if ($("#login_data").is(":checked")) {
                $("#login_data_div").slideDown(500);
            } else {
                $("#login_data_div").slideUp(500);
            }
        });
//        var selector = $("#username");
//        Inputmask({regex: "/^[a-zA-Z0-9]$/"}).mask(selector);

        $('#username').inputmask('Regex', {
            regex: "[0-9a-zA-Z]*"
        });
        $("#add_emp_btn").click(function () {
            DJ.disable_btn_fa("add_emp_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/update_employee") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_emp_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("add_emp_btn", "Submit");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#new_cus_form").trigger("reset");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>