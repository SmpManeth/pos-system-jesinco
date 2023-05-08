<?php
//Sep 18, 2018 6:34:15 PM 
?>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("invoice/search-results", "class='form-horizontal' METHOD='GET'"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Item :</label>
                                <input type="text" class="form-control input-sm number" name="inv" placeholder="Invoice Number" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Date <span id="stock_qty"></span></label>
                                <input type="text" class="form-control input-sm datepicker" name="from" placeholder="Date From" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm datepicker" name="to" placeholder="Date to" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <div class="btn-group btn-group-sm">
                                    <button type="submit" id="add_item_btn" class="btn btn-primary"><i class="fa fa-search"></i> <span>Search</span></button>
                                </div>
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
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, endDate: '<?php echo date("Y-m-d") ?>'});
    });
</script>
