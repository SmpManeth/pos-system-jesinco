<?php
//Sep 21, 2018 4:30:27 PM 
?>

<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<title>New Invoice</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_inbound_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
<!--                    <input type="hidden" value="" name="id" id="id" />-->
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Item : <span id="stock_qty"></span></label>
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
                                                    data-cost="<?php echo $item->cost ?>" 
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
                            <div class="col-md-1">
                                <label class="control-label">Quantity</label>
                                <input type="text" class="form-control input-sm number" name="qty" id="qty" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label"><span>Cost : </span></label>
                                <input type="text" class="form-control input-sm number" readonly name="cost" id="cost" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Total :</label>
                                <input type="text" class="form-control input-sm number" name="itm_total" id="itm_total" readonly=""/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Invoice Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="inv_date" id="inv_date" />
                            </div>
                            <div class="col-md-1 text-right">
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
                                        <td>Ref</td>
                                        <td>Date</td>
                                        <td>Item</td>
                                        <td class="text-right">Quantity</td>
                                        <td class="text-right">Rate</td>
                                        <td class="text-right">Total</td>
                                        <td></td>
                                    </tr>                
                                </thead>
                                <tbody id="item_body">
                                    <?php
                                    if (isset($inbound_items)) {
                                        foreach ($inbound_items as $inb_item) {
                                            $qty = doubleval($inb_item->qty);
                                            $rate = doubleval($inb_item->rate);
                                            $status_cls = $inb_item->status == "2" ? "danger" : ""
                                            ?>
                                            <tr class="<?php echo $status_cls ?>">
                                                <td>
                                                    #<?php echo $inb_item->id ?>

                                                </td>
                                                <td>
                                                    <span><?php echo $inb_item->use_date ?></span><br/>
                                                    <small  class="text-primary"><?php echo $inb_item->system_date ?></small>
                                                </td>
                                                <td>
                                                    <span><?php echo $inb_item->itm_name ?></span><br/>
                                                    <strong  class="text-primary"><?php echo $inb_item->itm_code ?></strong>
                                                </td>
                                                <td>
                                                    <?php echo $qty ?>
                                                </td>
                                                <td>
                                                    <?php echo is_zero($rate) ?>
                                                </td>
                                                <td>
                                                    <?php echo is_zero($rate * $qty) ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($inb_item->status == "1") {

                                                        if ($user->user_type == "admin" || $user->user_type == "superadmin") {
                                                            ?>
                                                            <button type="button" class="btn btn-link btn-xs" onclick="cancel_inbound_item(<?php echo $inb_item->id ?>, this)"><i class="fa fa-times text-danger"></i></button>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
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


        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>', endDate: '<?php echo date("Y-m-d") ?>'});
        $(".number").number(true, 3);
        $("#qty").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#rate").focus().select();
            }
        });
        $("#itm_code").change(function () {
            var price = $("#itm_code option:selected").data("cost");
            var stock_qty = $("#itm_code option:selected").data("stock");
            $("#cost").val(price);
            if (stock_qty !== "" && typeof stock_qty != "undefined") {
                $("#stock_qty").html("( " + stock_qty + " max)");
            } else {
                $("#stock_qty").html("");
            }
            calculate_item();
        });

        $("#add_item_btn").click(function () {
            var inv_date = $("#inv_date").val();
            var item = $("#itm_code").val();
            var qty = $("#qty").val();
            var rate = $("#rate").val();
            if (inv_date != "") {
                $("#inv_date").parent().removeClass("has-error");
                if (item != "") {
                    $("#itm_code").parent().parent().removeClass("has-error");
                    if (Number(qty) > 0) {
                        $("#qty").parent().removeClass("has-error");
                        DJ.disable_btn_fa("add_item_btn", "Saving")
                        $.ajax({
                            url: "<?php echo base_url("invoice/save_inbound_item") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                inv_date: inv_date,
                                item: item,
                                itm_code: item,
                                qty: qty,
                                rate: rate
                            },
                            success: function (data) {
                                DJ.enable_btn_fa("add_item_btn", "Add Item")
                                if (data.msg_type == "OK") {
                                    $("#item_body").prepend(data.row);
                                    $("#new_inbound_form").trigger("reset");
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
        });

    });

    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#cost").val();
        var tot = Number(qty) * Number(rate);
        $("#itm_total").val(tot);
    }
    function cancel_inbound_item(id, ele) {
        DJ.Confirm("Are you want to Cancel the Issue?", function () {
            $.ajax({
                url: "<?php echo base_url("invoice/cancel_inbound_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $(ele).parent().parent().addClass("danger");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>