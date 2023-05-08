<?php
//Aug 27, 2018 5:45:23 PM 
?>
<title>Edit Supplier Return</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='return_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Supplier :</label>
                                <input type="hidden" value="<?php echo $ret_note->id ?>" name="ret_id" id="ret_id" /><br/>
                                <p class="form-control-static"><?php echo $ret_note->company_name ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Return Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="ret_date" id="ret_date" value="<?php echo $ret_note->ret_date ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Return Reference :</label>
                                <input type="text" class="form-control input-sm" name="ret_ref" id="ret_ref" value="<?php echo $ret_note->ret_ref ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="control-label"><br/></label><br/>
                                <button type="button" class="btn btn-primary btn-sm" id="update_return"><i class="fa fa-bars"></i> <span>Update</span></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>G R N </td>
                                <td>Item Code</td>
                                <td>Item Name</td>
                                <td>Quantity</td>
                                <td>Rate</td>
                                <td>Total</td>
                                <td></td>
                            </tr>                
                        </thead>
                        <tbody id="tbody">
                            <?php
                            if (isset($ret_items)) {
                                $i = 0;
                                foreach ($ret_items as $rt_itm) {
                                    $i++;
                                    $total = doubleval($rt_itm->qty) * doubleval($rt_itm->rate);
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo decorate_code($rt_itm->grn_id, "grn", $this->prefixes) ?></td>
                                        <td><?php echo $rt_itm->itm_code ?></td>
                                        <td><?php echo $rt_itm->itm_name ?></td>
                                        <td class="text-center"><?php echo $rt_itm->qty ?></td>
                                        <td class="text-right"><?php echo number_format($rt_itm->rate, 2) ?></td>
                                        <td class="text-right"><?php echo number_format($total, 2) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-link" onclick="remove_item(<?php echo $rt_itm->id ?>, this)"><i class="fa fa-times text-danger"></i><span></span></button>
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
            <?php
            if (isset($ret_items) && count($ret_items) > 0) {
                ?>
                <div class="portlet light bordered text-right">
                    <button type="button" class="btn btn-sm btn-success" id="finish_ret"><i class="fa fa-check"></i> <span>Finish</span></button>
                    <button type="button" class="btn btn-sm btn-warning" id="cancel_ret"><i class="fa fa-ban"></i> <span>Cancel</span></button>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true,startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>'});
        $(".number").number(true, 2);
        $("#update_return").click(function () {
            DJ.disable_btn_fa("update_return", "Updating");
            $.ajax({
                url: "<?php echo base_url("grn/update_return_note") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#return_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("update_return", "Update");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#cancel_ret").click(function () {
            DJ.Overlay_confirm({
                title: "Are you want to Cancel this Return List?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        DJ.disable_btn_fa("cancel_ret", "Canceling");
                        $.ajax({
                            url: "<?php echo base_url("grn/update_ret_note_status") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {id: $("#ret_id").val(), status: 2},
                            success: function (data) {
                                DJ.enable_btn_fa("cancel_ret", "Cancel");
                                if (data.msg_type == "OK") {
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
        $("#finish_ret").click(function () {
            DJ.Overlay_confirm({
                title: "Are you want to Finish this Return List?",
                button: {
                    yes: {txt: "YES"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        DJ.disable_btn_fa("finish_ret", "Finishing");
                        $.ajax({
                            url: "<?php echo base_url("grn/update_ret_note_status") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: {id: $("#ret_id").val(), status: 1},
                            success: function (data) {
                                DJ.enable_btn_fa("finish_ret", "Finish");
                                if (data.msg_type == "OK") {
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

    function remove_item(id, ele) {
        DJ.Overlay_confirm({
            title: "Are you want to remove this item from the Return List?",
            button: {
                yes: {txt: "Remove"},
                no: {txt: "NO"}
            },
            click: function (v) {
                if (v) {
                    DJ.disable_ele_fa(ele, "");
                    $.ajax({
                        url: "<?php echo base_url("grn/remove_from_return") ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {id: id},
                        success: function (data) {
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                $(ele).parent().parent().hide(500, function () {
                                    $(ele).parent().parent().remove();
                                })
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                }
            }
        });
    }
</script>