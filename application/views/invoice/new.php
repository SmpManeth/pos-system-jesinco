<?php
//Aug 20, 2018 8:20:09 AM 
?>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<title>New Reservation Note</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
<!--                    <input type="hidden" value="" name="id" id="id" />-->
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Customer :</label>
                                <input type="hidden" value="" name="id" id="inv_id" />
                                <select class="form-control input-sm select-picker" name="customer" id="customer">
                                    <optgroup>
                                        <option value="">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($customers)) {
                                            foreach ($customers as $cus) {
                                                ?>
                                                <option value="<?php echo $cus->id ?>"><?php echo $cus->customer_prefix . " " . $cus->customer_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Invoice Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="inv_date" id="inv_date" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">DO Number :</label>
                                <input type="text" class="form-control input-sm" name="do_number" id="do_number" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Item <span id="stock_qty"></span></label>
                                <select class="form-control input-sm select-picker" name="itm_code" id="itm_code">
                                    <optgroup>
                                        <option value="">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($items)) {
                                            foreach ($items as $item) {
                                                ?>
                                                <option 
                                                    data-subtext="<?php echo $item->itm_code ?>" 
                                                    data-price="<?php echo $item->selling ?>" 
                                                    data-wholesale="<?php echo $item->wholesale ?>" 
                                                    data-stock="<?php echo $item->qty ?>" 
                                                    value="<?php echo $item->itm_id ?>">
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
                                <label class="control-label">Quantity:</label>
                                <input type="text" class="form-control input-sm number" name="qty" id="qty" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label"><span>Rate : </span></label>
                                <span class="pull-right" style="margin-top: 7px;"><button type="button" id="toggle_btn_wh" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i></button></span>
                                <input type="text" class="form-control input-sm number" readonly name="rate" id="rate" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Total :</label>
                                <input type="text" class="form-control input-sm number" name="itm_total" id="itm_total" readonly=""/>
                            </div>
                            <div class="col-md-2 text-right">
                                <label class="control-label">&nbsp;</label><br/>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" id="add_item_btn" class="btn btn-primary"><i class="fa fa-plus"></i> <span>Add Item</span></button>
                                    
                                </div>
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
                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" rowspan="3" class="text-right">
                                            <textarea class="form-control" placeholder="Remarks" id="remarks" name="remarks"></textarea>
                                        </td>
                                        <td colspan="2" class="text-right">Sub Total</td>
                                        <td class="text-strong text-right" id="inv_sub_total">0.00</td>
                                        <td style="width:40px;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Discount</td>
                                        <td class="" style="width:150px;"><input type="text"  id="inv_discount" class="form-control text-right number" value="" /></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary hidden" id="save_discount"><i class="fa fa-save"></i><span></span></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td id="inv_total" class="text-strong text-right">0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-12 text-right col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary" id="finish_credit_inv_btn"><i class="fa fa-level-down"></i> <span>Finish Credit</span></button>
                            <button type="button" class="btn btn-sm btn-success" id="finish_inv_btn"><i class="fa fa-check"></i> <span>Finish Cash</span></button>
                            <button type="button" class="btn btn-sm default" id="cancel_po_btn"><i class="fa fa-remove text-danger"></i> <span>Cancel</span></button>
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
                <label for="display_qty" class="col-sm-4 control-label">Display Quantity</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control input-sm number" id="display_qty" placeholder="Quantity">
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control input-sm text-uppercase" id="display_unit" placeholder="Unit of Measure">
                </div>
            </div>
            <div class="form-group">
                <label for="display_rate" class="col-sm-4 control-label">Display Rate</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm number" id="display_rate" placeholder="Rate">
                </div>
            </div>
            <div class="form-group">
                <label for="display_total" class="col-sm-4 control-label">Display Total</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm number" readonly="" id="display_total" placeholder="Total">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="button" id="save_secondary_item" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <span>Add Item</span></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var serials = new Array();
    $(document).ready(function () {
        $("#finish_inv_btn").hide();
        $("#finish_credit_inv_btn").hide();
        $("#cancel_po_btn").hide();
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>', endDate: '<?php echo date("Y-m-d") ?>'});
        $(".number").number(true, 3);
        $("#qty").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#rate").focus().select();
            }
        });
        $("#display_qty").keyup(function (e) {
            if (e.keyCode == 13) {
                $("#display_unit").focus();
                $("#display_unit").select();
            }
            calculate_display_total();
        });
        $("#display_unit").keyup(function (e) {
            if (e.keyCode == 13) {
                if ($(this).val() !== "") {
                    $("#display_rate").focus();
                    $("#display_rate").select();
                }
            }
        });
        $("#display_rate").keyup(function (e) {
            if (e.keyCode == 13) {
                if ($(this).val() !== "") {
                    $("#save_secondary_item").trigger("click");
                }
            }
            calculate_display_total();
        });
        $("#rate").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#add_item_btn").trigger("click");
            }
        });
        var _discount = 0;
        $("#inv_discount").keypress(function () {
            _discount = $(this).val();
        });
        $("#inv_discount").on("paste keyup", function () {
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
        var is_whole_sale = false;
        $("#toggle_btn_wh").click(function () {
            var wholesale = $("#itm_code option:selected").data("wholesale");
            var retal = $("#itm_code option:selected").data("price");
            console.log(is_whole_sale);
            if (!is_whole_sale) {
                $("#rate").val(wholesale);
                is_whole_sale = true;
            } else {
                $("#rate").val(retal);
                is_whole_sale = false;
            }
            calculate_item();
        });
        $("#itm_code").change(function () {
            var price = $("#itm_code option:selected").data("price");
            var stock_qty = $("#itm_code option:selected").data("stock");
            $("#rate").val(price);
            if (stock_qty !== "" && typeof stock_qty != "undefined") {
                $("#stock_qty").html("( " + stock_qty + " max)");
            } else {
                $("#stock_qty").html("");
            }
            calculate_item();
        });
        $("#add_item_btn").click(function () {
            var item_ele = $("#itm_code option:selected");
            var itm_ok = item_exists($(item_ele).data("subtext"));
            if (itm_ok) {
                add_item(0)
            } else {
                DJ.Notify("Item Already Exists", "danger");
            }
        });

        $("#add_sec_item_btn").click(function (e) {
            e.preventDefault();
            var inv_id = $("#inv_id").val();
            var customer = $("#customer").val();
            var inv_date = $("#inv_date").val();
            var qty = 0;
            DJ.Overlay_input({
                title: "Enter Display Quantity?",
                type: "number",
                greater_than: 0,
                button: {
                    yes: {txt: "Proceed"},
                    no: {txt: "CANCEL"}
                },
                click: function (v) {
                    var item = $("#itm_code").val();
                    var qty = v;
                    var rate = $("#rate").val();
                    if (customer !== "") {
                        $("#customer").parent().parent().removeClass("has-error");
                        if (inv_date != "") {
                            $("#inv_date").parent().removeClass("has-error");
                            if (item != "") {
                                $("#itm_code").parent().parent().removeClass("has-error");
                                if (Number(qty) > 0) {
                                    if (Number(rate) > 0) {
                                        $("#rate").parent().removeClass("has-error");
                                        var item_ele = $("#itm_code option:selected");
                                        var itm_ok = item_exists($(item_ele).data("subtext"));
                                        if (itm_ok) {
                                            var sec_price = $("#itm_code option:selected").data("secprice");
                                            var secunitname = $("#itm_code option:selected").data("secunitname");
                                            if (sec_price !== "" && typeof sec_price !== "undefined") {
                                                add_secondary_item(v, sec_price, secunitname);
                                            } else {
                                                DJ.Notify("This item not has a secondary prive.")
                                            }
                                        } else {
                                            DJ.Notify("Item Already Exists", "danger");
                                        }
                                    } else {
                                        $("#rate").parent().addClass("has-error");
                                    }
                                } else {
                                    $("#qty").parent().addClass("has-error");
                                }
                            } else {
                                $("#itm_code").parent().parent().addClass("has-error");
                            }

                        } else {
                            $("#inv_date").parent().addClass("has-error");
                        }
                    } else {
                        $("#customer").parent().parent().addClass("has-error");
                    }
                }
            });
        });
        $("#add_piece_btn").click(function (e) {
            e.preventDefault();
            var inv_id = $("#inv_id").val();
            var customer = $("#customer").val();
            var inv_date = $("#inv_date").val();
            var qty = 0;
            DJ.Overlay_input({
                title: "Enter Display Quantity?",
                type: "number",
                greater_than: 0,
                button: {
                    yes: {txt: "Proceed"},
                    no: {txt: "CANCEL"}
                },
                click: function (v) {
                    var item = $("#itm_code").val();
                    var qty = v;
                    var rate = $("#rate").val();
                    if (customer !== "") {
                        $("#customer").parent().parent().removeClass("has-error");
                        if (inv_date != "") {
                            $("#inv_date").parent().removeClass("has-error");
                            if (item != "") {
                                $("#itm_code").parent().parent().removeClass("has-error");
                                if (Number(qty) > 0) {
                                    if (Number(rate) > 0) {
                                        $("#rate").parent().removeClass("has-error");
                                        var item_ele = $("#itm_code option:selected");
                                        var itm_ok = item_exists($(item_ele).data("subtext"));
                                        if (itm_ok) {
                                            var sec_price = $("#itm_code option:selected").data("third_price");
                                            var secunitname = $("#itm_code option:selected").data("third_unit_name");
                                            if (sec_price !== "" && typeof sec_price !== "undefined") {
                                                add_secondary_item(v, sec_price, secunitname);
                                            } else {
                                                DJ.Notify("This item not has a secondary prive.")
                                            }
                                        } else {
                                            DJ.Notify("Item Already Exists", "danger");
                                        }
                                    } else {
                                        $("#rate").parent().addClass("has-error");
                                    }
                                } else {
                                    $("#qty").parent().addClass("has-error");
                                }
                            } else {
                                $("#itm_code").parent().parent().addClass("has-error");
                            }

                        } else {
                            $("#inv_date").parent().addClass("has-error");
                        }
                    } else {
                        $("#customer").parent().parent().addClass("has-error");
                    }
                }
            });
        });
        $("#cancel_po_btn").click(function () {
            DJ.Confirm("Do You want to Cancel this Invoice?", function () {
                var id = $("#inv_id").val();
                var remarks = $("#remarks").val();
                DJ.enable_btn_fa("cancel_po_btn", "Cancelling");
                $.ajax({
                    url: "<?php echo base_url("invoice/cancel") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id, remarks: remarks},
                    success: function (data) {
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
        $("#finish_inv_btn").click(function () {
            if ($("#save_discount").is(":visible")) {
                DJ.Notify("Discount Amount has changed, but not Saved.<br/>Please Save before finish this Invoice", "warning");
            } else {
                DJ.Confirm("Do You want to Finish this Invoice?<br/><strong>Invoice will Finished as Full Payment Received.<strong>", function () {
                    var id = $("#inv_id").val();
                    var customer = $("#customer").val();
                    var inv_date = $("#inv_date").val();
                    var remarks = $("#remarks").val();
                    DJ.disable_btn_fa("finish_inv_btn", "Finishing");
                    var rows = $("#item_body").children();
                    if (rows.size() > 0) {
                        $.ajax({
                            url: "<?php echo base_url("invoice/finish") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {id: id, is_cash: 1, discount: $("#inv_discount").val(), customer: customer, inv_date: inv_date, remarks: remarks},
                            success: function (data) {
                                if (data.msg_type == "OK") {
                                    DJ.Notify(data.msg, "success");
                                    window.open(data.inv_print, 'InvoicePrint').focus();
                                    location.href = data.url;
                                } else {
                                    DJ.Notify(data.msg, "danger");
                                }
                            }
                        });
                    } else {
                        DJ.Notify("No Items for Save...", 'danger');
                    }
                });
            }
        });
        $("#save_discount").click(function () {
            DJ.disable_btn_fa("save_discount", "");
            $.ajax({
                url: "<?php echo base_url("invoice/save-discount") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {discount: $("#inv_discount").val(), id: $("#inv_id").val()},
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
        $("#finish_credit_inv_btn").click(function () {
            if ($("#save_discount").is(":visible")) {
                DJ.Notify("Discount Amount has changed, but not Saved.<br/>Please Save before finish this Invoice", "warning");
            } else {
                DJ.Overlay_input({
                    title: "Do You want to Finish this Invoice?",
                    type: "number",
                    greater_than: -1,
                    button: {
                        yes: {txt: "Proceed"},
                        no: {txt: "CANCEL"}
                    },
                    click: function (v) {
                        DJ.disable_btn_fa("finish_credit_inv_btn", "Finishing");
                        var id = $("#inv_id").val();
                        var customer = $("#customer").val();
                        var inv_date = $("#inv_date").val();
                        var remarks = $("#remarks").val();
                        $.ajax({
                            url: "<?php echo base_url("invoice/finish") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {id: id, payment: v, is_cash: 0, discount: $("#inv_discount").val(), customer: customer, inv_date: inv_date, remarks: remarks},
                            success: function (data) {
                                DJ.disable_btn("finish_inv_btn", "Finish Credit");
                                if (data.msg_type == "OK") {
                                    DJ.Notify(data.msg, "success");
                                    window.open(data.inv_print, 'InvoicePrint').focus()
                                    location.href = data.url;
                                } else {
                                    DJ.Notify(data.msg, "danger");
                                }
                            }
                        });
                    }
                });
            }
        });
        $("#pre-rec-items").click(function () {
            DJ.show_model()({
                title: "Select Pre Received Items",
                selector: "#",
                type: 'modal-lg',
                fade: ''
            });
        });
    });
    function calculate() {
        var total = 0;
        var trs1 = $("#item_body").children();
        for (var i = 0; i < $(trs1).size(); i++) {
            var tds = $(trs1[i]).children();
            total += Number(DJ.replace_coma($(tds[5]).html()));
        }
        console.log(DJ.format_number(total,2));
        $("#inv_sub_total").html(DJ.format_number(total,2));
        calculate_discount();
    }
    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var tot = Number(qty) * Number(rate);
        $("#itm_total").val(tot);
    }
    function calculate_discount() {
        var discount = $("#inv_discount").val();
        var sub_totla = DJ.replace_coma($("#inv_sub_total").html());
        var tot = Number(sub_totla) - Number(discount);
        $("#inv_total").html(DJ.format_number(tot,2));
    }

    function calculate_display_total() {
        var qty = $("#display_qty").val();
        var rate = $("#display_rate").val();
        var tot = Number(qty) * Number(rate);
        $("#display_total").val(tot);
    }

    function add_item() {
        var inv_id = $("#inv_id").val();
        var customer = $("#customer").val();
        var inv_date = $("#inv_date").val();
        var item = $("#itm_code").val();
        var qty = $("#qty").val();
        var do_number = $("#do_number").val();
        var rate = $("#rate").val();
        if (customer !== "") {
            $("#customer").parent().parent().removeClass("has-error");
            if (inv_date != "") {
                $("#inv_date").parent().removeClass("has-error");
                if (item != "") {
                    $("#itm_code").parent().parent().removeClass("has-error");
                    if (Number(qty) > 0) {
                        $("#qty").parent().removeClass("has-error");
                        DJ.disable_btn_fa("add_item_btn", "Saving")
                        $.ajax({
                            url: "<?php echo base_url("invoice/save_invoice") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                id: inv_id,
                                customer: customer,
                                inv_date: inv_date,
                                do_number: do_number,
                                item: item,
                                qty: qty,
                                rate: rate
                            },
                            success: function (data) {
                                DJ.enable_btn_fa("add_item_btn", "Add Item")
                                if (data.msg_type == "OK") {

                                    $("#doc_info b").html(data.inv_id_display);
                                    $("#doc_info").show();
                                    $("#inv_id").val(data.inv_id);
                                    add_row(data);
                                } else {
                                    DJ.Notify(data.msg, "danger");
                                }
                            }
                        });
                    } else {
                        $("#qty").parent().addClass("has-error");
                    }
                } else {
                    $("#itm_code").parent().parent().addClass("has-error");
                }

            } else {
                $("#inv_date").parent().addClass("has-error");
            }
        } else {
            $("#customer").parent().parent().addClass("has-error");
        }
    }
    function add_secondary_item(qty_display, rate_display, unit_measure) {
        var inv_id = $("#inv_id").val();
        var customer = $("#customer").val();
        var inv_date = $("#inv_date").val();
        var item = $("#itm_code").val();
        var qty = $("#qty").val();
        var rate = $("#rate").val();
//        var qty_display = $("#display_qty").val();
//        var rate_display = $("#display_rate").val();
//        var unit_measure = $("#display_unit").val();

        if (qty_display !== "") {
            $("#display_qty").parent().removeClass("has-error");
            if (rate_display != "") {
                $("#display_rate").parent().removeClass("has-error");
                DJ.disable_btn_fa("add_item_btn", "Saving")
                DJ.disable_btn_fa("save_secondary_item", "Saving")
                $.ajax({
                    url: "<?php echo base_url("invoice/save_invoice") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: inv_id,
                        customer: customer,
                        inv_date: inv_date,
                        item: item,
                        qty: qty,
                        rate: rate,
                        qty_display: qty_display,
                        rate_display: rate_display,
                        unit_measure: unit_measure,
                    },
                    success: function (data) {
                        DJ.enable_btn_fa("add_item_btn", "Add Item")
                        DJ.enable_btn_fa("save_secondary_item", "Add Item")
                        if (data.msg_type == "OK") {
                            DJ.close_model();
                            $("#doc_info b").html(data.inv_id_display);
                            $("#doc_info").show();
                            $("#inv_id").val(data.inv_id);
                            add_row(data);
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            } else {
                $("#inv_date").parent().addClass("has-error");
            }
        } else {
            $("#customer").parent().parent().addClass("has-error");
        }
    }

    function add_row(data) {
        DJ.Overlay_notify("Redirecting...","Redirecting you to Edit invoice page.","success");
        window.location.href = "<?php echo base_url("invoice/payments/")?>"+ data.inv_id_display;
        var tbody = $("#item_body");
        var tr = document.createElement("tr");
        var td1 = document.createElement("td");
        var td2 = document.createElement("td");
        var td3 = document.createElement("td");
        var td4 = document.createElement("td");
        var td5 = document.createElement("td");
        var td6 = document.createElement("td");
        var td7 = document.createElement("td");
        var item = $("#itm_code option:selected");
        $(td1).html($(tbody).children().size() + 1);
        $(td2).html($(item).data("subtext"));
        $(td3).html(data.display_name);
        $(td4).html(data.display_qty);
        $(td5).html((data.display_rate)).addClass("text-right");
        $(td6).html(data.display_total).addClass("text-right");
        var a = document.createElement("a");
        $(a).html('<i class="text-danger fa fa-times"></i>');
        $(a).click(function (e) {
            remove_item(data.inv_item_id, a, e)
        });
        $(td7).append(a);
        $(tr).append(td1, td2, td3, td4, td5, td6, td7).addClass("success");
        $(tbody).append(tr);
        setTimeout(function () {
            $(tr).removeClass("success");
        }, 1500);
        calculate();
        reset_form();
        $("#finish_inv_btn").show();
        $("#finish_credit_inv_btn").show();
        $("#cancel_po_btn").show();
    }

    function item_exists(itm_name) {
        var trs = $("#item_body").children();
        var boo = true;
        for (var i = 0; i < $(trs).size(); i++) {
            var td = $(trs[i]).children();
            if ($(td[1]).html() === itm_name) {
                boo = false;
                break;
            }
        }
        return boo;
    }
    function remove_item(id, ele, e) {
        e.preventDefault();
        DJ.Confirm("Are you want to remove this item from the Invoice?", function () {
            $.ajax({
                url: "<?php echo base_url("invoice/remove_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        $(ele).parent().parent().remove();
                        calculate();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }

    function reset_form() {
        $("#itm_code").val("").change();
        $("#qty").val("");
        $("#rate").val("");
        $("#display_qty").val("");
        $("#display_rate").val("");
        $("#display_unit").val("");
    }
</script>