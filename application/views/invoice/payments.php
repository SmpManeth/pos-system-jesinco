<?php
//Aug 20, 2018 8:20:09 AM 
?>
<title>Invoice Payments/Returns</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div>
                <?php
                if ($invoice->status != "2") {
                    ?>
                    <a href="<?php echo base_url("invoice/print-invoice/" . $doc_id) ?>" class="btn btn-link pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Print</a>
                    <a href="jesinco.print://invoice/<?php echo $invoice->id ?>" class="btn btn-primary pull-left" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-print"></i> Direct Print</a>
                    <?php
                    if (user_can($user, CAN_CANCEL_INVOICE) && $invoice->cancel_approved == "0" && ($invoice->status == "4" || $invoice->status == "1")) {
                        ?>
                        <a href="#/" class="btn btn-danger pull-left" id="cancel_btn" style="margin-top: 10px;margin-right: 5px;"><i class="fa fa-remove"></i><span> Return Invoice</span></a>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
                <h4 class="pull-left" style="margin-bottom: 0px"><label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($invoice->created_at)) ?></label> </h4>
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
                    <?php
                } else if ($invoice->status == "4") {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px"><label class="alert alert-success">Item Delivered</label></h4>
                    <?php
                } else {
                    ?>
                    <h4 class="pull-right" style="margin-bottom: 0px"><label class="alert alert-<?php echo $invoice->status == "1" ? "success" : "danger" ?>">Invoice <?php echo $invoice->status == "1" ? "Finished" : "Cancelled" ?></label></h4>
                    <?php
                }
                ?>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <input type="hidden" id="balance_hidden" value="<?php echo $invoice->balance ?>" />
                    <input type="hidden" id="inv_id" value="<?php echo $invoice->id ?>" />
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-3">
                                <label class="control-label">Customer : </label>
                                <p class="text-strong ">
                                    <?php echo $customer->customer_prefix . " " . $customer->customer_name ?>
                                    <small class="text-info"><?php echo $customer->devision ?></small>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Delivery Date :</label>
                                <p class="text-strong ">
                                    <span class="pull-left" id="inv_date"><?php echo $invoice->inv_date ?></span>
                                </p>
                                <i class="fa fa-edit edit_qty_btn pull-right" id="edit_date_btn" data-id="<?php echo $invoice->id ?>"></i>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Invoice Date :</label>
                                <p class="text-strong ">
                                    <span class="pull-left" id="inv_date"><?php echo $invoice->inv_created_on ?></span>
                                </p>
                                <!--<i class="fa fa-edit edit_qty_btn pull-right" id="edit_date_btn" data-id="<?php echo $invoice->id ?>"></i>-->
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">&nbsp;</label>
                                <p class=""><small>Last Edit By <?php echo $invoice->username . " @ " . $invoice->last_edit_at ?></small><small class="label label-info label-sm">#<?php echo $invoice->id ?></small></p>
                            </div>
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-6  col-sm-12">
                            <legend>Installment Details</legend>
                            <div id="return_item_table">
                                <h4 class="text-center"> Loading... <i class="fa fa-spin fa-spinner"></i></h4>
                            </div>

                        </div>
                        <div class="col-lg-6 text-right col-sm-12">
                            <legend>
                                <?php
                                if (doubleval($invoice->balance) > 0) {
                                    ?>
                                    <button type="button" class="pull-left btn btn-xs btn-primary" id="finishPayment"><i class="fa fa-check"></i> Finish Invoice</button>
                                    <button style="margin: 0 5px;" type="button" class="pull-left btn btn-xs btn-danger" id="mark_as_visited"><i class="fa fa-map-marker"></i> <span>Mark as Visited</span></button>
                                    <?php
                                }
                                ?>
                                <button type="button" class="pull-left btn btn-xs btn-default" id="get_visit_history"><i class="fa fa-list"></i> <span>Get Visit History</span></button>
                                Invoice Payments</legend>
                            <div id="invoice_payment_table">
                                <h4 class="text-center"> Loading... <i class="fa fa-spin fa-spinner"></i></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-scrollable">
                            <table class="table table-striped table-bordered table-sm">
                                <caption class="text-right">
                                </caption>
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Code</td>
                                        <td>Item</td>
                                        <td>Quantity</td>
                                        <td class="text-right">Rate</td>
                                        <td class="text-right">Total</td>
                                        <?php
                                        if ($invoice->status == "1") {
                                            echo "<td></td>";
                                        }
                                        ?>
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
                                                <td class="text-right"><?php echo $secondary ? "<span class='text-strong pull-left view_qriginal'> $qty </span>" : '' ?> <?php echo $display_qty; ?></td>
                                                <td class="text-right"><?php echo $secondary ? "<span class='text-strong pull-left view_qriginal'> $rate </span>" : '' ?><?php echo $display_rate; ?></td>
                                                <td class="text-right"><?php echo $secondary ? "<span class='text-strong pull-left view_qriginal'> " . is_zero($qty * $rate) . "</span>" : '' ?><?php echo is_zero($display_qty * $display_rate); ?> </td>
                                                <?php
                                                if ($invoice->status == "1") {
                                                    ?>
                                                    <td>
                                                        <button type="button" class="btn btn-link btn-xs" onclick="return_item(<?php echo $inv_item->id ?>,<?php echo $secondary ? "true" : "false" ?>, this)"><i class="fa fa-reply text-danger"></i></button>
                                                    </td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="subtotal">
                                    <tr>
                                        <td colspan="3" rowspan="6" class="well">
                                            <div id="remarks" class="pull-left"><?php echo $invoice->remarks ?></div>
                                            <i class="fa fa-edit edit_qty_btn pull-right" id="edit_rem_btn" data-id="<?php echo $invoice->id ?>"></i>
                                        </td>
                                        <td colspan="2" class="text-right">Sub Total</td>
                                        <td class="text-strong text-right" id="subtotal"><?php echo is_zero($invoice->subtotal) ?></td>
                                    </tr>
                                    <tr>
                                        <?php $discount = doubleval($invoice->subtotal) * (doubleval($invoice->discount) / 100) ?>
                                        <td colspan="2" class="text-right">Discount (<?php echo number_format($discount, 2) ?>)</td>
                                        <td class="text-strong text-right"><?php echo number_format($invoice->discount) ?>%</td>
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
                                        <td colspan="2" class="text-right">Balance</td>
                                        <td class="text-strong text-right" id="balance"><?php echo is_zero($invoice->balance) ?></td>
                                    </tr>
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
<div id="secondary_item" style="display: none;">
    <div>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="display_qty" class="col-sm-4 control-label">Actual Quantity</label>
                <div class="col-sm-5">
                    <input type="hidden" id="ret_itm_id" >
                    <input type="text" class="form-control input-sm number" id="qty" placeholder="Quantity">
                </div>
            </div>
            <div class="form-group" id="display_qty_wrapper">
                <label for="display_rate" class="col-sm-4 control-label">Display Quantity</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control input-sm number" id="display_qty" placeholder="Display Quantity">
                </div>
            </div>
            <div class="form-group">
                <label for="display_rate" class="col-sm-4 control-label">Remarks</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm" id="ret_remarks" />
                    <small class="pull-right text-muted" id="char_qty">0/200</small>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8" style="padding-left: 35px;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="with_refund" value="1"> With Refund
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="button" id="return_item_btn" class="btn btn-primary btn-sm"><i class="fa fa-reply-all"></i> <span>Add Return</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
       
        var customer_location = <?php echo !empty($customer->location)?$customer->location:'{"long":"","lat":""}' ?>;
        $("#mark_as_visited").click(function (e) {
            DJ.Overlay_confirm({
                title: "Are you want to <strong class='text-danger'>Mark as Visited</strong> at this Location?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function (position) {

                                var is_in_range = check_location_in_range(customer_location,{'lat': position.coords.latitude,'long': position.coords.longitude},100)
                                if(is_in_range){
                                // if(true){
                                    DJ.disable_btn_fa("mark_as_visited", "Processing");
                                    $.ajax({
                                        url: "<?php echo base_url("invoice/mark_as_visited") ?>",
                                        type: 'POST',
                                        dataType: 'JSON',
                                        data: {
                                            id: <?php echo $invoice->id ?>, 
                                            long: position.coords.longitude, 
                                            lat: position.coords.latitude
                                            },
                                        success: function (data) {
                                            DJ.enable_btn_fa("mark_as_visited", "Mark as Visited");
                                            if (data.msg_type == "OK") {
                                                DJ.Notify("Mark as Visited Successfully.", "success");
                                            } else {
                                                DJ.Notify(data.msg, "danger");
                                            }
                                        }
                                    });
                                }else{
                                    DJ.Notify("You are not near the customers location.", "danger");  
                                }
                            });
                        } else {
                            alert("Geolocation is not supported by this browser.");
                        }
                    }
                }
            });
        });

        load_installment_data();
        $("#add_payment").click(function () {
            DJ.Overlay_input({
                title: "Input Payment Amount here.",
                type: "number",
                greater_than: 0,
                button: {
                    yes: {txt: "Pay Now"},
                },
                click: function (v) {
                    DJ.disable_btn_fa("add_payment", "Paying");
                    $.ajax({
                        url: "<?php echo base_url("invoice/add_payment") ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {id: <?php echo $invoice->id ?>, payment: v},
                        success: function (data) {
                            DJ.enable_btn_fa("add_payment", "Add Paymnet");
                            if (data.msg_type == "OK") {

                                $("#balance").html(data.balance);
                                $("#balance_hidden").val(data.balance).change();
                                var tr = document.createElement("tr");
                                var td1 = document.createElement("td");
                                var td2 = document.createElement("td");
                                var td3 = document.createElement("td");
                                var td4 = document.createElement("td");
                                var a = document.createElement("a");
                                $(a).html('<i class="fa fa-times text-danger"></i>').click(function (e) {
                                    cancel_payment(data.id, a, e);
                                });
                                var tbody = $("#tbody_payments");
                                $(td1).html($(tbody).children().size() + 1);
                                $(td2).html(data.date);
                                $(td3).html(data.payment);
                                $(td4).append(a);
                                $(tr).append(td1, td2, td3, td4).addClass("success");
                                $(tbody).append(tr);
                                setTimeout(function () {
                                    $(tr).removeClass("success")
                                }, 2000);
                                if (Number(DJ.replace_coma(data.balance)) == 0) {
                                    $("#add_payment").hide();
                                }
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                }
            })
        });
        $("#ret_remarks").keyup(function () {
            var text = this.value;
            console.log(text.length);
            $("#char_qty").html((text.length) + "/200")
        });
        $("#edit_rem_btn").click(function () {
            DJ.Overlay_input({
                title: "Enter Remarks",
                value: $("#remarks").html(),
                placeholder: "Remarks",
                button: {
                    yes: {txt: "OK"},
                    no: {txt: "CANCEL"}
                },
                click: function (v) {
                    $.ajax({
                        url: "<?php echo base_url("invoice/save-remarks") ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {id: $("#edit_rem_btn").data("id"), remarks: v},
                        success: function (data) {
                            if (data.msg_type == "OK") {
                                $("#remarks").html(data.remarks);
                                DJ.Notify(data.msg, "success");
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                }
            });
        });
        $("#edit_date_btn").click(function () {
            DJ.Overlay_input({
                title: "Enter New Date",
                value: $("#inv_date").html(),
                type: "date",
                options: {format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 14, "-") ?>', endDate: '<?php echo date("Y-m-d") ?>'},
                placeholder: "Invoice Date",
                button: {
                    yes: {txt: "OK"},
                    no: {txt: "CANCEL"}
                },
                click: function (v) {
                    $.ajax({
                        url: "<?php echo base_url("invoice/save-inv_date") ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {id: $("#edit_rem_btn").data("id"), inv_date: v},
                        success: function (data) {
                            if (data.msg_type == "OK") {
                                $("#inv_date").html(data.remarks);
                                DJ.Notify(data.msg, "success");
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                }
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
                        var id = $("#edit_rem_btn").data("id");
                        DJ.load_to_model({
                            title: "Cancellation Form",
                            url: "<?php echo base_url("reservation/load_cancellation_form"); ?>",
                            data: {is_ajax_request: "OK", inv_id: id,type:"invoice"}
                        });
                    }
                }
            });
        });
        $("#finishPayment").click(function () {
            var id = $("#edit_rem_btn").data("id");
            DJ.load_to_model({
                title: "Finish Invoice Payments",
                url: "<?php echo base_url("invoice/get_finish_form") ?>",
                data: {id: id},
                fade: "fade",
            });
        });

        $("#get_visit_history").click(function (e) {
            var id = $("#edit_rem_btn").data("id");
            DJ.load_to_model({
                title: "Finish Invoice Payments",
                url: "<?php echo base_url("invoice/get_visit_history") ?>",
                data: {id: id},
                fade: "fade",
            });
        });
    });

    function load_installment_data() {
        $("#invoice_payment_table").load("<?php echo base_url("invoice/get_payment_data") ?>", {id:<?php echo $invoice->id ?>});
        $("#return_item_table").load("<?php echo base_url("invoice/get_installment_data") ?>", {id:<?php echo $invoice->id ?>});
    }

    function check_location_in_range(main_locaton,given_location,distance){
        var is_in_range = false;

console.log(main_locaton);
console.log(given_location);

        const { lat: lat1, long: lon1 } = main_locaton;
        const { lat: lat2, long: lon2 } = given_location;
        const degToRad = x => x * Math.PI / 180;

        console.log(lon1);
        console.log(lat1);
        console.log(lat2);
        console.log(lon2);
        

        const R = 6371;
        const halfDLat = degToRad(lat2 - lat1) / 2;  
        const halfDLon = degToRad(lon2 - lon1) / 2;  
        const a = Math.sin(halfDLat) * Math.sin(halfDLat) + 
                    Math.cos(degToRad(lat1)) * Math.cos(degToRad(lat2)) * 
                    Math.sin(halfDLon) * Math.sin(halfDLon);  
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); 
        var dt =  R * c; 
        
        if(dt<=0.1){
            is_in_range=true;
        }

        return is_in_range;
    }


</script>