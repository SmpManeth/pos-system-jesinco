<?php
?>
<div class="row">
    <div class="col-lg-8">
        <?php echo form_open("#", "class='form-horizontal' id='sms_template_form'"); ?>
        <?php echo form_hidden("is_ajax_request"); ?>

        <div class="form-group">
            <label class="col-md-4 control-label">Due SMS Template:</label>
            <div class="col-md-4">
                <textarea title="Before the last {count} installments, If you pay total amount to be paid on the day in installment at once
You can get a {discount}% discount. Conditional." rows="5" class="form-control input-sm" name="due_sms_template" id="due_sms_template"><?php echo isset($options['due_sms_template']) ? $options['due_sms_template'] : "" ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Discount Percentage :</label>
            <div class="col-md-4">
                <input type="text" class="form-control input-sm number-0" name="due_sms_discount_percentage" id="due_sms_discount_percentage"
                       value="<?php echo isset($options['due_sms_discount_percentage']) ? $options['due_sms_discount_percentage'] : "" ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Due Installment Count :</label>
            <div class="col-md-4">
                <input type="text" class="form-control input-sm number-0" name="due_sms_installment_count" id="due_sms_installment_count"
                       value="<?php echo isset($options['due_sms_installment_count']) ? $options['due_sms_installment_count'] : "" ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Enable SMS Send :</label>
            <div class="col-md-4">
                <?php
                $enabled = 'no';
                if (isset($options['due_sms_enabled']) && isset($options['due_sms_enabled']) == 'yes') {
                    $enabled = 'yes';
                }
                ?>
                <select class="form-control input-sm" name="due_sms_enabled">
                    <option <?php echo $enabled == 'no' ? 'selected' : '' ?> value="no">No</option>
                    <option <?php echo $enabled == 'yes' ? 'selected' : '' ?> value="yes">Yes</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button type="button" class="btn btn-success btn-sm" id="save_sms_templates"><i class="fa fa-save"></i>
                    <span>Save Settings</span></button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#save_sms_templates").click(function (e) {
            var btn = this;
            DJ.disable_ele_fa(btn, "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/settings/save-options") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#sms_template_form").serialize(),
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

</script>
