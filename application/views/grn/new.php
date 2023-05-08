<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<title>New Good Receive Note</title>
<?php
?>
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
                                <label class="control-label">Supplier :</label>
                                <input type="hidden" value="" name="id" id="gr_id" />
                                <select class="form-control input-sm select-picker" name="supplier" id="supplier">
                                    <optgroup>
                                        <option value="-1">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($sups)) {
                                            foreach ($sups as $sup) {
                                                ?>
                                                <option data-subtext="<?php echo $sup->bis_type ?>" value="<?php echo $sup->id ?>"><?php echo $sup->company_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">GRN Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="po_date" id="po_date" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Reference :</label>
                                <input type="text" class="form-control input-sm" name="po_ref" id="po_ref" />
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Delivery Location :</label>
                                <input type="text" class="form-control input-sm" name="del_location" id="del_location" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Item :</label>
                                <select class="form-control input-sm select-picker" name="itm_code" id="itm_code">
                                    <optgroup>
                                        <option value="-1">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($items)) {
                                            foreach ($items as $item) {
                                                ?>
                                                <option data-subtext="<?php echo $item->itm_code ?>" data-unique="<?php echo $item->unique_type ?>" data-price="<?php echo $item->cost ?>" value="<?php echo $item->id ?>"><?php echo $item->itm_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="control-label">Quantity</label>
                                <input type="text" class="form-control input-sm number" name="qty" id="qty" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Rate :</label>
                                <input type="text" class="form-control input-sm number" name="rate" id="rate" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Total :</label>
                                <input type="text" class="form-control input-sm number" name="itm_total" id="itm_total" readonly=""/>
                            </div>
                            <div class="col-md-3 text-right">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="button" class="btn btn-sm btn-info" id="add_foc_btn"><i class="fa fa-share-alt"></i> <span>FOC</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                <button type="button" class="btn btn-sm btn-primary" id="add_item_btn"><i class="fa fa-plus"></i> <span>Add Item</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-scrollable">
                            <table class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <td>Item</td>
                                        <td>Quantity</td>
                                        <td>Rate</td>
                                        <td class="text-right">Total</td>
                                        <td></td>
                                    </tr>                
                                </thead>
                                <tr>
                                    <td colspan="5" class="text-muted bg-info">Current Items</td>
                                </tr>
                                <tbody id="item_body">

                                </tbody>
                                <tr>
                                    <td colspan="5" class="bg-success">Free of Charge Items</td>
                                </tr>
                                <tbody id="item_foc_body">

                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Subtotal</td>
                                        <td id="sub_total" class="text-strong text-right">0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Discount</td>
                                        <td class="text-strong text-right" style="width:150px;">
                                            <input type="text" onkeyup="calculate_discount();" class="form-control number text-right" id="discount" name="discount" value="0.00" />
                                        </td>
                                        <td style="width:40px;"><button type="button" class="btn btn-sm btn-primary hidden" id="save_discount"><i class="fa fa-save"></i><span></span></button></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td id="gr_total" class="text-strong text-right">0.00
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-6 hidden col-sm-12">
                            <div class="portlet">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-barcode"></i>Serial List</div>
                                    <div class="tools">
                                        <a href="javascript:;" class="expand font-blue-madison" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                                <tr>
                                                    <td>Item</td>
                                                    <td>Serial</td>
                                                </tr>                
                                            </thead>
                                            <tbody id="item_serial_body">

                                            </tbody>
                                        </table>`
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 text-right col-sm-12">
                            <button type="button" class="btn btn-success btn-sm" id="finish_grn_btn"><i class="fa fa-check"></i> <span>Finish</span></button>
                            <button type="button" class="btn btn-default" id="cancel_po_btn"><i class="fa fa-times"></i> <span>Cancel</span></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="serial_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<script>
    var serials = new Array();
    $(document).ready(function () {
        $("#finish_po_btn").hide();
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true,startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>'});
        $(".number").number(true, 2);
        $("#qty").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#rate").focus().select();
            }
        });
        $("#rate").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#add_item_btn").trigger("click");
            }
        });
        $("#itm_code").change(function () {
            var price = $("#itm_code option:selected").data("price");
            var unique = $("#itm_code option:selected").data("unique");
            $("#rate").val(price);
            if (unique == "1") {
                $("#qty").prop({disabled: true});
                $("#qty").val(0);
            } else {
                $("#qty").prop({disabled: false});
            }
        });

        $("#add_item_btn").click(function () {
            add_item(0)
        });
        $("#add_foc_btn").click(function () {
            add_item(1);
        });
        $("#cancel_po_btn").click(function () {
            DJ.Confirm("Do You want to Cancel this G R N?", function () {
                var id = $("#gr_id").val();
                $.ajax({
                    url: "<?php echo base_url("grn/cancel_grn") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
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
        $("#finish_grn_btn").click(function () {
            DJ.Confirm("Do You want to Finish this Goods Receive Note?<br/><small>Once you proceed, cannot undo this event.</small>", function () {
                var id = $("#gr_id").val();
                var supplier = $("#supplier").val();
                var po_date = $("#po_date").val();
                var po_ref = $("#po_ref").val();
                var del_location = $("#del_location").val();
                DJ.disable_btn_fa("finish_grn_btn", "Processing.");
                $.ajax({
                    url: "<?php echo base_url("grn/finish_grn") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id,
                        supplier: supplier,
                        grn_date: po_date,
                        po_ref: po_ref,
                        del_location: del_location
                    },
                    success: function (data) {
                        DJ.enable_btn("finish_grn_btn", "Finish");
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


        var _discount = 0;
        $("#discount").keypress(function () {
            _discount = $(this).val();
        });
        $("#discount").on("paste keyup", function () {
            var changed = $(this).val();
            if (Number(_discount) !== Number(changed)) {
                if ($("#gi_id").val() !== "") {
                    $("#save_discount").removeClass("hidden");
                }
            } else {
                $("#save_discount").addClass("hidden");
            }
            calculate_discount();
        });
        $("#save_discount").click(function () {
            DJ.disable_btn_fa("save_discount", "");
            $.ajax({
                url: "<?php echo base_url("grn/save-discount") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {discount: $("#discount").val(), id: $("#gr_id").val()},
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
    });
    function calculate() {
        var total = 0;
        var trs1 = $("#item_body").children();
        var trs2 = $("#pre_item_body").children();
        for (var i = 0; i < $(trs1).size(); i++) {
            var tds = $(trs1[i]).children();
            total += Number(DJ.replace_coma($(tds[3]).html()));
        }
        for (var i = 0; i < $(trs2).size(); i++) {
            var tds = $(trs2[i]).children();
            total += Number(DJ.replace_coma($(tds[3]).html()));
        }
        var discount = Number($("#discount").val());
        $("#sub_total").html(DJ.format_number(total));
        $("#gr_total").html(DJ.format_number(total - discount));
    }
    function open_qty_editor(ele, id, qty) {
        DJ.Overlay_input({
            title: "Enter new Quantity :",
            value: qty,
            type: "number",
            click: function (v) {
                $(ele).removeClass("fa-edit").addClass("fa-spin fa-spinner").css({"pointer-events": "none"});
                $.ajax({
                    url: "<?php echo base_url("grn/update-grn-qty") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id, qty: v},
                    success: function (data) {
                        $(ele).removeClass("fa-spin fa-spinner").addClass("fa-edit").css({"pointer-events": "auto"});
                        if (data.msg_type == "OK") {
                            $(ele).parent().find("span:first").html(v);
                            $(ele).closest("tr").find("td:nth(3)").html(data.new_price);
                            calculate();
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            }
        });
    }

    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var tot = Number(qty) * Number(rate);

        $("#itm_total").val(tot);
    }
    function remove_item_po(id) {
        DJ.Confirm("Do You want to Remove this item from the GRN?", function () {
            var tr = $("#grn_" + id);
            var ele = $(tr).find("i");
            $(ele).removeClass("fa-remove").addClass("fa-spinner fa-spin disabled");
            $.ajax({
                url: "<?php echo base_url("grn/remove_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        $(tr).hide(500, function () {
                            $(tr).remove()
                        });
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }

    function item_exists(itm_name, foc) {
        if (foc == 1) {
            var trs = $("#item_foc_body").children();
        } else {
            var trs = $("#item_body").children();
        }
        var boo = true;
        for (var i = 0; i < $(trs).size(); i++) {
            var td = $(trs[i]).children();
            if ($(td[0]).find("span").html() === itm_name) {
                boo = false;
                break;
            }
        }
        return boo;
    }
    function add_item(foc) {
//      Document Information
        var id = $("#gr_id").val();
        var supplier = $("#supplier").val();
        var po_date = $("#po_date").val();
        var po_ref = $("#po_ref").val();
        var del_location = $("#del_location").val();
//      Items Information
        var item = $("#itm_code").val();
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var itm_total = $("#itm_total").val();

        var item_ele = $("#itm_code option:selected");
        var itm_ok = item_exists($(item_ele).data("subtext"), foc);
        if (itm_ok) {
            DJ.disable_btn_fa("add_item_btn", "Processing");
            DJ.disable_btn_fa("add_foc_btn", "Processing");
            $.ajax({
                url: "<?php echo base_url("grn/new_grn_save") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    supplier: supplier,
                    grn_date: po_date,
                    po_ref: po_ref,
                    del_location: del_location,
                    item: item,
                    qty: qty,
                    rate: rate,
                    itm_total: itm_total,
                    foc: foc
                },
                success: function (data) {
                    DJ.enable_btn_fa("add_item_btn", "Add Item");
                    DJ.enable_btn_fa("add_foc_btn", "FOC");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        var p_id_display = data.p_id_display;
                        $("#doc_info b").html(p_id_display);
                        $("#gr_id").val(data.p_id);
                        $("#doc_info").show();
                        $("#finish_po_btn").show();
                        var item = $("#itm_code option:selected");
                        var tr = document.createElement("tr");
                        var td1 = document.createElement("td");
                        var span = document.createElement("span");
                        var td2 = document.createElement("td");
                        var edit_qty = document.createElement("i");
                        var td3 = document.createElement("td");
                        var td4 = document.createElement("td");
                        var td5 = document.createElement("td");

                        $(span).html($(item).data("subtext")).addClass("small text-muted");
                        $(td1).append($(item).html(), "&nbsp;&nbsp;", span);


                        $(edit_qty).addClass("fa fa-edit edit_qty_btn").click(function () {
                            open_qty_editor(edit_qty, data.pi_id, qty);
                        });
                        $(td2).html("<span>" + qty + "</span>");
                        if (foc == 1) {
                            $(td3).html(0.00);
                            $(td4).html(0.00).addClass("text-right");
                        } else {
                            $(td2).append(edit_qty);
                            $(td3).html(DJ.format_number(rate));
                            $(td4).html(DJ.format_number(itm_total)).addClass("text-right");
                        }

                        if (data.is_uni == "1") {
                            $(td5).append("&nbsp;");
                        }
                        $(tr).append(td1, td2, td3, td4, td5).attr({id: "grn_" + data.pi_id});
                        if (foc == 1) {
                            $("#item_foc_body").append(tr);
                        } else {
                            $("#item_body").append(tr);
                        }
                        $(tr).addClass("success");
                        calculate();
                        setTimeout(function () {
                            $(tr).removeClass("success");
                        }, 1000);
                        $("#itm_code").val("-1");
                        $("#itm_code").selectpicker('refresh')
                        $("#qty").val("");
                        $("#rate").val("");
                        $("#itm_total").val("");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        } else {
            DJ.Notify("Item Already Exists", "danger");
        }
    }
    function remove_pre_item_po(ele, id) {
        DJ.Confirm("Are you want to Remove this Item?", function () {
            $(ele).find("i").removeClass("fa-remove").addClass("fa-spinner fa-spin disabled");
            $.ajax({
                url: "<?php echo base_url("grn/remove_pre_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        $(ele).parent().parent().slideUp(500, function () {
                            $(ele).parent().parent().remove();
                            calculate();
                        });
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }

    function calculate_discount() {
        var discount = $("#discount").val();
        var sub_totla = DJ.replace_coma($("#sub_total").html());
        var tot = Number(sub_totla) - Number(discount);
        $("#gr_total").html(DJ.format_number(tot));
    }
</script>