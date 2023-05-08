<title>Edit Company </title>
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
                                <input type="text" class="form-control input-sm" placeholder="Enter Company Name" name="company_name" id="company_name" value="<?php echo $company->company_name?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Address :</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="PO Box" name="address_po_box" id="address_po_box" value="<?php echo $company->address_po_box?>">
                                <input type="text" class="form-control input-sm" placeholder="Street Name" name="address_line1" id="address_line1" value="<?php echo $company->address_line1?>">
                                <input type="text" class="form-control input-sm" placeholder="Address Line 2" name="address_line2" id="address_line2" value="<?php echo $company->address_line2?>">
                                <input type="text" class="form-control input-sm" placeholder="City / Town" id="address_city" name="address_city" value="<?php echo $company->address_city?>">
                                <select class="form-control input-sm" name="counrty" id="counrty">
                                    <?php
                                    if (isset($countries)) {
                                        foreach ($countries->result_array() as $country):
                                            ?>
                                            <option <?php echo $company->counrty && $country["country_name"] == $company->counrty ? "selected" : ($country["country_name"] == "Sri Lanka" ? "selected":"") ?> value="<?php echo $country["country_name"] ?>"><?php echo $country["country_name"] ?></option>
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
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Telephone" name="tp1" id="tp1" value="<?php echo $company->tp1?>">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Fax " id="tp2" name="tp2" value="<?php echo $company->tp2?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tax ID :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Tax ID" name="tax_id" id="tax_id" value="<?php echo $company->tax_id?>">
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
                                    <div class="portlet-body tabs-below" style="display: none;">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Bank Name" name="bank_name" id="bank_name" value="<?php echo $company->bank_name?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Branch Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Branch Name" name="bank_branch" id="bank_branch" value="<?php echo $company->bank_branch?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Number :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Account Number" id="bank_acc_no" name="bank_acc_no" value="<?php echo $company->bank_acc_no?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Name :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Account Name" name="bank_acc_name" id="bank_acc_name" value="<?php echo $company->bank_acc_name?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Swift Code :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Swift Code or B.I.C" name="bank_swift_code" id="bank_swift_code" value="<?php echo $company->bank_swift_code?>">
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
            DJ.disable_btn("update_company_btn","Saving");
            $.ajax({
                url: "<?php echo base_url("company/update_company") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#update_company_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("update_company_btn","Save Changes");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg,"success");
                    } else {
                        DJ.Notify(data.msg,"danger");
                    }
                }
            });
        });
    });
</script>