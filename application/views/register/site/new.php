<title>New Site</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css")?>" rel="stylesheet" type="text/css"/>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-9 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name :</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control input-sm" placeholder="Enter Name" name="site_name" id="site_name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Address :</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="PO Box" name="address_po_box" id="address_po_box">
                                <input type="text" class="form-control input-sm" placeholder="Street Name" name="address_line1" id="address_line1">
                                <input type="text" class="form-control input-sm" placeholder="Address Line 2" name="address_line2" id="address_line2">
                                <input type="text" class="form-control input-sm" placeholder="City / Town" id="address_city" name="address_city">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Telephone" name="tp1" id="tp1">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Fax " id="tp2" name="tp2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Address :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-medium" placeholder="Email" name="email" id="email">
                                <input type="hidden" value="1" name="visibility" id="visibility">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Supervisor :</label>
                            <div class="col-md-7">
                                <style>
                                    .bootstrap-select .select-picker{
                                        width: 100%;
                                    }
                                </style>
                                <select class="form-control input-sm select-picker" name="supervisor" id="supervisor">
                                    <optgroup>
                                        <option value="-1">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($employees)) {
                                            foreach ($employees as $employee) {
                                                ?>
                                                <option data-subtext="<?php echo $employee->designation ?>" value="<?php echo $employee->id ?>"><?php echo $employee->emp_prefix . " " . $employee->emp_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn green" id="add_site_btn"><span>Submit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                <button type="button" class="btn green" id="add_site_btn2222"><span>Suzfdfdfbmit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
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
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js")?>"></script>
<script>
    $(document).ready(function () {
        $(".select-picker").selectpicker({showSubtext:true, style: "btn"});
        $("#add_site_btn").click(function () {
            DJ.disable_btn("add_site_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/save_site") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_site_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("add_site_btn", "Submit");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#new_site_form").trigger("reset");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        
        $("#add_site_btn2222").click(function(){
            $.ajax({
                url: "<?php echo base_url("register_c/save_site2222") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {},
                success: function (data) {
                    if (data.msg_type == "OK") {

                    } else {
                    }
                }
            });
        });
    });
</script>