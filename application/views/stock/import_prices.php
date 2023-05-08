<?php
//Sep 17, 2018 3:05:03 PM 
?>
<title>Import Items</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open_multipart(base_url("stock/import_prices"), "class='form-horizontal' id='new_adjust_form'"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select File to Import :</label>
                            <div class="col-md-3">
                                <input type="file" name="userfile" id="userfile" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary" id="adjust_item"><span>Submit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($status)) {
                        echo $msg;
                    }
                    if (isset($updated)) {
                        echo "<br/>".($updated). " Row(s) Updated";
                    }
                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>