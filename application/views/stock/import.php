<?php
//Sep 10, 2018 9:01:40 AM 
?>
<title>Import Items</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open_multipart(base_url("stock/import"), "class='form-horizontal' id='new_adjust_form'"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select File :</label>
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
                    if (isset($total_array)) {
                        dump($total_array);
                    }
                    if (isset($repeated)) {
                        dump($repeated);
                    }
                    ?>
                    <hr/>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td><i class="fa fa-code"></i> Item Code</td>
                                <td><i class="fa fa-shopping-cart"></i> Item Name</td>
                                <td><i class="fa fa-ship"></i> Supplier</td>
                                <td><i class="fa fa-dollar"></i> Selling Price</td>
                                <td><i class="fa fa-dollar"></i> Quantity</td>
                            </tr>                
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>