<?php
//Aug 3, 2018 3:07:17 PM 
?>
<title>Adjustments</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
    
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <table class="table table-striped table-bordered table-hover" id="adj_table" 
                    data-toggle="table"
                    data-search="false"
                    data-pagination="true">
                    <caption>
                        <span class="pull-left">
                            <a href="#" id="download-adj" class="btn green-haze btn-outline btn-sm sbold uppercase"><i class="fa fa-download"></i>&nbsp;Download Adjustments</a>
                        </span>
                        <span class="pull-right">
                            <i class='fa fa-arrow-up text-primary'></i> Increase | <i class='fa fa-arrow-down text-warning'></i> Descrease
                        </span>
                    </caption>
                    <thead>
                        <tr>
                            <th>Item Code </th>
                            <th>Item Name</th>
                            <th>Direction</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                            <th>Date</th>
                            <th>User</th>
                        </tr>                
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if (count($adjustments) > 0) {
                            foreach ($adjustments as $adjust) {
                                ?>
                                <tr>
                                    <td><?php echo $adjust->itm_code ?></td>
                                    <td><?php echo $adjust->itm_name ?></td>
                                    <td><?php echo $adjust->direction == "1" ? "Increase <i class='fa fa-arrow-up text-primary'></i>" : "Descrease <i class='fa fa-arrow-down text-warning'></i>" ?></td>
                                    <td><?php echo $adjust->qty ?></td>
                                    <td><?php echo $adjust->remarks ?></td>
                                    <td><?php echo date("M d ,Y h:i a", strtotime($adjust->adj_time)) ?></td>
                                    <td><?php echo $adjust->username ?></td>
                                </tr>
                                <?php
                            }
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

<div id="download-modal" style="display:none">
    <div>
        <?php echo form_open(base_url("reporter/logs/get_adjustments"), "class='form-horizontal' method='GET'"); ?>
            <div class="form-group">
                <label for="first_name" class="col-lg-4 control-label">From</label>
                <div class="col-sm-6">
                    <input type="text" name="s"  class="form-control input-sm datepicker" />
                </div>
            </div>
            <div class="form-group">
                <label for="first_name" class="col-lg-4 control-label">To</label>
                <div class="col-sm-6">
                    <input type="text" name="e" class="form-control input-sm datepicker" value="" />
                </div>
            </div>
            <div class="form-group">
                <label for="first_name" class="col-lg-4 control-label">Item</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm select-picker" name="i" id="i">
                        <optgroup>
                            <option value="">--SELECT--</option>
                        </optgroup>
                        <optgroup>
                            <?php
                            if (isset($items)) {
                                foreach ($items as $item) {
                                    ?>
                                    <option 
                                        value="<?php echo $item->itm_id ?>">
                                            <?php echo $item->itm_name ." - ".$item->itm_code?>
                                    </option>
                                    <?php
                                }
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6 col-lg-offset-4">
                    <button class="btn btn-primary">Download</button>
                </div>
            </div>
        <?php echo form_close() ?>
    </div>
</div>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $(".number").number(true, 2);

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

        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, endDate: '<?php echo date("Y-m-d") ?>'});

        $("#download-adj").click(function (e) {
            e.preventDefault();
            DJ.show_model({
                title: "Download Logs",
                selector: "#download-modal",
                success:function(){

                }
            });
        });
    });
</script>

