<?php
//Aug 29, 2018 5:09:41 PM 
$date = date("Y-m-d");
$year = date("Y");
$months = get_months();
?>
<title>Reports</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<div class="page-content-col">
    <br/>
    <?php
    if (isset($report_name) && ($report_name == "all" || $report_name == "stock-movements")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/stock/stock-movements"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Stock Movement Report </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e" />
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b" id="b" class="form-control input-sm" >
                                        <?php
                                            foreach($branches as $br){
                                                ?>
                                                <option value="<?php echo $br->id?>"><?php echo $br->branch_name?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
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
    if (isset($report_name) && ($report_name == "all" || $report_name == "current-stock-report")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/stock/current"), "class='form-horizontal' method='GET'  target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Current Stock Report</p>
                            </div>
                            <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm" multiple  size="9">
                                        <?php
                                            foreach($branches as $br){
                                                ?>
                                                <option value="<?php echo $br->id?>"><?php echo $br->branch_name?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            <div class="col-md-4 text-right">
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
                        <?php echo form_open(base_url("reporter/stock/item-registration"), "class='form-horizontal' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Item Registration</p>
                            </div>
                            <div class="col-md-4 text-right">
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

    if (isset($report_name) && ($report_name == "all" || $report_name == "items-sales-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/stock/item-sales-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Items Sales Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <select class="form-control input-sm select-picker" name="i" id="i">
                                    <optgroup>
                                        <?php
                                        if (isset($items)) {
                                            foreach ($items as $item) {
                                                ?>
                                                <option 
                                                    data-subtext="<?php echo $item->itm_code ?>" 
                                                    data-price="<?php echo $item->selling ?>" 
                                                    value="<?php echo $item->id ?>">
                                                        <?php echo $item->itm_name ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" value="<?php echo $date ?>" name="s" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm" readonly value="<?php echo $date ?>" name="e" />
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
    if (isset($report_name) && ($report_name == "all" || $report_name == "items-category-vice")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/stock/items-category-vice"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Stock Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Category</label><br/>
                                <select class="form-control" name="c" id="c">
                                    <option value="">--SELECT--</option>
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
                            <div class="col-md-3">
                                <label class="control-label">Sub-Category</label><br/>
                                <div class="input-group">

                                    <select class="form-control" name="sc" id="sub_c">
                                        <optgroup>
                                            <option value="">--SELECT--</option>
                                        </optgroup>
                                        <optgroup>

                                        </optgroup>
                                    </select>
                                    <span class="input-group-addon" style="background: transparent;border: none">
                                        <i class="fa fa-spin fa-spinner pull-left hidden" id="loader"></i>
                                    </span>
                                </div>
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
        $(".datepicker").datepicker({format: "yyyy-mm-dd", endDate: '<?php echo $date ?>', autoclose: true});
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});

        $("#c").change(function () {
            var val = $(this).val();
            if (val) {
                $("#loader").removeClass("hidden");
                $.post("<?php echo base_url("register_c/get_sub_categories") ?>", {cat_id: val}, function (data) {
                    $("#loader").addClass("hidden");
                    $("#sub_c").html
                    $.each(data.subs, function (i, row) {
                        var option = document.createElement("option");
                        $(option).html(row.sub_name).val(row.id);
                        $("#sub_c optgroup:last").append(option);
                    });
                }, "JSON");
            }
        });
    });
</script>