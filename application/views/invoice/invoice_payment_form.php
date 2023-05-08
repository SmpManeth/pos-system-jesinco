<?php
/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>

<?php echo form_open("#", "id='invoice_payment_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<div class="form-group">
    <label class="col-lg-4 control-label">Balance</label>
    <div class="col-sm-6">
        <input type="text" class="form-control number-2" id="payment" name="payment" value="<?php echo $amount ?>" />
    </div>
</div>
<?php
if (user_can($user, CAN_REMOVE_FINE)) {
    ?>
    <div class="form-group">
        <label class="col-lg-4 control-label">With Fine</label>
        <div class="col-sm-6">
            <input type="checkbox" id="with_fine" name="with_fine" value="1" checked="checked" />
        </div>
    </div>
    <?php
} else {
    ?>
    <input type="checkbox" style="display: none" id="with_fine" name="with_fine" value="1" checked="checked" />
    <div class="form-group">
        <label class="col-lg-4 control-label">Fine : </label>
        <div class="col-sm-6">
            <p><?php echo number_format($fine, 2) ?></p>
        </div>
    </div>
    <?php
}
?>
<div class="form-group">
    <label class="col-lg-4 control-label">Payment</label>
    <div class="col-sm-6">
        <p class="form-control-static" id="payment_d"><?php echo number_format(doubleval($amount) + doubleval($fine), 2) ?></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label"></label>
    <div class="col-sm-6">
        <button type="button" class="btn btn-sm btn-success" id="make-payment-btn"><i class="fa fa-check-circle"></i> <span>Pay Now</span></button>
    </div>
</div>
<script>
    $(document).ready(function () {

        $("#with_fine").change(function(){
            var fine = <?php echo doubleval($fine) ?>;
            var amount = <?php echo doubleval($amount) ?>;


            if($(this).is(":selected")){
                $("#payment_d").html(DJ.format_number(find+amount));
            }else{
                $("#payment_d").html(DJ.format_number(amount));
            }

        });

        $(".number-2").number(true, 2);
        $("#make-payment-btn").click(function (e) {
            DJ.disable_btn_fa("make-payment-btn", "");

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    $.post("<?php echo base_url("invoice/add_payment"); ?>", {
                        amount: $("#payment").val(),
                        with_fine: $("#with_fine").is(":checked") ? 1 : 0,
                        inv_id: "<?php echo $inv_id ?>",
                        installment: "<?php echo $installment ?>",
                        fine: "<?php echo $fine ?>",
                        due_date: "<?php echo $due_date ?>",
                        long: position.coords.longitude,
                        lat: position.coords.latitude
                    }, function (data) {
                        DJ.enable_btn_fa("make-payment-btn", "Pay Now");
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                            $("#balance").html(data.balance);
                            if (data.complete == "YES") {
                                window.location.reload();
                            } else {
                                load_installment_data();
                            }
                            DJ.close_model();
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }

                    }, "JSON");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    });
</script>

