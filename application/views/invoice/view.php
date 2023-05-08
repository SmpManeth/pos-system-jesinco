<?php
//Aug 20, 2018 8:20:09 AM 
?>
<title>View Invoice</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div>
                <?php
                if ($invoice->status != "2") {
                    ?>
                    <a href="<?php echo base_url("invoice/print-invoice/" . $doc_id) ?>" class="btn btn-link pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                    <a href="jesinco.print://invoice/<?php echo $invoice->id ?>" class="btn btn-primary pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                    <?php
                    if (user_can($user, CAN_CANCEL_INVOICE) && !isset($type) && $invoice->status != "1") {
                        ?>
                        <a href="#/" class="btn btn-danger pull-left" id="cancel_btn" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-remove"></i><span> Cancel Invoice</span></a>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
                <h4 class="pull-left" style="margin-bottom: 0px"><label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($invoice->created_at)) ?></label></h4>
                <?php
                if ($invoice->status == "1" && $invoice->is_cash == "1") {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-purple"><i class="fa fa-check"></i> Paid in Cash</label></h4>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-<?php echo doubleval($invoice->balance) == 0 ? "success" : "danger" ?>">Payment <?php echo doubleval($invoice->balance) == 0 ? "Complete" : "Pending" ?></label></h4>
                    <?php
                } else if ($invoice->status == "3") {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-purple"><i class="fa fa-check"></i> Pending</label></h4>
                    <h4 class="pull-right" style="margin-bottom: 0px" id="payment_status"><label class="alert alert-info">Reservation Invoice</label></h4>
                    <button type="button" class="btn btn-warning pull-left" id="mark_as_delivered" style="margin-top: 10px;margin-left: 5px;"><i class="fa fa-truck"></i> <span>Mark as Delivered</span></button>
                    <?php
                } else if ($invoice->status == "4") {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px"><label class="alert alert-success">Item Delivered</label></h4>
                    <?php
                } else {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px"><label class="alert alert-<?php echo $invoice->status == "1" ? "success" : "danger" ?>"><?php echo isset($type) && $type=="do"?"DO ":"Invoice"?> <?php echo $invoice->status == "1" ? "Finished" : "Cancelled" ?> <?php echo $invoice->status=="2" && $invoice->cancel_approved=="1"?" | Approved":" | Pending" ?></label></h4>
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
                                <label class="control-label">Invoice Date :</label>
                                <p class="text-strong"><?php echo $invoice->inv_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">DO Note Number :</label>
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
                                                <td class="text-right">
                                                    <?php
                                                    if ($invoice->status == "1" || $invoice->status == "4") {
                                                        ?>
                                                        <button type="button" class="btn btn-xs btn-danger retun-btn pull-left" data-id="<?php echo $inv_item->id ?>"><i class="fa fa-reply"></i> <span></span></button>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php echo $display_qty; ?>
                                                </td>
                                                <td class="text-right"><?php echo $display_rate; ?></td>
                                                <td class="text-right"><?php echo is_zero($display_qty * $display_rate); ?> </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="subtotal">
                                    <tr>
                                        <td colspan="3" rowspan="10" class="well">
                                            <div class="pull-left">
                                                <h4>Repairing Information</h4>
                                                <?php
                                                if (isset($repair)) {
                                                    ?>
                                                    <p><?php
                                                        switch ($repair->status) {
                                                            case "0":
                                                                echo "Pending";
                                                                break;
                                                            case "1":
                                                                echo "In heade office";
                                                                break;
                                                            case "2":
                                                                echo "Repairing";
                                                                break;
                                                            case "3":
                                                                echo "Repair done";
                                                                break;
                                                            case "4":
                                                                echo "Repair done,In Branch";
                                                                break;
                                                            case "5":
                                                                echo "Handover to Customer";
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                        ?></p>
                                                    <p>Created Date : <?php echo $repair->created_date ?></p>
                                                    <p>Sent Date : <?php echo $repair->sent_date ?></p>
                                                    <p>Returned Date :  <?php echo $repair->returned_date ?></p>
                                                    <p>Handover Date :  <?php echo $repair->handover_date ?></p>
                                                    <p>Comments :  <?php echo $repair->comment ?></p>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div id = "remarks" class="pull-left" style="max-width:500px;white-space: pre-wrap;"><?php echo $invoice->remarks ?></div>
                                        </td>
                                        <td colspan="2" class="text-right">Sub Total</td>
                                        <td class="text-strong text-right" id="subtotal"><?php echo is_zero($invoice->subtotal) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Discount</td>
                                        <td class="text-strong text-right"><?php echo number_format($invoice->discount, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Down Payment</td>
                                        <td class="text-strong text-right"><?php echo number_format($invoice->down_payment, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Service Charge</td>
                                        <td class="text-strong text-right"><?php echo number_format($invoice->service_charge, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class="text-strong text-right" id="total"><?php echo is_zero($invoice->total) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Paid Amount</td>
                                        <td class="text-strong text-right" id="total"><?php echo is_zero($total_paid_amount) ?></td>
                                    </tr>
                                    <?php
                                    if ($invoice->status !== "1") {
                                        ?>
                                        <tr class="<?php echo doubleval($invoice->balance) > 0 ? "danger" : "success" ?>">
                                            <td colspan="2" class="text-right">Balance</td>
                                            <td class="text-strong text-right" id="balance"><?php echo is_zero($invoice->balance) ?></td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr class="<?php echo doubleval($invoice->balance) > 0 ? "danger" : "success" ?>">
                                            <td colspan="2" class="text-right">Balance</td>
                                            <td class="text-strong text-right" id="balance"><?php echo is_zero($invoice->balance) ?></td>
                                        </tr>
                                        <?php
                                    }
                                    if ($invoice->status == "2" || $invoice->status == "6") {
                                        ?>
                                        <tr class="warning">
                                            <td colspan="2" class="text-right">Unpaid Fines</td>
                                            <td class="text-strong text-right" ><?php echo is_zero($invoice->unpaid_fines) ?></td>
                                        </tr>
                                        <tr class="warning">
                                            <td colspan="2" class="text-right">Damage Deduction</td>
                                            <td class="text-strong text-right" ><?php echo is_zero($invoice->damaged_deduction) ?></td>
                                        </tr>
                                        <tr class="warning">
                                            <td colspan="2" class="text-right">Refund</td>
                                            <td class="text-strong text-right" ><?php echo is_zero($invoice->refund) ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <?php 
                    if(!isset($type) || $type=="invoices"){
                        ?>
                        <div class="row well">
                            <div class="col-lg-6  col-sm-12">
                                <legend>Installment Details</legend>
                                <div id="return_item_table">
                                    <h4 class="text-center"> Loading... <i class="fa fa-spin fa-spinner"></i></h4>
                                </div>

                            </div>
                            <div class="col-lg-6 text-right col-sm-12">
                                <legend>Invoice Payments</legend>
                                <div id="invoice_payment_table">
                                    <h4 class="text-center"> Loading... <i class="fa fa-spin fa-spinner"></i></h4>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <div class="row well">
                        <div class="col-lg-12 text-right col-sm-12">
                            <?php
                            if ($invoice->status == "1") {
                                ?>
                                <a href="<?php echo base_url("invoice/payments/" . decorate_code($invoice->inv_id, "invoice", $this->prefixes)) ?>" class="btn btn-sm btn-primary pull-left"><i class="fa fa-cc-visa"></i> Payment/Returns</a>
                                <?php
                            }
                            ?>
                                                <a href="<?php echo base_url("invoice/print-invoice/" . $doc_id) ?>" class="btn btn-link pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                    <a href="jesinco.print://invoice/<?php echo $invoice->id ?>" class="btn btn-primary pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Print Direct</a>
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
        <?php if(!isset($type) || $type=="invoices"){ ?>
            load_installment_data();
        <?php } ?>
        $("#mark_as_delivered").click(function () {
            DJ.Confirm("Do You want to Mark this Invoice as Deliverd?", function () {
                var id = $("#inv_id").val();
                DJ.disable_btn_fa("mark_as_delivered", "Processing");
                $.ajax({
                    url: "<?php echo base_url("invoice/mark_as_delivered") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        DJ.enable_btn_fa("mark_as_delivered", "Mark as Delivered");
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                            location.href = data.url;
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            });
        });

        $(".retun-btn").click(function (e) {
            var ele = this;
            DJ.Confirm("Are you sure want to add a repair item?", function () {
                var id = $(ele).data("id");
                DJ.disable_ele_fa(ele, "");
                $.ajax({
                    url: "<?php echo base_url("invoice_c/add_to_return") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        DJ.enable_btn_fa("mark_as_delivered", "Mark as Delivered");
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                            location.href = data.url;
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            });
        });
        $("#cancel_btn").click(function (e) {
            var ele = this;
            e.preventDefault();
            DJ.Overlay_confirm({
                title: "Are you want to <strong class='text-danger'>CANCEL</strong> this Invoice?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        var id = $("#inv_id").val();
                        DJ.load_to_model({
                            title: "Cancellation Information",
                            url: "<?php echo base_url("invoice/load_cancel_form"); ?>",
                            data: {is_ajax_request: "OK", inv_id: id}
                        });
                    }
                }
            });
        });
    });

    function load_installment_data() {
        $("#invoice_payment_table").load("<?php echo base_url("invoice/get_payment_data") ?>", {id:<?php echo $invoice->id ?>,type:'no-edit'});
        $("#return_item_table").load("<?php echo base_url("invoice/get_installment_data") ?>", {id:<?php echo $invoice->id ?>,type:'no-edit'});
    }
</script>