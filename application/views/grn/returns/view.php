<?php
//Aug 28, 2018 6:33:26 PM 
?>
<title>View Supplier Return</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='return_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Supplier :</label>
                                <input type="hidden" value="<?php echo $ret_note->id ?>" name="ret_id" id="ret_id" /><br/>
                                <p class="form-control-static"><?php echo $ret_note->company_name ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Return Date :</label>
                                <p class="form-control-static"><?php echo $ret_note->ret_date ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Return Reference :</label>
                                <p class="form-control-static"><?php echo!empty($ret_note->ret_ref) ? $ret_note->ret_ref : "&nbsp;" ?></p>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Status :</label><br/>
                                <p class="form-control-static text-<?php echo $ret_note->status == "1" ? "success" : "danger" ?>"><strong><?php echo $ret_note->status == "1" ? "Finished" : "Cancelled" ?></strong></p>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>G R N </td>
                                <td>Item Code</td>
                                <td>Item Name</td>
                                <td>Quantity</td>
                                <td>Rate</td>
                                <td>Total</td>
                            </tr>                
                        </thead>
                        <tbody id="tbody">
                            <?php
                            if (isset($ret_items)) {
                                $i = 0;
                                foreach ($ret_items as $rt_itm) {
                                    $i++;
                                    $total = doubleval($rt_itm->qty) * doubleval($rt_itm->rate);
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo decorate_code($rt_itm->gr_id, "grn", $this->prefixes) ?></td>
                                        <td><?php echo $rt_itm->itm_code ?></td>
                                        <td><?php echo $rt_itm->itm_name ?></td>
                                        <td class="text-center"><?php echo $rt_itm->qty ?></td>
                                        <td class="text-right"><?php echo number_format($rt_itm->rate, 2) ?></td>
                                        <td class="text-right"><?php echo number_format($total, 2) ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            if ($ret_note->status == "1") {
                ?>
                <div class="portlet light bordered text-right">
                    <a href="<?php echo base_url("grn/returns/print/" . decorate_code($ret_note->id, "supreturn", $this->prefixes)) ?>" class="btn btn-sm btn-success" id="finish_ret"><i class="fa fa-print"></i> <span>Print</span></a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>