<?php
//Sep 18, 2018 3:02:03 PM 
?>
<title>View Good Issue Note</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div>
                <h5 class="pull-left">System Date : &nbsp;<label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($gi_note->system_date)) ?></label></h5>
                <h5 class="pull-right">Goods Issue Note : &nbsp;<label class="alert alert-<?php echo $gi_note->status == "1" ? "success" : "danger" ?>"><?php echo $gi_note->status == "1" ? "Finished" : "Cancelled" ?></label></h5>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <input type="hidden" value="<?php echo $gi_note->id ?>" name="id" id="gi_id"/>
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Shop :</label>
                                <p class="text-strong"><?php echo $gi_note->branch_name ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Issue Date :</label>
                                <p class="text-strong"><?php echo $gi_note->issue_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Issue Reference :</label>
                                <p class="text-strong"><?php echo $gi_note->issue_ref ?></p>
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
                                            $qty = doubleval($gi_itm->qty);
                                            $rate = doubleval($gi_itm->rate);
                                            ?>
                                            <tr>
                                                <td><?php echo $gi_itm->itm_code ?></td>
                                                <td><?php echo $gi_itm->itm_name ?></td>
                                                <td class="text-center"><?php echo $qty ?></td>
                                                <td class="text-right"><?php echo $rate ?></td>
                                                <td class="text-right"><?php echo is_zero($qty * $rate) ?></td>
                                                <td><a href="#/" onclick="return_item_gi(<?php echo $gi_itm->id ?>, event, this)"><i class="fa fa-reply text-danger"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tr>
                                    <td colspan="6" class="bg-success" style="padding: 0px;padding-left: 10px;"><small>Free of Charge Items</small></td>
                                </tr>
                                <tbody id="item_foc_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-8 col-sm-12">
                            <legend>Returns</legend>
                            <?php
                            if (isset($returns) && count($returns) > 0) {
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Code</td>
                                            <td>Name</td>
                                            <td>Quantity</td>
                                            <td></td>
                                        </tr>                
                                    </thead>
                                    <tbody id="tbody">
                                        <?php
                                        foreach ($returns as $ret) {
                                            $stat_class = $ret->status == "2" ? "danger" : "";
                                            ?>
                                            <tr class="<?php echo $stat_class ?>">
                                                <td><?php echo $ret->itm_code ?></td>
                                                <td><?php echo $ret->itm_name ?></td>
                                                <td><?php echo $ret->qty ?></td>
                                                <td>
                                                    <?php
                                                    if ($ret->status == "1") {
                                                        ?>
                                                        <a href="#/" onclick="cancel_return(<?php echo $ret->id ?>, event, this)"><i class="fa fa-times text-danger"></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-lg-4 col-sm-12 text-right well">
                            <table class="table table-bordered">
                                <tbody id="tbody">
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-strong text-right"><?php echo $gi_note->sub_total ?></td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td class="text-strong text-right"><?php echo $gi_note->discount ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-strong text-right"><?php echo $gi_note->total ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-12 text-right col-sm-12">
                            <?php
                            if ($gi_note->status == "1") {
                                ?>
                                <a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url("goods-issue/print-note/" . decorate_code($gi_note->gi_id, "gi", $this->prefixes)) ?>"><i class="fa fa-print"></i> Print Invoice</a>
                                <button type="button" class="btn btn-sm btn-danger" id="cancel_gi_btn"><i class="fa fa-times"></i> Cancel</button>
                                <?php
                            }
                            ?>
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
        $("#cancel_gi_btn").click(function () {
            DJ.Confirm("Are you want to Cancel this Issue Note?", function () {
                $.ajax({
                    url: "<?php echo base_url("goods-issue/cancel-issue-note") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: $("#gi_id").val()},
                    success: function (data) {
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                            window.location.reload();
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            });
        });
    });
    function return_item_gi(id, e, ele) {
        e.preventDefault();
        DJ.Overlay_input({
            title: "Are you want to return this Item?",
            placeholder: "Return Quantity",
            type: "number",
            greater_than: 0,
            button: {
                yes: {txt: "RETURN"},
                no: {txt: "CANCEL"}
            },
            click: function (qty) {
                $(ele).find("i").removeClass("fa-reply").addClass("fa-spinner fa-spin");
                $.ajax({
                    url: "<?php echo base_url("goods-issue/return_item") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id, qty: qty},
                    success: function (data) {
                        if (data.msg_type == "OK") {
                            window.location.reload();
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            }
        });
    }

    function cancel_return(id, e, ele) {
        e.preventDefault();
        DJ.Confirm("Are you want to cancel this return Item?", function () {
            $(ele).find("i").removeClass("fa-times").addClass("fa-spinner fa-spin");
            $.ajax({
                url: "<?php echo base_url("goods-issue/cancel-return") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $(ele).find("i").addClass("fa-times").removeClass("fa-spinner fa-spin");
                    if (data.msg_type == "OK") {
                        window.location.reload();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>

