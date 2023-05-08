<?php
//Aug 2, 2018 6:58:55 PM 
?>
<title>Manage Stock Adjustments </title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_adjust_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label"></label>
                            <div class="col-md-10">
                                <p class="form-control input-sm-static">
                                    <label class="label label-success"><?php echo $item->itm_code ?></label>
                                    <label class="label label-info"><?php echo $item->itm_name ?></label>
                                    <label class="label label-primary"><?php echo $item->cat_name ?></label>
                                    <label class="label label-danger">Current Qty : <?php echo isset($item_qty) ? $item_qty->qty : 0 ?></label>
                                </p>
                            </div>
                        </div>
                        <?php
                        if (user_can($user, CAN_ADJUST_STOCK)) {
                            ?>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Direction :</label>
                                <div class="col-md-2">
                                    <select name="direction" class="form-control input-sm">
                                        <option value="1">Increase</option>
                                        <option value="-1">Decrease</option>
                                    </select>
                                </div>
                                <label class="col-md-1 control-label">Quantity:</label>
                                <div class="col-md-2">
                                    <input type="hidden" id="id" name="id" value="<?php echo $item->id ?>" />
                                    <input type="text" class="form-control input-sm number" placeholder="Adjusting Quantity " name="qty" id="qty">
                                </div>
                                <label class="col-md-1 control-label">Remark:</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control input-sm" placeholder="Adjusting Remarks " name="remark" id="remark">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn green btn-sm" id="adjust_item"><span>Submit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                    <button type="button" class="btn btn-sm blue" id="transfer_item"><i class="fa fa-exchange"></i> <span>Transfer</span></button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <hr/>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Direction</td>
                            <td>Quantity</td>
                            <td>Remark</td>
                            <td>Date</td>
                            <td>User</td>
                        </tr>                
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if (count($adjustments) > 0) {
                            foreach ($adjustments as $adjust) {
                                ?>
                                <tr>
                                    <td><?php echo $adjust->direction == "1" ? "Increase <i class='fa fa-arrow-up text-primary'></i>" : "Descrease <i class='fa fa-arrow-down text-warning'></i>" ?></td>
                                    <td><?php echo $adjust->qty ?></td>
                                    <td><?php echo $adjust->remarks ?></td>
                                    <td><?php echo date("M d , Y h:i a", strtotime($adjust->adj_time)) ?></td>
                                    <td><?php echo $adjust->first_name ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center"><em>No Adjustments</em></td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<div class="modal fade" id="cat_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body text-center">

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".number").number(true, 3);

        $("#adjust_item").click(function () {
            DJ.disable_btn("adjust_item", "Saving");
            $.ajax({
                url: "<?php echo base_url("stock/adjust-item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_adjust_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("adjust_item", "Submit");
                    if (data.msg_type === "OK") {
                        DJ.Notify(data.msg, "success");
                        window.location.reload();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#transfer_item").click(function (e) {
            DJ.load_to_model({
                title: "Transfer Details",
                url: "<?php echo base_url("stock/load_transfer_form"); ?>",
                data: {id: $("#id").val()},
                fade: "fade"
            });
        });
    });
</script>

