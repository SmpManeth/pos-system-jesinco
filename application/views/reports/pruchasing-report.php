<?php
//Sep 20, 2018 11:33:47 AM 
$date = date("Y-m-d");
$year = date("Y");
$months = get_months();
?>

<title>Reports</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <br/>
    <?php
    if (isset($report_name) && ($report_name == "all" || $report_name == "purchasing-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/purchasing/daily-purchasing-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Purchasing Summary -Supplier Vice</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <select class="form-control" name="sup">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    if (isset($suppliers)) {
                                        foreach ($suppliers as $sup) {
                                            ?>
                                            <option value="<?php echo $sup->id ?>"><?php echo $sup->company_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <input type="text" class="form-control input-sm datepicker" placeholder="Select Date" name="s" id="d_sales_date" value="<?php echo date("Y-m-d") ?>"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <input type="text" class="form-control input-sm datepicker" placeholder="Select Date" name="e" id="d_sales_date" value="<?php echo date("Y-m-d") ?>"/>
                            </div>
                            <div class="col-md-2">
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
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/purchasing/purchasing-summary-supplier"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Purchasing Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <input type="text" class="form-control input-sm datepicker" placeholder="Select Date" name="s" id="d_sales_date" value="<?php echo date("Y-m-d") ?>"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <input type="text" class="form-control input-sm datepicker" placeholder="Select Date" name="e" id="d_sales_date" value="<?php echo date("Y-m-d") ?>"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "good-returned-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/purchasing/good-returned-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Good Returned Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e" value="<?php echo $date ?>" />
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
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "total-purchasing-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/purchasing/total-purchasing-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <p class="form-control-static">Total Purchasing Summary</p>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branches :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                        foreach($branches as $br){
                                            ?>
                                            <option value="<?php echo $br->id?>"><?php echo $br->branch_name?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e"  value="<?php echo $date ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "purchasing-order-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/purchasing/purchasing-order-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <p class="form-control-static">Purchasing Order Summary</p>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branches :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                        foreach($branches as $br){
                                            ?>
                                            <option value="<?php echo $br->id?>"><?php echo $br->branch_name?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e"  value="<?php echo $date ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<script>
    $(document).ready(function () {
        $(".datepicker").datepicker({format: "yyyy-mm-dd", endDate: '<?php echo date("Y-m-d") ?>', autoclose: true});
    });
</script>