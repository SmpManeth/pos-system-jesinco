<?php
//Oct 2, 2018 5:22:25 PM 
$year = date("Y");
$months = get_months();
$date = date("Y-m-d");
?>

<title>Reports</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>

<div class="page-content-col">
    <br/>
    
    
    

    
    
    <!--<div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
    <?php echo form_open(base_url("reporter/sales/daily-sale-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label class="control-label">&nbsp;</label><br/>
                            <p class="form-control-static">Sales Summary </p>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Start :</label>
                            <input type="text" class="form-control input-sm datepicker" value="<?php echo date("Y-m-d") ?>" name="s" />
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">End :</label>
                            <input type="text" class="form-control input-sm datepicker" value="<?php echo date("Y-m-d") ?>" name="e" />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">&nbsp;</label><br/>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                        </div>
                    </div>
    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div> -->
    
    
    
    <!--<div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
    <?php echo form_open(base_url("reporter/stock/stock-bin-card"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label class="control-label">&nbsp;</label><br/>
                            <p class="form-control-static">Stock Bin Card</p>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Start Date :</label>
                            <input type="text" class="form-control input-sm datepicker" name="s" />
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">End Date :</label>
                            <input type="text" class="form-control input-sm" readonly name="e" value="<?php echo $date ?>" />
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">&nbsp;</label><br/>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                        </div>
                    </div>
    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div> -->

    
    

    <!--    <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
    <?php echo form_open(base_url("reporter/purchasing/good-returned-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Good Returned Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e" />
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                            </div>
                        </div>
    <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
    <?php echo form_open(base_url("reporter/purchasing/total-purchasing-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Total Purchasing Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e" />
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                            </div>
                        </div>
    <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>-->
</div>

<script>
    $(document).ready(function () {
        $(".datepicker").datepicker({format: "yyyy-mm-dd", endDate: '<?php echo $date ?>', autoclose: true});
    });
</script>
