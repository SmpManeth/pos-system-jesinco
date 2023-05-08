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
<title>View DO Note</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div>
                <?php
                if ($invoice->status != "6") {
                    if (user_can($user, CAN_CANCEL_DO)) {
                        ?>
                        <a href="#/" class="btn btn-danger pull-left" id="cancel_btn" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-remove"></i><span> Return DO Note</span></a>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
                <h4 class="pull-left" style="margin-bottom: 0px"><label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($invoice->created_at)) ?></label></h4>
                <?php
                if ($invoice->status == "3") {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-purple"><i class="fa fa-check"></i> Pending</label></h4>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-info">Reservation Invoice</label></h4>
                    <?php
                    if (user_can($user, CAN_CANCEL_INVOICE)) {
                        ?>
                        <button type="button" class="btn btn-success pull-left" id="create_invoice" style="margin-top: 10px;margin-left: 5px;"><i class="fa fa-file-text-o"></i> <span>Create Invoice</span></button>
                        <?php
                    }
                    ?>
                    <?php
                } else if ($invoice->status == "4") {
                    ?>
                    <a href="<?php echo base_url("invoice/print-invoice/" . $doc_id) ?>" class="btn btn-link pull-left pull-left " style="margin-top: 10px;margin-left: 5px;"><i class="fa fa-print"></i> Print</a>
                    <h4 class="pull-right" style="margin-bottom: 0px"><label class="alert alert-success">Invoice Created</label></h4>
                    <a href="<?php echo base_url("invoice/payments/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes)) ?>" style="margin-top: 10px;margin-right: 5px;" class="btn btn-link pull-right"><i class="fa fa-cc-visa"></i> Payment/Returns</a>
                    <?php
                } else {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px">
                        <label class="alert alert-danger">
                            <?php 
                            if($invoice->cancel_approved=="1"){
                                ?>
                                <i class="fa fa-check-circle"></i>
                                <?php
                            }
                            ?>
                            DO Note Cancelled
                        </label>
                        </h4>
                    <?php
                }
                ?>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <input type="hidden" id="inv_id" value="<?php echo $invoice->id ?>" />
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Customer :</label>
                                <p class="text-strong">
                                    <?php echo $customer->customer_prefix . " " . $customer->customer_name ?>
                                    <small class="text-info"><?php echo $customer->devision ?></small>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">DO Note Date :</label>
                                <p class="text-strong"><?php echo $invoice->inv_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Crated By :</label>
                                <p class="text-strong"><?php echo $invoice->username ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">DO Number :</label>
                                <p class="text-strong"><?php echo $invoice->do_number ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-scrollable">
                            <table class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Code</td>
                                        <td>Item</td>
                                        <td>Quantity</td>
                                        <td class="text-right">Rate</td>
                                        <td class="text-right">Total</td>
                                        <td></td>
                                    </tr>                
                                </thead>
                                <tbody id="item_body">
                                    <?php
                                    if (isset($invoice_items)) {
                                        $i = 0;
                                        foreach ($invoice_items as $inv_item) {
                                            $i++;
                                            $qty = $inv_item->qty;
                                            $rate = $inv_item->rate;
                                            $display_qty = $inv_item->display_qty;
                                            $display_rate = $inv_item->display_rate;
                                            $secondary = FALSE;
                                            if ($qty != $display_qty) {
                                                $secondary = TRUE;
                                            }
                                            ?>
                                            <tr class="<?php echo $secondary ? "warning" : "" ?>">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $inv_item->itm_code; ?></td>
                                                <td><?php echo $inv_item->itm_name; ?></td>
                                                <td class="text-right"></button><?php echo $display_qty; ?></td>
                                                <td class="text-right"><?php echo $display_rate; ?></td>
                                                <td class="text-right"><?php echo is_zero($display_qty * $display_rate); ?> </td>
                                                <td></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="subtotal">
                                    <tr>
                                        <td colspan="3" rowspan="7" class="well">
                                            <div id="remarks" class="pull-left"><?php echo $invoice->remarks ?></div>
                                        </td>
                                        <td colspan="2" class="text-right">Sub Total</td>
                                        <td class="text-strong text-right" id="subtotal"><?php echo is_zero($invoice->subtotal) ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    if ($invoice->status == "3") {
                                        ?>
                                        <tr>
                                            <td colspan="2" class="text-right">Discount <span id="discount_amount"></span></td>
                                            <td class="" style="width:150px;">

                                                <div class="input-group">
                                                    <input type="text" id="inv_discount" class="form-control text-right number" value="<?php echo ($invoice->discount) ?>" placeholder="Discount" />
                                                    <span class="input-group-addon" id="basic-addon1">%</span>
                                                </div>
                                            </td>
                                            <td rowspan="2">
                                                <?php
                                                if (user_can($user, CAN_EDIT_DO)) {
                                                    ?>
                                                    <button type="button" class="btn btn-sm btn-primary hidden" id="save_discount"><i class="fa fa-save"></i><span></span></button>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right">Down Payment</td>
                                            <td class="" style="width:150px;">
                                                <input type="text"  id="down_payment" class="form-control text-right number" value="<?php echo ($invoice->down_payment) ?>" />
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr class="text-right">
                                            <?php $discount = doubleval($invoice->subtotal) * (doubleval($invoice->discount) / 100) ?>
                                            <td colspan="2">Discount <span id="discount_amount">(<?php echo number_format($discount, 2) ?>)</span></td>
                                            <td class="" style="width:150px;">
                                                <p class="form-control-static text-strong"><?php echo number_format($invoice->discount, 2) ?>%</p>
                                            </td>
                                            <td rowspan="2"></td>
                                        </tr>
                                        <tr class="text-right">
                                            <td colspan="2">Down Payment</td>
                                            <td class="" style="width:150px;">
                                                <p class="form-control-static text-strong"><?php echo number_format($invoice->down_payment, 2) ?></p>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Service Charge</td>
                                        <td class="text-strong text-right" id="service_charge"><?php echo number_format($invoice->service_charge, 2) ?></td>
                                        <td style="width:40px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class="text-strong text-right" id="total"><?php echo is_zero($invoice->total) ?></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    if ($invoice->status == "2") {
                                        ?>
                                        <tr class="warning">
                                            <td colspan="2" class="text-right">Refund</td>
                                            <td class="text-strong text-right" ><?php echo is_zero($invoice->refund) ?></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var serials = new Array();
    $(document).ready(function () {
        $("#create_invoice").click(function () {

            if($("#save_discount").is(":visible")){
                DJ.Notify("Please Save Discount and Down Payment before create the invoice", "danger");
            }else{
                var down_payment = $("#down_payment").val();
                if(Number(down_payment) > 0){
                    DJ.Confirm("Do You want to Create an Invoice for this Delivery Note?", function () {
                    var id = $("#inv_id").val();

                    DJ.load_to_model({
                        title: "Payment Data",
                        url: "<?php echo base_url("reservation/load_payment_form"); ?>",
    //                    type: "",
                        data: {is_ajax_request: "OK", inv_id: id}
                    });
                });
                }else{
                    DJ.Notify("Down Payment amount is not provided. <br>Please Enter down payment and <b>Save</b>", "danger");
                }
            }
        });
        $("#cancel_btn").click(function (e) {
            var ele = this;
            e.preventDefault();
            DJ.Overlay_confirm({
                title: "Are you want to <strong class='text-danger'>CANCEL</strong> this Reservation Note?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {

                        DJ.load_to_model({
                            title: "Cancellation Form",
                            url: "<?php echo base_url("reservation/load_cancellation_form"); ?>",
        //                    type: "",
                            data: {is_ajax_request: "OK", inv_id: $("#inv_id").val(),type:"do"}
                        });
                    }
                }
            });
        });

        $("#save_discount").click(function () {
            DJ.disable_btn_fa("save_discount", "");
            $.ajax({
                url: "<?php echo base_url("invoice/save-discount") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {discount: $("#inv_discount").val(), down_payment: $("#down_payment").val(), id: $("#inv_id").val()},
                success: function (data) {
                    DJ.enable_btn_fa("save_discount", "");
                    $("#save_discount").addClass("hidden");
                    if (data.msg_type == "OK") {

                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        var _discount = 0;
        $("#inv_discount, #down_payment").on("paste keyup", function () {
            var changed = $(this).val();
            if (Number(_discount) !== Number(changed)) {
                if ($("#inv_id").val() !== "") {
                    $("#save_discount").removeClass("hidden");
                }
            } else {
                $("#save_discount").addClass("hidden");
            }
            calculate_discount();
        });
        $(".number").number(true, 2);
<?php
if ($invoice->status == "3") {
    ?>
            calculate_discount();
<?php } ?>
    });


    function calculate_discount() {
        var discount_percentage = $("#inv_discount").val();
        var down_payment = $("#down_payment").val();
        var service_charge = DJ.replace_coma($("#service_charge").html());
        var sub_totla = DJ.replace_coma($("#subtotal").html());

        var discount = Number(sub_totla) * (Number(discount_percentage) / 100);

        $("#discount_amount").html("(" + $.number(discount, 2) + ")");

        var tot = Number(sub_totla) - Number(Math.round(discount)) - Number(down_payment) - Number(service_charge);
        $("#total").html(DJ.format_number(tot, 2));
    }
</script>
