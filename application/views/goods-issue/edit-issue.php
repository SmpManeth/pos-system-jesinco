<?php
//Sep 18, 2018 10:04:22 AM 
?>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<title>Edit Good Issue Note</title>
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
                                <label class="control-label">Shop :</label>
                                <select class="form-control input-sm" name="shop" id="shop">
                                    <optgroup>
                                        <option value="">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($branches)) {
                                            foreach ($branches as $br) {
                                                ?>
                                                <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <input type="hidden" value="<?php echo $gi_note->gi_id ?>" name="id" id="gi_id" />

                            <div class="col-md-2">
                                <label class="control-label">Issue Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="po_date" id="po_date" value="<?php echo $gi_note->issue_date ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Issue Reference :</label>
                                <input type="text" class="form-control input-sm" name="po_ref" id="po_ref" value="<?php echo $gi_note->issue_ref ?>" />
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
                                                <option data-subtext="<?php echo $item->itm_code ?>" data-qty="<?php echo $item->qty ?>" data-price="<?php echo number_format($item->cost, 3, ".", "") ?>" value="<?php echo $item->id ?>"><?php echo $item->itm_name ?></option>
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
                                <button type="button" class="btn btn-sm btn-info" id="add_foc_btn"><i class="fa fa-share-alt"></i> <span>FOC</span></button>
                                <button type="button" class="btn btn-sm btn-primary" id="add_item_btn"><i class="fa fa-plus"></i> <span>Add Item</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-scrollable">
                            <table class="table table-striped table-bordered ">
                                <thead>
                                    <tr>
                                        <td>Code</td>
                                        <td>Item</td>
                                        <td class="text-center">Quantity</td>
                                        <td class="text-right">Rate</td>
                                        <td class="text-right">Total</td>
                                        <td></td>
                                    </tr>                
                                </thead>
                                <tbody id="item_body">
                                    <?php
                                    if (isset($gi_items)) {
                                        foreach ($gi_items as $gi_itm) {
                                            if ($gi_itm->foc == "0") {
                                                $qty = doubleval($gi_itm->qty);
                                                $rate = doubleval($gi_itm->rate);
                                                ?>
                                                <tr>
                                                    <td><?php echo $gi_itm->itm_code ?></td>
                                                    <td><?php echo $gi_itm->itm_name ?></td>
                                                    <td class="text-center"><?php echo $qty ?></td>
                                                    <td class="text-right"><?php echo $rate ?></td>
                                                    <td class="text-right"><?php echo is_zero($qty * $rate) ?></td>
                                                    <td><a href="#/" onclick="remove_item_gi(<?php echo $gi_itm->id ?>, event)"><i class="fa fa-remove text-danger"></i></a></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tr>
                                    <td colspan="6" class="bg-success" style="padding: 0px;padding-left: 10px;"><small>Free of Charge Items</small></td>
                                </tr>
                                <tbody id="item_foc_body">
                                    <?php
                                    if (isset($gi_items)) {
                                        foreach ($gi_items as $gi_itm) {
                                            if ($gi_itm->foc == "1") {
                                                $qty = doubleval($gi_itm->qty);
                                                $rate = doubleval($gi_itm->rate);
                                                ?>
                                                <tr id="gin_<?php echo $gi_itm->id ?>">
                                                    <td><?php echo $gi_itm->itm_code ?></td>
                                                    <td><?php echo $gi_itm->itm_name ?></td>
                                                    <td class="text-center"><?php echo $qty ?></td>
                                                    <td class="text-right"><?php echo $rate ?></td>
                                                    <td class=""><?php echo is_zero($qty * $rate) ?></td>
                                                    <td><a href="#/" onclick="remove_item_gi(<?php echo $gi_itm->id ?>, event, this)"><i class="fa fa-remove text-danger"></i></a></td>
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
                                        <td colspan="4" class="text-right">Subtotal</td>
                                        <td id="gi_sub_total" class="text-strong text-right"><?php echo $gi_note->sub_total ?></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right">Discount</td>
                                        <td class="text-strong text-right" style="width:150px;">
                                            <input type="text" onkeyup="calculate_discount();" class="form-control number text-right" id="discount" name="discount" value="<?php echo $gi_note->discount ?>" />
                                        </td>
                                        <td style="width:40px;"><button type="button" class="btn btn-sm btn-primary hidden" id="save_discount"><i class="fa fa-save"></i><span></span></button></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right">Total</td>
                                        <td id="gi_total" class="text-strong text-right"><?php echo $gi_note->total ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-12 text-right col-sm-12">
                            <button type="button" class="btn btn-sm green" id="finish_gi_btn"><i class="fa fa-check"></i> <span>Finish</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            <button type="button" class="btn btn-sm default" id="cancel_gi_btn">Cancel</button>
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
        $("#shop").val(<?php echo $gi_note->shop_id ?>);
        $("#supplier").val(<?php echo $gi_note->supplier ?>);

        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>', endDate: '<?php echo date("Y-m-d") ?>'});
        $("#shop").change(function () {
            var val = $(this).val();
            $("#sup_option").html("");
            if (val != "") {
                $("#sup_loading_icon").addClass("fa fa-spinner fa-spin");
                $.ajax({
                    url: "<?php echo base_url("goods-issue/get_branch_suppliers") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {shop: val},
                    success: function (data) {
                        $("#sup_loading_icon").removeClass("fa fa-spinner fa-spin");
                        $("#sup_option").html("");
                        if (data.msg_type == "OK") {
                            var option_grp = $("#sup_option");
                            for (var i = 0; i < data.suppliers.length; i++) {
                                var option = document.createElement("option");
                                $(option).val(data.suppliers[i].id);
                                $(option).html(data.suppliers[i].company_name);
                                $(option_grp).append(option);
                            }
                        }
                    }
                });
            }
        });
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
            var stock_qty = $("#itm_code option:selected").data("qty");
            $("#rate").val(price);
            if (stock_qty !== "" && typeof stock_qty != "undefined") {
                $("#stock_qty").html("( " + stock_qty + " max)");
            } else {
                $("#stock_qty").html("");
            }
            calculate_item();
        });
        $("#finish_gi_btn").click(function () {
            DJ.Confirm("Are you want to finish this Goods Issue Note?", function () {
                DJ.disable_btn_fa("finish_gi_btn", "Finishing");
                var id = $("#gi_id").val();
                var shop = $("#shop").val();
                var sup = $("#supplier").val();
                var ref = $("#po_ref").val();
                var date = $("#po_date").val();
                $.ajax({
                    url: "<?php echo base_url("goods-issue/finish") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: '<?php echo $gi_note->id ?>',
                        supplier: sup,
                        date: date,
                        ref: ref,
                        shop: shop
                    },
                    success: function (data) {
                        DJ.enable_btn_fa("finish_gi_btn", "Finish");
                        if (data.msg_type == "OK") {
                            window.location.href = data.url;
                        } else {
                            DJ.Notify(data.msg, "danger")
                        }
                    }
                });
            });
        });

        $("#add_item_btn").click(function () {
//            Validate Document Details
            var shop = $("#shop").val();
            var sup = $("#supplier").val();
            var date = $("#po_date").val();
            if (shop !== "") {
                $("#shop").parent().removeClass("has-error");
                if (sup !== "") {
                    $("#supplier").parent().removeClass("has-error");
                    if (date !== "") {
                        $("#po_date").parent().removeClass("has-error");
//                        Validate Item Details
                        var item = $("#itm_code").val();
                        var qty = $("#qty").val();
                        var rate = $("#rate").val();
                        if (item !== "") {
                            $("#itm_code").parent().parent().removeClass("has-error");
                            if (Number(qty) > 0) {
                                $("#qty").parent().removeClass("has-error");
                                if (Number(rate) > 0) {
                                    $("#rate").parent().removeClass("has-error");
                                    var itm_code = $("#itm_code option:selected").data("subtext");
                                    if (item_exists(itm_code, 0)) {
                                        add_item(0);
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
                        $("#po_date").parent().addClass("has-error");
                    }
                } else {
                    $("#supplier").parent().addClass("has-error");
                }
            } else {
                $("#shop").parent().addClass("has-error");
            }
        });
        $("#add_foc_btn").click(function () {
//            Validate Document Details
            var shop = $("#shop").val();
            var sup = $("#supplier").val();
            var date = $("#po_date").val();
            if (shop !== "") {
                $("#shop").parent().removeClass("has-error");
                if (sup !== "") {
                    $("#supplier").parent().removeClass("has-error");
                    if (date !== "") {
                        $("#po_date").parent().removeClass("has-error");
//                        Validate Item Details
                        var item = $("#itm_code").val();
                        var qty = $("#qty").val();
                        var rate = $("#rate").val();
                        if (item !== "") {
                            $("#itm_code").parent().parent().removeClass("has-error");
                            if (Number(qty) > 0) {
                                $("#qty").parent().removeClass("has-error");
                                if (Number(rate) > 0) {
                                    $("#rate").parent().removeClass("has-error");
                                    var itm_code = $("#itm_code option:selected").data("subtext");
                                    if (item_exists(itm_code, 1)) {
                                        add_item(1);
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
                        $("#po_date").parent().addClass("has-error");
                    }
                } else {
                    $("#supplier").parent().addClass("has-error");
                }
            } else {
                $("#shop").parent().addClass("has-error");
            }
        });
//        calculate();

        var _discount = <?php echo is_zero($gi_note->discount, 0) ?>;
//        $("#discount").keypress(function () {
//            _discount = $(this).val();
//        });
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
                url: "<?php echo base_url("goods-issue/save-discount") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {discount: $("#discount").val(), id: '<?php echo $gi_note->id ?>'},
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

        for (var i = 0; i < $(trs1).size(); i++) {
            var tds = $(trs1[i]).children();
            total += Number(DJ.replace_coma($(tds[4]).html()));
        }
        var dis = $("#discount").val();
        var sub = total - Number(dis);
        $("#gi_sub_total").html(DJ.format_number(total));
        $("#gi_total").html(DJ.format_number(sub));
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
                        $(ele).removeClass("fa-spin fa-spinner").addClass("fa-edit").css({"pointer-events": "alls"});
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
        $("#itm_total").val(DJ.format_number(tot));
    }
    function remove_item_gi(id, e, el) {
        e.preventDefault();
        DJ.Confirm("Do You want to Remove this item from the Goods Issue Note?", function () {
            var tr = $(el).parent().parent();
            var ele = $(tr).find("i");
            $(ele).removeClass("fa-remove").addClass("fa-spinner fa-spin disabled");
            $.ajax({
                url: "<?php echo base_url("goods-issue/remove_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        window.location.reload();
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
            if ($(td[0]).html() === itm_name) {
                boo = false;
                break;
            }
        }
        return boo;
    }
    function add_item(foc) {
//      Document Information
        var id = $("#gi_id").val();
        var shop = $("#shop").val();
        var sup = $("#supplier").val();
        var date = $("#po_date").val();
        var ref = $("#po_ref").val();
//      Items Information
        var item = $("#itm_code").val();
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var itm_total = $("#itm_total").val();

        var item_ele = $("#itm_code option:selected");
        var itm_ok = item_exists($(item_ele).data("subtext"), foc);
        if (itm_ok) {
            DJ.disable_btn_fa("add_item_btn", "Saving");
            DJ.disable_btn_fa("add_foc_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("goods-issue/new_gi_save") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    supplier: sup,
                    date: date,
                    ref: ref,
                    shop: shop,
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
                        window.location.reload();
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
        var sub_totla = DJ.replace_coma($("#gi_sub_total").html());
        var tot = Number(sub_totla) - Number(discount);
        $("#gi_total").html(DJ.format_number(tot));
    }
</script>

