<?php
//Oct 22, 2018 2:49:29 PM 
?>
<title>Superadmin - Stock Adjust</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<div class="page-content-col">
    <br/>
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open(base_url("reporter/stock/current"), "class='form-horizontal' target='_blank'"); ?>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <p class="form-control-static">Items Sales Summary</p>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <select class="form-control input-sm select-picker" name="i" id="i">
                                <optgroup>
                                    <?php
                                    if (isset($items)) {
                                        foreach ($items as $item) {
                                            ?>
                                            <option 
                                                data-subtext="<?php echo $item->itm_code ?>" 
                                                data-price="<?php echo $item->selling ?>" 
                                                value="<?php echo $item->id ?>">
                                                    <?php echo $item->itm_name ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Current Stock :</label>
                            <input type="text" class="form-control input-sm" value="" name="qty" id="qty" />
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Staring :</label>
                            <input type="text" class="form-control input-sm" value="" name="starting" id="starting"/>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Actual Starting :</label>
                            <input type="text" class="form-control input-sm" value="" name="a_starting" id="a_starting"/>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <button type="button" id="save_btn" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> <span>Update</span></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#i").change(function () {
            var id = $("#i").val();
            $.ajax({
                url: "<?php echo base_url("stock/get_starting_stock_super") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        $("#starting").val(data.starting_stock);
                        $("#qty").val(data.current_stock);
                    } else {
                        $("#starting").val(0);
                        $("#qty").val(0);
                    }
                }
            });
        });
        $("#save_btn").click(function () {
            DJ.disable_btn_fa("save_btn", "Updating");
            var id = $("#i").val();
            var amount = $("#a_starting").val();
            var a_starting = $("#starting").val();
            $.ajax({
                url: "<?php echo base_url("stock/update_current_stock") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id, amount: amount, starting: a_starting},
                success: function (data) {
                    DJ.enable_btn_fa("save_btn", "Update");
                    $("#a_starting").val(0);
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $(".datepicker").datepicker({format: "yyyy-mm-dd", endDate: '<?php echo date("Y-m-d") ?>', autoclose: true});
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
    });
</script>