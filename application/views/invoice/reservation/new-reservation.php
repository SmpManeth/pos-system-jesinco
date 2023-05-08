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
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<title>New Invoice</title>
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
                                                <option data-subtext="<?php echo $cus->devision ?>" value="<?php echo $cus->id ?>"><?php echo $cus->customer_prefix . " " . $cus->customer_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Delivery Date :</label>
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
                                        <td colspan="2" class="text-right">Total</td>
                                        <td id="inv_total" class="text-strong text-right">0.00</td>
                                        <td></td>
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
<script>
    var serials = new Array();
    $(document).ready(function () {
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, endDate: '<?php echo date_plaus_days(date("Y-m-d"), 30, "+") ?>', startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>'});
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
                add_item(0);
            } else {
                DJ.Notify("Item Already Exists", "danger");
            }
        });
        $("#rate").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#add_item_btn").trigger("click");
            }
        });
    });

    function add_item() {
        var inv_id = $("#inv_id").val();
        var customer = $("#customer").val();
        var inv_date = $("#inv_date").val();
        var item = $("#itm_code").val();
        var do_number = $("#do_number").val();
        var qty = $("#qty").val();
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
                                    DJ.Overlay_notify("Redirecting...", "Redirecting you to Delivery Note Edit page.", "success");
                                    window.location.href = "<?php echo base_url("reservation/edit/") ?>" + data.inv_id_display;
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
    function calculate() {
        var total = 0;
        var trs1 = $("#item_body").children();

        for (var i = 0; i < $(trs1).size(); i++) {
            var tds = $(trs1[i]).children();
            total += Number(DJ.replace_coma($(tds[5]).html()));
        }
        $("#inv_sub_total").html(DJ.format_number(total, 2));
        calculate_discount();
    }
    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var tot = Number(qty) * Number(rate);
        $("#itm_total").val(tot);
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