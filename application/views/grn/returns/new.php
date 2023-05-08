<?php
//Aug 27, 2018 4:53:34 PM 
?>
<title>New Supplier Return</title>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='return_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
<!--                    <input type="hidden" value="" name="id" id="id" />-->
                    <div class="form-body">
                        <div class="form-group well">
                            <div class="col-md-4">
                                <label class="control-label">Supplier :</label>
                                <input type="hidden" value="" name="id" id="gr_id" />
                                <select class="form-control input-sm select-picker" name="supplier" id="supplier">
                                    <optgroup>
                                        <option value="">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($sups)) {
                                            foreach ($sups as $sup) {
                                                ?>
                                                <option data-subtext="<?php echo $sup->bis_type ?>" value="<?php echo $sup->id ?>"><?php echo $sup->company_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Return Date :</label>
                                <input type="text" class="form-control input-sm datepicker" name="ret_date" id="ret_date" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Return Reference :</label>
                                <input type="text" class="form-control input-sm" name="ret_ref" id="ret_ref" />
                            </div>
                            <div class="col-md-4">
                                <label class="control-label"><br/></label><br/>
                                <button type="button" class="btn btn-primary btn-sm" id="create_return"><i class="fa fa-bars"></i> <span>Create</span></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
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
                                <td></td>
                            </tr>                
                        </thead>
                        <tbody id="tbody">
                            <?php
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true,startDate: '<?php echo date_plaus_days(date("Y-m-d"), 7, "-") ?>'});
        $(".number").number(true, 2);

        $("#create_return").click(function () {
            DJ.disable_btn_fa("create_return", "Creating");
            $.ajax({
                url: "<?php echo base_url("grn/create_return_note") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#return_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("create_return", "Create");
                    if (data.msg_type == "OK") {
                        window.location.href = data.url;
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>