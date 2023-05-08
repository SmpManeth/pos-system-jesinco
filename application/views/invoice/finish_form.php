<?php

$inv_discount_10 = get_option('inv-discount-10', 3, $this->branch->id);
$inv_discount_90 = get_option('inv-discount-90', 3, $this->branch->id);
$created_date = $invoice->inv_created_on;
$date_passed = intval(date_different($created_date, date('Y-m-d')));

$discount = 0.00;
if($date_passed > 0 && $date_passed <= 10 ){
    $discount = doubleval($inv_discount_10);
}
if($date_passed > 10 && $date_passed <= 90 ){
    $discount = doubleval($inv_discount_90);
}

?>
<?php echo form_open("#", "id='city_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<div class="form-group">
    <label class="col-lg-4 control-label">Total</label>
    <div class="col-sm-6">
        <p class="form-control-static"><?php echo number_format($invoice->subtotal, 2) ?></p>
        <input value="<?php echo $invoice->subtotal ?>" type="hidden" id="f_total"/>
        <input value="<?php echo $invoice->balance ?>" type="hidden" id="f_balance"/>
        <input value="<?php echo $fine ?>" type="hidden" id="f_fine"/>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">Balance</label>
    <div class="col-sm-6">
        <p class="form-control-static"><?php echo number_format($invoice->balance, 2) ?></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">Fines</label>
    <div class="col-sm-6">
        <p class="form-control-static" id="fines"><?php echo number_format($fine, 2) ?></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">Payment</label>
    <div class="col-sm-6">
        <p class="form-control-static" id="payment"><?php echo number_format($payment, 2) ?></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">Discount</label>
    <div class="col-sm-6">
        <div class="input-group">
            <input type="text" id="discount" class="form-control text-right number" value="<?php echo $discount?>"  placeholder="Discount"/>
            <span class="input-group-addon" id="basic-addon1">%</span>
        </div>
        <p><small>Date Passed : <?php echo $date_passed;?></small></p>
        <p><small>0-10 days : <?php echo $inv_discount_10;?>%</small> | <small>11-90 days : <?php echo $inv_discount_90;?>%</small></p>
        <!--<input type="text" id="discount" name="discount" class="form-control number-2 inpit-sm" value=""/>-->
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label"></label>
    <div class="col-sm-6">
        <button type="button" class="btn btn-sm btn-success" id="finish-invoice-btn"><i class="fa fa-check-circle"></i>
            <span>Finish</span></button>
    </div>
</div>

<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        $("#discount").keyup(function () {
            var total = $("#f_total").val();
            var fine = $("#f_fine").val();
            var f_balance = $("#f_balance").val();
            var _discount = this.value;

            var discount = Number(total) * (Number(_discount) / 100);

            $("#payment").html(DJ.format_number(Number(f_balance) - Number(discount) + Number(fine)), 2);
        });

        $("#discount").trigger('keyup');
        $("#finish-invoice-btn").click(function () {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var discount = $("#discount").val();
                    DJ.disable_btn_fa("finish-invoice-btn", "Saving");
                    $.ajax({
                        url: "<?php echo base_url("invoice/finish_with_discount") ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: <?php echo $invoice->id ?>,
                            discount: discount,
                            long: position.coords.longitude,
                            lat: position.coords.latitude
                        },
                        success: function (data) {
                            console.log(data);
                            DJ.enable_btn_fa("finish-invoice-btn", "Save");
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                window.location.reload();
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    });
</script>