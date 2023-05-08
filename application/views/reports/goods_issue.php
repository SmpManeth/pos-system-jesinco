<?php
//Sep 20, 2018 11:34:04 AM 
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
    if (isset($report_name) && ($report_name == "all" || $report_name == "goods-issue-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/goods-issue/summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Goods Issue Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" required="" value="<?php echo date("Y-m-d") ?>" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker" value="<?php echo date("Y-m-d") ?>" name="e" />
                            </div>
                            <div class="col-md-3">
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
    if (isset($report_name) && ($report_name == "all" || $report_name == "goods-issue-category-vice")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/goods-issue/category-vice-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Goods Issue Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Category :</label>
                                <select class="form-control" name="c">
                                    <option>--SELECT--</option>
                                    <?php
                                    if (isset($categories)) {
                                        foreach ($categories as $category) {
                                            ?>
                                            <option value="<?php echo $category->id ?>"><?php echo $category->cat_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" required="" value="<?php echo date("Y-m-d") ?>" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker" value="<?php echo date("Y-m-d") ?>" name="e" />
                            </div>
                            <div class="col-md-3">
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