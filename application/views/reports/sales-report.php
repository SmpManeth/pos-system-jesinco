<?php
//Sep 20, 2018 11:33:56 AM 
$date = date("Y-m-d");
$year = date("Y");
$months = get_months();
?>
<title>Reports</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>"
      rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <br/>
    <?php
    if (isset($report_name) && ($report_name == "all" || $report_name == "daily-sales-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/daily-sales-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Daily Sales Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <input type="text" class="form-control input-sm datepicker" placeholder="Select Date"
                                       name="d" id="d_sales_date" value="<?php echo date("Y-m-d") ?>"/>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "customer-list")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/customer-list"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Customer List</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <select name="s" id="s" class="form-control">
                                    <option value="all">--SELECT--</option>
                                    <option value="a">Approved</option>
                                    <option value="u">Un-Approved</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "customers")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/sales_transactions"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Sales person</label><br/>
                                <select name="sp" id="sp" class="form-control">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->emp_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From</label><br/>
                                <input type="text" name="from" class="form-control datepicker"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To`</label><br/>
                                <input type="text" name="to" class="form-control datepicker"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "do-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/do-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Sales Person</label><br/>
                                <select name="sp[]" id="sp" class="form-control input-sm" multiple size="9">
                                    <?php
                                    foreach ($sales_persons as $sp) {
                                        ?>
                                        <option value="<?php echo $sp->id ?>"><?php echo $sp->first_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "invoice-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/invoice-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Sales person</label><br/>
                                <select name="sp" id="sp" class="form-control">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->emp_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker" name="e"/>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "monthly-sales-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/monthly-sales-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Monthly Sales Summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Year</label><br/>
                                <select class="form-control" name="y">
                                    <?php
                                    $year = date("Y");
                                    ?>
                                    <option value="<?php echo $year ?>"><?php echo $year ?></option>
                                    <option value="<?php echo intval($year) - 1 ?>"><?php echo intval($year) - 1 ?></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Month</label><br/>
                                <select class="form-control" name="m">
                                    <?php
                                    $months = get_months();
                                    foreach ($months as $key => $value) {
                                        ?>
                                        <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Month</label><br/>
                                <select class="form-control" name="t">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "outstanding-statement-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/outstanding-statement-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Outstanding statement summary</p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "credit-bills")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/credit-invoice-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Credit Invoice Summary </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Collectors</label>
                                <select name="sp[]" id="sp" class="form-control input-sm" multiple size="9">
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->username ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label">OR</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Branches :</label>
                                    <select name="d[]" id="d" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($devisions as $dev) {
                                            ?>
                                            <option value="<?php echo $dev->id ?>"><?php echo $dev->devision . " ( " . $dev->branch_name . " )" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "due-bill-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/due-bill-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Due Bill Summary </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label">OR</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Devisions :</label>
                                    <select name="d[]" id="d" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($devisions as $dev) {
                                            ?>
                                            <option value="<?php echo $dev->id ?>"><?php echo $dev->devision . " ( " . $dev->branch_name . " )" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "live-due-bill-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/sales/live-due-bill-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Live Due Bill Summary </p>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label">OR</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Devisions :</label>
                                    <select name="d[]" id="d" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($devisions as $dev) {
                                            ?>
                                            <option value="<?php echo $dev->id ?>"><?php echo $dev->devision . " ( " . $dev->branch_name . " )" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "collection-bills")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/collection-bills"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Sales person</label><br/>
                                <select name="sp" id="sp" class="form-control input-sm ">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->username ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "forward-date-collection-bills")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/forward-date-collection-bills"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="control-label">OR</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Devision :</label>
                                    <select name="d[]" id="d" class="form-control input-sm" multiple size="9">
                                        <?php
                                        foreach ($devisions as $dev) {
                                            ?>
                                            <option value="<?php echo $dev->id ?>"><?php echo $dev->devision . " ( " . $dev->branch_name . " )" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "branch-vice-total-collection")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/branch-vice-total-collection"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Collection Bills </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "payment-complete-bills")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <p>Report generated on last payment date</p>
                        <?php echo form_open(base_url("reporter/newsales/payment-complete-bills"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Collection Bills </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "monthly-return-bills")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/monthly-return-bills"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <p>Report generated on last payment date</p>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Collection Bills </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "do-report")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/do-report"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Collection Bills </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label">Sales person</label><br/>
                                <select name="sp" id="sp" class="form-control input-sm ">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->username ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "return-do-report")) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/return-do-report"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">&nbsp;</label><br/>
                                <p class="form-control-static">Return DO Report </p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">From :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">To :</label>
                                <input type="text" class="form-control input-sm datepicker2"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="well" style="background:#f8f8f8">
                                <div class="col-md-2">
                                    <label class="control-label">Branches :</label>
                                    <select name="b[]" id="b" class="form-control input-sm">
                                        <?php
                                        foreach ($branches as $br) {
                                            ?>
                                            <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "bill-issue-summary")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/bill-issue-summary"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-2">
                                <label class="control-label">Sales Persons :</label>
                                <select name="sp" id="sp" class="form-control">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    foreach ($sales_persons as $sales_person) {
                                        ?>
                                        <option value="<?php echo $sales_person->id ?>"><?php echo $sales_person->first_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Branches :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "branch-vice-sold-items")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/branch-vice-sold-items"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branches :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "c24-report")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/c24-report"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branch :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "completed-invoices")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/completed-invoices"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branch :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "cancelled-invoices")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/cancelled-invoices"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branch :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if (isset($report_name) && ($report_name == "all" || $report_name == "cancelled-dos")) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <?php echo form_open(base_url("reporter/newsales/cancelled-dos"), "class='form-horizontal' method='GET' target='_blank'"); ?>
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="control-label">Branch :</label>
                                <select name="b" id="b" class="form-control input-sm">
                                    <?php
                                    foreach ($branches as $br) {
                                        ?>
                                        <option value="<?php echo $br->id ?>"><?php echo $br->branch_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Start :</label>
                                <input type="text" class="form-control input-sm datepicker" name="s"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">End :</label>
                                <input type="text" class="form-control input-sm datepicker"
                                       value="<?php echo date("Y-m-d") ?>" name="e"/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>
                                    <span>Download</span></button>
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
        $(".datepicker2").datepicker({format: "yyyy-mm-dd", autoclose: true});
        $("#d").change(function () {
            $("#b").val("");
        });
        $("#b").change(function () {
            $("#d").val("");
        });
    });
</script>