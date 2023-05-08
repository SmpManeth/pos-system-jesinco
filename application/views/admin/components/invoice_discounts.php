<?php
?>

<div class="row">
    <div class="col-lg-8">
        <?php echo form_open("#", "class='form-horizontal' id='discount_form'"); ?>
        <?php echo form_hidden("is_ajax_request"); ?>

        <div class="form-group">
            <label class="col-md-4 control-label">Within 10 Days :</label>
            <div class="col-md-4">
                <input type="text" class="form-control input-sm number-2" name="inv-discount-10" id="inv-discount-10"
                       value="<?php echo isset($options['inv-discount-10']) ? $options['inv-discount-10'] : "" ?>"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Within 90 Days :</label>
            <div class="col-md-4">
                <input type="text" class="form-control input-sm number-0" name="inv-discount-90" id="inv-discount-90"
                       value="<?php echo isset($options['inv-discount-90']) ? $options['inv-discount-90'] : "" ?>"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button type="button" class="btn btn-success btn-sm" id="save_discount"><i class="fa fa-save"></i>
                    <span>Save Settings</span></button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#save_discount").click(function (e) {
            var btn = this;
            DJ.disable_ele_fa(btn, "Saving");
            $.ajax({
                url: "<?php echo base_url("admin/settings/save-options") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#discount_form").serialize(),
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