<title>View Purchasing Order</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div>
                <h5 class="pull-left"> &nbsp; &nbsp;System Date : &nbsp;<label class="alert alert-info"><?php echo date("M d, Y h:i a", strtotime($p_order->system_date)) ?></label></h5>
                <h5 class="pull-right">Purchasing Order : &nbsp;<label class="alert alert-<?php echo $p_order->status == "1" ? "success" : "danger" ?>"><?php echo $p_order->status == "1" ? "Finished" : "Cancelled" ?></label></h5></div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_site_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <input type="hidden"  name="po_id" id="po_id" value="<?php echo $p_order->id ?>"/>
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-3">
                                <label class="control-label">Supplier </label><br/>
                                <p class="form-control input-sm-static text-info"><?php echo $p_order->company_name ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Date</label><br/>
                                <p class="form-control input-sm-static text-info"><?php echo $p_order->p_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Delivery Date</label><br/>
                                <p class="form-control input-sm-static text-info"><?php echo $p_order->del_date ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">PO Reference</label><br/>
                                <p class="form-control input-sm-static text-info"><?php echo $p_order->po_ref ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Delivery Location</label><br/>
                                <p class="form-control input-sm-static text-info"><?php echo $p_order->del_location ?></p>
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
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr class="info">
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">Total</td>
                                        <td id="po_total" class="text-strong text-right"> <?php echo is_zero($p_order->total) ?></td>
                                    </tr>
                                </tfoot>
                            </table>`
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <?php
                                if ($p_order->status == "2") {
                                    ?>
                                    <button type="button" class="btn green" id="pending_po_btn"><span>Mark as Pending</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                    <?php
                                }
                                if ($p_order->status == "1") {
                                    ?>
                                    <button type="button" class="btn btn-sm btn-success" id="create_grn"><i class="fa fa-list"></i>&nbsp;<span>Create GRN</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                    <button type="button" class="btn btn-sm btn-warning" id="pending_po_btn"><i class="fa fa-edit"></i>&nbsp;<span>Mark as Pending</span></button>
                                    <a class="btn btn-sm btn-primary pull-left" href="<?php echo base_url("po/print-po/" . $doc_id) ?>"><i class="fa fa-print"></i>&nbsp;<span>Print</span></a>
                                    <?php
                                }
                                ?>
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
        $("#pending_po_btn").click(function () {
            DJ.Confirm("Do You want to Mark this Purchasing Order as Pending?", function () {
                var id = $("#po_id").val();
                DJ.disable_btn("pending_po_btn", "Processing");
                $.ajax({
                    url: "<?php echo base_url("po/pending_po") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        DJ.enable_btn("pending_po_btn", "Mark as Pending");
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
        $("#create_grn").click(function () {
            DJ.Confirm("Are you want to Create a GRN From this Purchasing Order?", function () {
                DJ.disable_btn("create_grn", "Creating GRN");
                var id = $("#po_id").val();
                $.ajax({
                    url: "<?php echo base_url("po/create_grn") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    success: function (data) {
                        DJ.enable_btn("create_grn", "Create GRN");
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
</script>