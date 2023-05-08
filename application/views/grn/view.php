<title>View Good Receive Note</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div><h5 class="pull-left">System Date : &nbsp;<label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($grn->system_date)) ?></label></h5><h5 class="pull-right">G R N: &nbsp;<label class="alert alert-<?php echo $grn->status == "1" ? "success" : "danger" ?>"><?php echo $grn->status == "1" ? "Finished" : "Cancelled" ?></label></h5></div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
<!--                    <input type="hidden" value="" name="id" id="id" />-->
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Supplier :</label>
                                <p class="text-strong"><?php echo $grn->company_name ?></p>
                            </div>
                            <div class="col-itm_serialsmd-2">
                                <label class="control-label pull-left">GRN Date :</label>
                                <i class="fa fa-edit edit_qty_btn pull-right" id="edit_date_btn" data-id="<?php echo $grn->id ?>"></i>
                                <p class="text-strong pull-right" style="padding-top: 8px;" id="grn_date"><?php echo $grn->grn_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Reference :</label>
                                <p class="text-info text-strong"><?php echo $grn->po_ref ?></p>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Delivery Location :</label>
                                <p class="text-strong"><?php echo $grn->del_location ?></p>
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
                                    </tr>                
                                </thead>
                                <tr>
                                    <td colspan="5" class="bg-info">Current Items</td>
                                </tr>
                                <tbody id="item_body">
                                    <?php
                                    if ($g_items) {
                                        foreach ($g_items as $g_item) {
                                            if ($g_item->is_temp != "1" && $g_item->foc == "0") {
                                                ?>
                                                <tr id="grn_<?php echo $g_item->id ?>">
                                                    <td>
                                                        <?php echo $g_item->itm_name ?>&nbsp;&nbsp;<span class="small text-muted"><?php echo $g_item->itm_code ?></span>
                                                        <?php
                                                        if ($grn->status == "1") {
                                                            ?>
                                                            <a href="#/" class="pull-right " onclick="return_item(<?php echo $g_item->id ?>, this, event, 0,<?php echo $g_item->qty ?>)"><span class="fa fa-reply text-danger"></span></a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $g_item->qty ?></td>
                                                    <td><?php echo number_format($g_item->price,3) ?></td>
                                                    <td class="text-right"><?php echo is_zero(doubleval($g_item->price) * doubleval($g_item->qty)) ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tr>
                                    <td colspan="5" class="bg-success">Free of Charge Items</td>
                                </tr>
                                <tbody id="item_foc_body">
                                    <?php
                                    if ($g_items) {
                                        foreach ($g_items as $g_item) {
                                            if ($g_item->foc == "1") {
                                                ?>
                                                <tr id="grn_<?php $g_item->id ?>">
                                                    <td>
                                                        <?php echo $g_item->itm_name ?>&nbsp;&nbsp;<span class="small text-muted"><?php echo $g_item->itm_code ?></span>
                                                        <a href="#/" class="pull-right" onclick="return_item(<?php echo $g_item->id ?>, this, event, 1,<?php echo $g_item->qty ?>)"><span class="fa fa-reply text-danger"></span></a>
                                                    </td>
                                                    <td><span><?php echo $g_item->qty ?></span></td>
                                                    <td><?php echo number_format($g_item->price,3) ?></td>
                                                    <td class="text-right"><?php echo "0.00" ?></td>
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
                                        <td colspan="3" class="text-right">Sub-Total</td>
                                        <td id="po_total" class="text-strong text-right"><?php echo is_zero($grn->sub_total) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Discount</td>
                                        <td id="po_total" class="text-strong text-right"><?php echo number_format($grn->discount, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td id="po_total" class="text-strong text-right"><?php echo is_zero($grn->total) ?></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="row well">
                        <div class="col-lg-6 col-sm-12 hidden">
                        </div>
                        <div class="col-lg-12 text-right col-sm-12">
                            <?php
                            if ($grn->status == "1") {
                                ?>
                                <a class="btn btn-success btn-sm" href="<?php echo base_url("grn/print-grn/" . $doc_id) ?>"><i class="fa fa-print"></i>&nbsp;&nbsp;<span>Print</span></a>
                                <?php
                            }
                            if ($grn->status == "2" || $grn->status == "1") {
                                ?>
                                <!-- <button type="button" class="btn btn-sm btn-warning" id="mk_pending"><i class="fa fa-retweet"></i>&nbsp;&nbsp;<span>Mark as Pending</span></button> -->
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
    <link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
</div>
<script>
                                                $(document).ready(function () {
                                                    $("#edit_date_btn").click(function () {
                                                        DJ.Overlay_input({
                                                            title: "Enter New Date",
                                                            value: $("#grn_date").html(),
                                                            type: "date",
                                                            options: {format: "yyyy-mm-dd", autoclose: true, startDate: '<?php echo date_plaus_days(date("Y-m-d"), 30, "-") ?>', endDate: '<?php echo date("Y-m-d") ?>'},
                                                            placeholder: "Invoice Date",
                                                            button: {
                                                                yes: {txt: "OK"},
                                                                no: {txt: "CANCEL"}
                                                            },
                                                            click: function (v) {
                                                                $.ajax({
                                                                    url: "<?php echo base_url("grn/save-grn-date") ?>",
                                                                    type: 'POST',
                                                                    dataType: 'JSON',
                                                                    data: {id: $("#edit_date_btn").data("id"), grn_date: v},
                                                                    success: function (data) {
                                                                        if (data.msg_type == "OK") {
                                                                            $("#grn_date").html(data.date);
                                                                            DJ.Notify(data.msg, "success");
                                                                        } else {
                                                                            DJ.Notify(data.msg, "danger");
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    });
                                                    $("#mk_pending").click(function () {
                                                        DJ.Overlay_confirm({
                                                            title: "Are you wanto mark this G R N as Pending?",
                                                            button: {
                                                                yes: {txt: "YES"},
                                                                no: {txt: "NO"}
                                                            },
                                                            click: function (v) {
                                                                if (v) {
                                                                    $.ajax({
                                                                        url: "<?php echo base_url("grn/mark-as-pending") ?>",
                                                                        type: 'POST',
                                                                        dataType: 'JSON',
                                                                        data: {id:<?php echo $grn->id ?>},
                                                                        success: function (data) {
                                                                            if (data.msg_type == "OK") {
                                                                                DJ.Notify(data.msg, "success");
                                                                                window.location.href = data.url;
                                                                            } else {
                                                                                DJ.Notify(data.msg, "danger");
                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                                function return_item(id, ele, e, foc, max) {
                                                    e.preventDefault();
                                                    DJ.load_to_model({
                                                        title: "Currently Open Supplier Notes",
                                                        url: "<?php echo base_url("grn/get_open_grn_returns/" . $grn->id) ?>",
                                                        fade: "fade",
                                                        success: function () {
                                                            item = {
                                                                id: id,
                                                                grn: "<?php echo $grn->id ?>",
                                                                foc: foc,
                                                                max: max
                                                            };
                                                            $(".modal").find("thead td:nth(4)").html("Max : " + max).addClass("text-info");
                                                            $(".modal").find("input").attr({max: max});
                                                        }
                                                    });
                                                }

                                                var item = {};
</script>