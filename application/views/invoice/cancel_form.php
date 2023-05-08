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
$total = doubleval($invoice->subtotal);
$discount = doubleval($invoice->discount);
$balance = doubleval($invoice->balance);

$paid_amount = $total - $discount - $balance;

$total_paid = $total_paid;// + doubleval($invoice->service_charge);
?>
<?php echo form_open("#", "id='cancel_form' class='form-horizontal'") ?>
<?php echo form_hidden("is_ajax_request") ?>
<?php echo form_hidden("inv_id", $invoice->id) ?>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">1/3 of Paid Amount</label>
    <div class="col-sm-6">
        <input type="text" name="refund_amount" id="refund_amount" readonly="true" class="form-control number-2" value="<?php echo ($total_paid / 3) > get_option('service-charge',null,$branch->id) ? $total_paid / 3 : 0 ?>" />
        <small>(Calculated automatically)</small>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label"></label>
    <div class="col-sm-6">
        <label class="mt-checkbox font-green-jungle">
            <input name="damaged" value="1" type="checkbox"> Mark as Damaged
            <span></span>
        </label>
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Unpaid Fines</label>
    <div class="col-sm-6">
        <input type="text" name="unpaid_fines" id="unpaid_fines" class="form-control number-2" value="" />
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Damage Deductions</label>
    <div class="col-sm-6">
        <input type="text" name="damaged_fines" id="damaged_fines" class="form-control number-2" value="" />
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Refund Amount</label>
    <div class="col-sm-6">
        <p class="form-control-static" id="total_refund_html"></p>
        <input type="hidden" id="total_refund" name="total_refund" />
    </div>
</div>
<div class="form-group">
    <label for="first_name" class="col-lg-4 control-label">Remarks</label>
    <div class="col-sm-6">
        <textarea name="remark" class="form-control"></textarea>
    </div>
</div>
<div class="form-group">
    <label for="address_1" class="col-lg-4 control-label"></label>
    <div class="col-lg-6 text-right">
        <button type="button" class="btn btn-success" id="modal-cancel-invoice"><i class="fa fa-save"></i> <span>Proceed</span></button>
    </div>
</div>
</form>
<?php echo form_close() ?>

<script>
    $(document).ready(function () {
        $(".number-2").number(true, 2);
        $("#modal-cancel-invoice").click(function () {
            DJ.disable_btn_fa('modal-cancel-invoice', 'Processing');
            $.ajax({
                url: "<?php echo base_url("invoice/cancel_invoice") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#cancel_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa('modal-cancel-invoice', 'Proceed');
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        window.location.reload();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#unpaid_fines").on("keyup", function () {
            calculate_refund_amount();
        });
        $("#damaged_fines").on("keyup", function () {
            calculate_refund_amount();
        });
        calculate_refund_amount();
    });

    function calculate_refund_amount() {

        var refund_amo1_3 = $("#refund_amount").val();
        var unpaid_fines = $("#unpaid_fines").val();
        var damaged_fines = $("#damaged_fines").val();

        console.log("refund_amo1_3 : " + refund_amo1_3);
        console.log("unpaid_fines : " + unpaid_fines);
        console.log("damaged_fines : " + damaged_fines);

        var total_refund_amo = Number(refund_amo1_3) - Number(unpaid_fines) - Number(damaged_fines);

        $("#total_refund").val(total_refund_amo);
        $("#total_refund_html").html(DJ.format_number(total_refund_amo, 2));
    }
</script>
