<?php
//Oct 30, 2018 10:39:08 AM 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="page-content-col">
    <br/>
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open_multipart(base_url("stock/rectify_stock"), "class='form-horizontal'"); ?>
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <p class="form-control-static">Select File</p>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <input type="file" name="userfile" />
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">&nbsp;</label><br/>
                            <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> <span>Upload</span></button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($data) && !empty($data)) {
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>Code</td>
                            <td>Name</td>
                            <td>Qty</td>
                        </tr>                
                    </thead>
                    <tbody id="tbody">
                        <?php
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row[0]->itm_code ?></td>
                                <td><?php echo $row[0]->itm_name ?></td>
                                <td><?php echo $row[1] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>