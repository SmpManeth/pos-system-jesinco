<?php
//Aug 10, 2018 5:37:25 PM 
?>
<link href="<?php echo base_url("assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css") ?>" rel="stylesheet" type="text/css"/>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js") ?>"></script>
<title>Temporary Good Receive Entry</title>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_temp_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
<!--                    <input type="hidden" value="" name="id" id="id" />-->
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="alert alert-info alert-dismissible text-center" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>Warning!</strong> This is a Temporary added record. This will <strong>effect your Stock</strong>.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Item :</label>
                                <select class="form-control input-sm select-picker" name="itm_code" id="itm_code">
                                    <optgroup>
                                        <option value="">--SELECT--</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($items)) {
                                            foreach ($items as $item) {
                                                ?>
                                                <option data-subtext="<?php echo $item->itm_code ?>" data-price="<?php echo $item->selling ?>" value="<?php echo $item->id ?>"><?php echo $item->itm_name ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Quantity :</label>
                                <input type="text" class="form-control input-sm number" name="qty" id="qty" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Rate :</label>
                                <input type="text" class="form-control input-sm number" name="rate" id="rate" />
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Total :</label>
                                <input type="text" class="form-control input-sm number" name="itm_total" id="itm_total" readonly=""/>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label><br/>
                                <button type="button" class="btn btn-primary btn-sm " id="add_item_btn"><i class="fa fa-plus"></i> <span>Add Item</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Item Name</td>
                        <td>Quantity</td>
                        <td>Price</td>
                        <td>Total</td>
                        <td>Added Date</td>
                    </tr>                
                </thead>
                <tbody id="tbody">
                    <?php
                    if (isset($tgrn_items)) {
                        foreach ($tgrn_items as $tgrni) {
                            ?>
                            <tr>
                                <td><?php echo $tgrni->itm_code ?></td>
                                <td><?php echo $tgrni->itm_name ?></td>
                                <td><?php echo $tgrni->qty ?></td>
                                <td><?php echo is_zero($tgrni->price) ?></td>
                                <td><?php echo is_zero($tgrni->price * $tgrni->qty) ?></td>
                                <td><?php echo time_ago($tgrni->edit_at) ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select-picker").selectpicker({showSubtext: true, style: "btn btn-link", liveSearch: true});
        $("#add_item_btn").click(function () {

            DJ.Overlay_confirm({
                title: "<strong>Are you want to add this item?</strong><br>This directly effect item's Stock",
                button: {
                    yes: {txt: "YES, Proceed"},
                    no: {txt: "NO"}
                },
                click: function (v) {
                    if (v) {
                        DJ.disable_btn("add_item_btn", "Saving");
                        $.ajax({
                            url: "<?php echo base_url("grn/add-temp-item") ?>",
                            type: 'POST',
                            dataType: 'JSON',
                            data: $("#new_temp_form").serialize(),
                            success: function (data) {
                                DJ.enable_btn("add_item_btn", "Add Item");
                                if (data.msg_type == "OK") {
                                    DJ.Notify(data.msg, "success");
                                    window.location.reload();
                                } else {
                                    DJ.Notify(data.msg, "danger");
                                }
                            }
                        });
                    }
                }
            });
        });

        $("#itm_code").change(function () {
            var price = $("#itm_code option:selected").data("price");
            var unique = $("#itm_code option:selected").data("unique");
            $("#rate").val(price);
        });

        $("#qty").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#rate").focus().select();
            }
        });
        $("#rate").keyup(function (e) {
            calculate_item();
            if (e.which == 13) {
                $("#add_item_btn").trigger("click");
            }
        });
    });
    function calculate_item() {
        var qty = $("#qty").val();
        var rate = $("#rate").val();
        var tot = Number(qty) * Number(rate);
        console.warn(qty);
        $("#itm_total").val(tot);
    }
</script>