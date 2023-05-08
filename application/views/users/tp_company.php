<title>Select Company - The iDea Hub Inventory</title>
<div class="logo">
</div>
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <?php echo form_open("users/login", "id='comp_form' class='login-form'"); ?>
    <?php echo form_hidden("is_ajax_request") ?>
    <h3 class="form-title">Login to your account</h3>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Username</label>
        <div class="input-icon">
            <i class="fa fa-building"></i>
            <select class="form-control input-sm" name="company">
                <optgroup>
                    <option value="-1">--SELECT--</option>
                </optgroup>
                <optgroup>
                    <?php
                    if (isset($branches)) {
                        foreach ($branches as $branch) {
                            ?>
                            <option value="<?php echo $branch->id ?>"><?php echo $branch->branch_name_report ?></option>
                            <?php
                        }
                    }
                    ?>
                </optgroup>
            </select>
        </div>
    </div>

    <div class="form-actions">
        <a href="<?php echo base_url("users/logout") ?>" class="btn btn-link pull-left font-white" id="signin">
            <span>Logout</span>&nbsp;&nbsp;
        </a>
        <button type="button" class="btn btn-primary btn-sm pull-right" id="c_proceed">
            <span>Proceed</span> <i class="fa fa-arrow-right"></i>
        </button>
    </div>
    <?php echo form_close() ?>
</div>
<script>
    $(document).ready(function () {
        // init background slide images
        $("#c_proceed").click(function () {
            DJ.disable_btn_fa("c_proceed", "Processing");
            $.ajax({
                url: "<?php echo base_url("company/proceed") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#comp_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("c_proceed", "Proceed");
                    if (data.msg_type == "OK") {
                        DJ.disable_btn_fa("c_proceed", "Redirecting");
                        location.href = "<?php echo base_url("home") ?>";
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>