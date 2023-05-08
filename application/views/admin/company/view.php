<title>Company Information</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-9 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='update_company_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Name :</label>
                            <div class="col-md-9">
                                <p class="form-control input-sm-static"><?php echo $company->company_name ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Address :</label>
                            <div class="col-md-5">
                                <?php
                                if ($company->address_po_box) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->address_po_box ?></p>,<br/>
                                    <?php
                                }
                                if ($company->address_line1) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->address_line1 ?></p>,<br/>
                                    <?php
                                }
                                if ($company->address_line2) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->address_line2 ?></p>,<br/>
                                    <?php
                                }
                                if ($company->address_city) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->address_city ?></p>,<br/>
                                    <?php
                                }
                                if ($company->counrty) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->counrty ?></p><br/>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone :</label>
                            <div class="col-md-9">
                                <?php 
                                if ($company->counrty) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->tp1 ?></p><br/>
                                    <?php
                                }
                                if ($company->counrty) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $company->tp2 ?></p><br/>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tax ID :</label>
                            <div class="col-md-9">
                                <p class="form-control input-sm-static"><?php echo $company->tax_id ?></p><br/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <div class="portlet box grey-salt">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-bank"></i>Bank Details</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                        </div>
                                    </div>
                                    <div class="portlet-body tabs-below">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Name :</label>
                                                <div class="col-md-8">
                                                    <p class="form-control input-sm-static"><?php echo $company->bank_name ?></p><br/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Branch Name :</label>
                                                <div class="col-md-8">
                                                    <p class="form-control input-sm-static"><?php echo $company->bank_branch ?></p><br/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Number :</label>
                                                <div class="col-md-6">
                                                    <p class="form-control input-sm-static"><?php echo $company->bank_acc_no ?></p><br/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Name :</label>
                                                <div class="col-md-6">
                                                    <p class="form-control input-sm-static"><?php echo $company->bank_acc_name ?></p><br/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Swift Code :</label>
                                                <div class="col-md-6">
                                                    <p class="form-control input-sm-static"><?php echo $company->bank_swift_code ?></p><br/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn green" id="update_company_btn"><span>Save Changes</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
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
<script>
    $(document).ready(function () {

        $("#update_company_btn").click(function () {
            DJ.disable_btn("update_company_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("company/update_company") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#update_company_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("update_company_btn", "Save Changes");
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