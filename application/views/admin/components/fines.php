<?php ?>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <h2>Fines Conditions</h2>
        <div class="col-lg-8 col-lg-offset-2">
            <?php echo form_open("#", "class='form-horizontal' id='fines_form'"); ?>
            <?php echo form_hidden("is_ajax_request"); ?>

            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label">Fine Days :</label>
                    <input type="text" class="form-control input-sm number-0" name="day" id="day" value="" />
                </div>
                <div class="col-md-4">
                    <label class="control-label">Fine Amount :</label>
                    <input type="text" class="form-control input-sm number-0" name="fine_amount" id="fine_amount" value="" />
                </div>
                <div class="col-md-2">
                    <label class="control-label"><br/></label>
                    <button type="button" class="btn btn-success btn-sm" id="save_fines"><i class="fa fa-check"></i> <span>Save</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="row">
            <div class="col-lg-12" id="fines_div">
                <?php $this->load->view("admin/components/fine_conditions", array("installments" => $installments)); ?>
            </div>
        </div>
    </div>
</div>
<div id="edit_form_div" class="hidden">
    <div>
        <?php echo form_open("#", "class='form-horizontal' id='fines_form_edit'"); ?>
        <?php echo form_hidden("is_ajax_request"); ?>

        <div class="form-group">
            <div class="col-md-4">
                <label class="control-label">Fine Days :</label>
                <input type="hidden"  name="id" id="id_edit" value="" />
                <input type="text" class="form-control input-sm number-0" name="day" id="day_edit" value="" />
                <p style="display: block" class="form-control-static" id="edit_above">Above the last</p>
            </div>
            <div class="col-md-4">
                <label class="control-label">Fine Amount :</label>
                <input type="text" class="form-control input-sm number-0" name="fine_amount" id="fine_amount_edit" value="" />
            </div>
            <div class="col-md-2">
                <label class="control-label"><br/></label>
                <button type="button" class="btn btn-success btn-sm" id="save_fines_edit"><i class="fa fa-check"></i> <span>Save</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#save_fines").click(function (e) {
            DJ.disable_btn_fa("save_fines", "Saving");
            $.ajax({
                url: "<?php echo site_url("admin/settings/save_fines") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#fines_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("save_fines", "Save");
                    if (data.msg_type == "OK") {
                        load_fines();
                        DJ.Notify(data.msg, "success");
                        $("#fines_form").trigger("reset");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#save_fines_edit").click(function (e) {
            DJ.disable_btn_fa("save_fines_edit", "Saving");
            $.ajax({
                url: "<?php echo site_url("admin/settings/update_fines") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#fines_form_edit").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("save_fines_edit", "Save");
                    if (data.msg_type == "OK") {
                        DJ.close_model();
                        load_fines();
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });

    function load_fines() {
        $("#fines_div").html("Loading <span class='fa fa-spinner fa-spin'></span>")
        $("#fines_div").load("<?php echo base_url("admin/settings/load_fines"); ?>")
    }
</script>
