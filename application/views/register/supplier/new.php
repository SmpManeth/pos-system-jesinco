<title>New Supplier</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_sup_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Name :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="Enter Supplier Company Name" name="company_name" id="company_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Contact Persons</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Titles :</label>
                                                <div class="col-md-8">
                                                    <select class="form-control input-sm" name="contact_person_prefix1">
                                                        <optgroup>
                                                            <option value="">---</option>
                                                        </optgroup>
                                                        <optgroup>
                                                            <?php
                                                            if (isset($user_prefixes)) {
                                                                foreach ($user_prefixes as $u_pref) {
                                                                    ?>
                                                                    <option value="<?php echo $u_pref ?>"><?php echo $u_pref ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Name" name="contact_person1" id="contact_person1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Telephone :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Telephone" name="contact_person_tp1" id="contact_person_tp1">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Email :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Email" name="contact_person_email1" id="contact_person_email1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="">

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Titles :</label>
                                                <div class="col-md-8">
                                                    <select class="form-control input-sm" name="contact_person_prefix2">
                                                        <optgroup>
                                                            <option value="">---</option>
                                                        </optgroup>
                                                        <optgroup>
                                                            <?php
                                                            if (isset($user_prefixes)) {
                                                                foreach ($user_prefixes as $u_pref) {
                                                                    ?>
                                                                    <option value="<?php echo $u_pref ?>"><?php echo $u_pref ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Name" name="contact_person2" id="contact_person2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Telephone :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Telephone" name="contact_person_tp2" id="contact_person_tp2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Email :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Email" name="contact_person_email2" id="contact_person_email2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Address :</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="PO Box" name="address_po_box" id="address_po_box">
                                <input type="text" class="form-control input-sm" placeholder="Street Name" name="address_line1" id="address_line1">
                                <input type="text" class="form-control input-sm" placeholder="Address Line 2" name="address_line2" id="address_line2">
                                <input type="text" class="form-control input-sm" placeholder="City / Town" id="address_city" name="address_city">
                                <select class="form-control input-sm" name="counrty" id="counrty">
                                    <?php
                                    if (isset($countries)) {
                                        foreach ($countries->result_array() as $country):
                                            ?>
                                            <option <?php echo $country["country_name"] == "Sri Lanka" ? "selected" : "" ?> value="<?php echo $country["country_name"] ?>"><?php echo $country["country_name"] ?></option>
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
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Telephone" name="tp1" id="tp1">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Fax " id="tp2" name="tp2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tax ID :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Tax ID" name="tax_id" id="tax_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nature of Business :</label>
                            <div class="col-md-9 text-right">
                                <div class="mt-radio-inline text-left">
                                    <label class="mt-radio">
                                        <input name="nb_option" value="Manufacturer" checked="" type="radio"> Manufacturer
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input name="nb_option" value="Authorised Agent" type="radio"> Authorized Agent
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input name="nb_option" value="Consulting Company" type="radio"> Consulting Company
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input name="nb_option" value="Other" type="radio"> Other
                                        <span></span>
                                    </label>
                                </div>
                                <input type="text" class="form-control input-sm input-inline input-medium d-none" placeholder="Nature of Business" name="nb_option_other" id="nb_option">
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
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Bank Name" name="bank_name" id="bank_name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Branch Name :</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Branch Name" name="bank_branch" id="bank_branch">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Number :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Account Number" id="bank_acc_no" name="bank_acc_no">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Account Name :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Account Name" name="bank_acc_name" id="bank_acc_name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Bank Swift Code :</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control input-sm" placeholder="Enter Swift Code or B.I.C" name="bank_swift_code" id="bank_swift_code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9 text-right">
                                <div class="mt-checkbox-outline text-left">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" name="visibility" id="visibility">&nbsp;For this Branch Only
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn green" id="add_sup_btn"><i class="fa fa-save"></i> <span>Submit</span></button>
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
        $('input[type=radio][name=nb_option]').change(function () {
            if ($(this).val() == "Other") {
                $("#nb_option").slideDown(500);
            } else {
                $("#nb_option").slideUp(500);
            }
        });

        $("#add_sup_btn").click(function () {
            DJ.disable_btn_fa("add_sup_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/save_supplier") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_sup_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("add_sup_btn", "Submit");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        window.location.href = "<?php echo base_url("register/supplier"); ?>";
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>