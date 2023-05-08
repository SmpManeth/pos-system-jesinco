<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<title>Edit Purchasing Order</title>
<?php
?>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-3">
                                <label class="control-label">Supplier :</label>
                                <input type="hidden" value="<?php echo $p_order->id ?>" name="id" id="po_id" />
                                <select class="form-control input-sm select-picker" name="supplier" id="supplier">
                                    <optgroup>
                                        <option value="-1">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($sups)) {
                                            foreach ($sups as $sup) {
                                                ?>
                                                <option <?php echo $p_order->supplier == $sup->id ? "selected" : ""; ?> data-subtext="<?php echo $sup->bis_type ?>" value="<?php echo $sup->id ?>"><?php echo $sup->company_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="po_date" id="po_date" value="<?php echo $p_order->p_date ?>"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Delivery Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="del_date" id="del_date" value="<?php echo $p_order->del_date ?>"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Reference :</label>
                                <input type="text" class="form-control input-sm" name="po_ref" id="po_ref" value="<?php echo $p_order->po_ref ?>"/>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Delivery Location :</label>
                                <input type="text" class="form-control input-sm" name="del_location" id="del_location" value="<?php echo $p_order->del_location ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
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
                                                <option data-subtext="<?php echo $item->itm_code . " (<b class='text-info'>$item->qty</b>)" ?>" data-price="<?php echo $item->cost ?>" value="<?php echo $item->item_id ?>"><?php echo $item->itm_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Quantity :</label>
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
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="button" class="btn btn-sm btn-primary" id="add_item_btn"><i class="fa fa-plus"></i> <span>Add Item</span></button>
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
                                <tbody id="item_body">
                                    <?php
                                    if (isset($p_items)) {
                                        foreach ($p_items as $p_item) {
                                            if ($p_item->is_temp != "1") {
                                                ?>
                                                <tr id="po_<?php echo $p_item->id ?>">
                                                    <td><?php echo $p_item->itm_name ?>&nbsp;&nbsp;<span class="small text-muted"><?php echo $p_item->itm_code ?></span></td>
                                                    <td><?php echo $p_item->qty ?></td>
                                                    <td><?php echo $p_item->price ?></td>
                                                    <td class="text-right"><?php echo is_zero(doubleval($p_item->price) * doubleval($p_item->qty)) ?></td>
                                                    <td><a onclick="remove_item_po(<?php echo $p_item->id ?>)"><i class="fa fa-remove font-red-thunderbird"></i></a></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td id="po_total" class="text-strong text-right"> <?php echo is_zero($p_order->total) ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12 text-right">

                                <button type="button" class="btn btn-sm btn-success" id="finish_po_btn" title="Finish the Purchasing Order."><i class="fa fa-check"></i> <span>Finish</span></button>
                                <button type="button" class="btn btn-sm btn-warning" title="Cancel the Purchasing Order" id="cancel_po_btn"><i class="fa fa-times"></i> Cancel</button>
                                <a href="<?php echo base_url("po") ?>" title="Save and Go Back" class="btn btn-sm default" id="cancel_po_btn"><i class="fa fa-arrow-left"></i> Go Back</a>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select-picker").selectpicker({showSubtext: true, style: "btn", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true,startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>'});
        $(".number").number(true, 3);

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
            $("#rate").val(price);
        });

        $("#add_item_btn").click(function () {
//            Document Information
            var id = $("#po_id").val();
            var supplier = $("#supplier").val();
            var po_date = $("#po_date").val();
            var del_date = $("#del_date").val();
            var po_ref = $("#po_ref").val();
            var del_location = $("#del_location").val();


//            Items Information
            var item = $("#itm_code").val();
            var qty = $("#qty").val();
            var rate = $("#rate").val();
            var itm_total = $("#itm_total").val();

            DJ.disable_btn_fa("add_item_btn", "Processing");
            $.ajax({
                url: "<?php echo base_url("po/new_order") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    supplier: supplier,
                    po_date: po_date,
                    del_date: del_date,
                    po_ref: po_ref,
                    del_location: del_location,
                    item: item,
                    qty: qty,
                    rate: rate
                },
                success: function (data) {
                    DJ.enable_btn_fa("add_item_btn", "Add Item");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        var p_id_display = data.p_id_display;
                        $("#doc_info b").html(p_id_display);
                        $("#po_id").val(data.p_id);
                        $("#doc_info").show();


                        var item = $("#itm_code option:selected");
                        var tr = document.createElement("tr");
                        var td1 = document.createElement("td");
                        var span = document.createElement("span");
                        var td2 = document.createElement("td");
                        var td3 = document.createElement("td");
                        var td4 = document.createElement("td");
                        var td5 = document.createElement("td");

                        var a = document.createElement("a");
                        $(a).html("<i class='fa fa-remove font-red-thunderbird'></i>").attr({href: "javascript:;"}).click(function () {
                            remove_item_po(data.pi_id);
                        });
                        $(span).html($(item).data("subtext")).addClass("small text-muted");
                        $(td1).append($(item).html(), "&nbsp;&nbsp;", span);
                        $(td2).html(qty);
                        $(td3).html(DJ.format_number(rate));
                        $(td4).html(DJ.format_number(itm_total)).addClass("text-right");
                        $(td5).append(a);

                        $(tr).append(td1, td2, td3, td4, td5).attr({id: "po_" + data.pi_id});
                        $("#item_body").append(tr);
                        $(tr).addClass("success");
                        calculate();
                        setTimeout(function () {
                            $(tr).removeClass("success");
                        }, 1000);
                        $("#itm_total").val(0);
                        $("#itm_code").val("-1").change();
                        $("#qty").val(0);
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#cancel_po_btn").click(function () {
            DJ.Confirm("Do You want to Finish this Purchasing Order?", function () {
                var id = $("#po_id").val();
                DJ.disable_btn_fa("cancel_po_btn", "Processing");
                $.ajax({
                    url: "<?php echo base_url("po/cancel_po") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        DJ.enable_btn_fa("cancel_po_btn", "Cancel");
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
        $("#finish_po_btn").click(function () {
            DJ.Confirm("Do You want to Finish this Purchasing Order?", function () {
                var id = $("#po_id").val();
                var supplier = $("#supplier").val();
                var po_date = $("#po_date").val();
                var del_date = $("#del_date").val();
                var po_ref = $("#po_ref").val();
                var del_location = $("#del_location").val();
                DJ.disable_btn_fa("finish_po_btn", "Processing");
                $.ajax({
                    url: "<?php echo base_url("po/finish_po") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id,
                        supplier: supplier,
                        po_date: po_date,
                        del_date: del_date,
                        po_ref: po_ref,
                        del_location: del_location
                    },
                    success: function (data) {
                        DJ.enable_btn_fa("finish_po_btn", "Finish");
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
    });

    function calculate() {
        var total = 0;
        var trs = $("#item_body").children();
        var trs2 = $("#pre_item_body").children();
        for (var i = 0; i < $(trs).size(); i++) {
            var tds = $(trs[i]).children();
            total += Number(DJ.replace_coma($(tds[3]).html()));
        }
        for (var i = 0; i < $(trs2).size(); i++) {
            var tds = $(trs2[i]).children();
            total += Number(DJ.replace_coma($(tds[3]).html()));
        }
        $("#po_total").html(DJ.format_number(total));
    }

    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var tot = Number(qty) * Number(rate);
        $("#itm_total").val(tot);
    }
    function remove_item_po(id) {
        var tr = $("#po_" + id);
        DJ.Confirm("Do You want to Remove this item?", function () {
            $.ajax({
                url: "<?php echo base_url("po/remove_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $(tr).removeClass("warning");
                    if (data.msg_type == "OK") {
                        $(tr).hide(500, function () {
                            $(tr).remove();
                        });
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        }, function () {
            $(tr).addClass("warning");
        }, function () {
            $(tr).removeClass("warning");
        });
    }
</script>