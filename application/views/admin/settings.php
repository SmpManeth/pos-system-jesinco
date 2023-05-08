<?php
//Jun 26, 2018 2:39:05 PM 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<title>Settings</title>
<br/>
<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#menu1">General</a></li>
            <li><a data-toggle="tab" href="#home">Document Prefixes</a></li>
            <li><a data-toggle="tab" href="#categories">Categories</a></li>
            <li><a data-toggle="tab" href="#installments">Installments</a></li>
            <li><a data-toggle="tab" href="#fines">Fines</a></li>
            <li><a data-toggle="tab" href="#devisions">Devisions</a></li>
            <li><a data-toggle="tab" href="#inv_discounts">Invoice Discounts</a></li>
            <li><a data-toggle="tab" href="#sms_templates">SMS Templates</a></li>
        </ul>
        <div class="tab-content">
            <div id="menu1" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-lg-8">
                        <?php echo form_open("#", "class='form-horizontal' id='settings_form'"); ?>
                        <?php echo form_hidden("is_ajax_request"); ?>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Service Charge :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number-2" name="service-charge" id="service-charge" value="<?php echo isset($options['service-charge']) ? $options['service-charge'] : "" ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Fine Buffer Days :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number-0" name="fine-buffer-days" id="fine-buffer-days" value="<?php echo isset($options['fine-buffer-days']) ? $options['fine-buffer-days'] : "" ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Warranty Days :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number-0" name="warrenty-days" id="warrenty-days" value="<?php echo isset($options['warrenty-days']) ? $options['warrenty-days'] : "" ?>" />
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-md-offset-4 col-md-4">
                                <button type="button" class="btn btn-success btn-sm" id="save_settings"><i class="fa fa-save"></i> <span>Save Settings</span></button>
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <div id="home" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <?php echo form_open("#", "class='form-horizontal' id='prefixes_form'"); ?>
                        <?php echo form_hidden("is_ajax_request"); ?>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Purchase Order :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="po" id="po" value="<?php echo isset($prefixes) && !empty($prefixes['po']->prefix) ? $prefixes['po']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Goods Receive Note :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="grn" id="grn" value="<?php echo isset($prefixes) && !empty($prefixes['grn']->prefix) ? $prefixes['grn']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Delivery Note :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="do" id="do" value="<?php echo isset($prefixes) && !empty($prefixes['do']->prefix) ? $prefixes['do']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Invoice :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="invoice" id="invoice" value="<?php echo isset($prefixes) && !empty($prefixes['invoice']->prefix) ? $prefixes['invoice']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Supplier Returns :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="supreturn" id="supreturn" value="<?php echo isset($prefixes) && !empty($prefixes['supreturn']->prefix) ? $prefixes['supreturn']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Goods Issue :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="gi" id="gi" value="<?php echo isset($prefixes) && !empty($prefixes['gi']->prefix) ? $prefixes['gi']->prefix : '' ?>" />
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-md-offset-4 col-md-4">
                                <button type="button" class="btn btn-success btn-sm" id="save_prefixes"><i class="fa fa-check"></i> <span>Save</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <div id="categories" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <ul class="list-group">
                            <?php
                            foreach ($categories as $category) {
                                ?>
                                <li class="list-group-item"><?php echo $category->cat_name ?> <a class="badge label label-success" href="javascript:void(0)" onclick="edit_category(<?php echo $category->id ?>, '<?php echo $category->cat_name ?>', '<?php echo $category->visibility ?>')"><i class="fa fa-edit"></i></a><span class=" badge <?php echo $category->visibility == "0" ? "label label-info" : "label label-warning" ?>"><?php echo $category->visibility == "0" ? "Global" : "Private" ?></span> </li>
                                <?php
                            }
                            ?>
                        </ul> 
                    </div>
                </div>
            </div>
            <div id="installments" class="tab-pane fade">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <h2>Installments</h2>
                        <div class="col-lg-8 col-lg-offset-2">
                            <?php echo form_open("#", "class='form-horizontal' id='installment_form'"); ?>
                            <?php echo form_hidden("is_ajax_request"); ?>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Install Months :</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm number-0" name="installment" id="installment" value="" />
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-success btn-sm" id="save_installment"><i class="fa fa-check"></i> <span>Save</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" id="installments_div">
                                <?php $this->load->view("admin/components/installments", array("installments" => $installments)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="fines" class="tab-pane fade">
                <?php $this->load->view("admin/components/fines");?>
            </div>
            <div id="devisions" class="tab-pane fade">
                <?php $this->load->view("admin/components/devisions");?>
            </div>
            <div id="inv_discounts" class="tab-pane fade">
                <?php $this->load->view("admin/components/invoice_discounts");?>
            </div>
            <div id="sms_templates" class="tab-pane fade">
                <?php $this->load->view("admin/components/sms_templates");?>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Category</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open("#", "class='form-horizontal' id='category_edit_form'"); ?>
                <?php echo form_hidden("is_ajax_request"); ?>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="email">Category Name:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="cat_name" name="cat_name">
                        <input type="hidden" class="form-control" id="cat_id" name="cat_id">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <div class="checkbox">
                            <label><input type="checkbox" id="visibility" name="visibility"> This Branch Only</label>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cat_save_btn">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        $(".number-0").number(true, 0);
        $("#save_prefixes").click(function () {
            DJ.disable_btn("save_prefixes", "Saving.")
            $.ajax({
                url: "<?php echo base_url("admin/settings/save-prefixes") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#prefixes_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("save_prefixes", "Save");
                    if (data.msg_type == "OK") {

                    } else {
                    }
                }
            });
        });
        $("#cat_save_btn").click(function () {
            DJ.disable_btn_fa("cat_save_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/update_categories") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#category_edit_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("cat_save_btn", "Save");
                    if (data.msg_type == "OK") {
                        window.location.reload();
                    } else {
                    }
                }
            });
        });

        $("#save_installment").click(function (e) {
            DJ.disable_btn_fa("save_installment", "Saving");
            $.ajax({
                url: "<?php echo site_url("admin/settings/save-installment") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#installment_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("save_installment", "Save");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#installments_div").html("Loading <span class='fa fa-spinner fa-spin'></span>")
                        $("#installments_div").load("<?php echo base_url("admin/settings/load_installments"); ?>");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#save_settings").click(function (e) {
            var btn = this;
            DJ.disable_ele_fa(btn, "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/settings/save-options") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#settings_form").serialize(),
                success: function (data) {
                    DJ.enable_ele_fa(btn, "Save Settings");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });

    function edit_category(id, name, visibility) {
        $("#cat_name").val(name);
        $("#cat_id").val(id);
        $("#visibility").attr({checked: visibility == 0 ? true : false});
        $("#myModal").modal();
    }
    function edit_month(id, val) {
        DJ.Overlay_input({
            title: "Update nstallment month",
            value: val,
            placeholder: "",
            type: "number-0",
            greater_than: "no",
            button: {
                yes: {txt: "Update"},
                no: {txt: "CANCEL"}
            },
            click: function (v) {
                $.ajax({
                    url: "<?php echo site_url("admin/settings/update-installment") ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id, installment: v},
                    success: function (data) {
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                            $("#installments_div").html("Loading <span class='fa fa-spinner fa-spin'></span>")
                            $("#installments_div").load("<?php echo base_url("admin/settings/load_installments"); ?>");
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            }
        });
    }
    function edit_month_status(ele) {
        var id = $(ele).data("id");
        var status = $(ele).is(":checked") ? "1" : "0";

        $(ele).parent().find("span").addClass("fa fa-spinner fa-spin");
        $.ajax({
            url: "<?php echo site_url("admin/settings/update-staus-installment") ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id, status: status},
            success: function (data) {
                $(ele).parent().find("span").removeClass("fa fa-spinner fa-spin");
                if (data.msg_type == "OK") {
                    SLFIND.Notify(data.msg, "success");
                } else {
                    SLFIND.Notify(data.msg, "danger");
                }
            }
        });
    }
</script>