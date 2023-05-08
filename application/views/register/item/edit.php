<title>Edit Item</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_itm_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Item Code :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" placeholder="Enter Item Code" name="itm_code" id="itm_code" value="<?php echo $item->itm_code ?>" />
                                <input type="hidden" name="id" id="id" value="<?php echo $item->id ?>" />
                            </div>
                            <label class="col-md-2 control-label">Item Name:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" placeholder="Enter Item Name " name="itm_name" id="itm_name" value="<?php echo $item->itm_name ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category :</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control input-sm" name="category" id="category">
                                        <optgroup>
                                            <option value="-1">--SELECT--</option>
                                        </optgroup>
                                        <optgroup>
                                            <?php
                                            if (isset($categories)) {
                                                foreach ($categories as $category) {
                                                    ?>
                                                    <option <?php echo $category->id == $item->itm_cat ? "selected" : "" ?> value="<?php echo $category->id ?>"><?php echo $category->cat_name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn green-haze" type="button" id="cat_btn"><i class="fa fa-plus-square"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-2 control-label">Sub Category:</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control input-sm" name="sub_category" id="sub_category">
                                        <optgroup>
                                            <option value="-1">--SELECT--</option>
                                        </optgroup>
                                        <optgroup>
                                            <?php
                                            if (isset($subs)) {
                                                foreach ($subs as $sub) {
                                                    ?>
                                                    <option <?php echo $item->sub_cat == $sub->id ? "selected" : "" ?> value="<?php echo $sub->id ?>"><?php echo $sub->sub_name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </optgroup>
                                    </select>
                                    <span class="input-group-addon hidden" id="sub-cat-ld-img">
                                        <img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class=""/>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn green-haze" type="button" id="sub_cat_btn"><i class="fa fa-plus-square"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description :</label>
                            <div class="col-md-4">
                                <textarea class="form-control input-sm" name="description" id="description" rows="3"><?php echo $item->itm_description ?></textarea>
                            </div>
                            <div class="col-md-6 hidden">
                                <label class="col-md-4 control-label">Serial :</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control input-sm" placeholder="" name="serial" id="serial" <?php echo $item->stock_type == "1" ? "disabled" : "" ?> value="<?php echo $item->bar_code ?>" />
                                </div>
                                <br/>
                                <label class="col-md-4 control-label">Unique :</label>
                                <div class="col-md-8">
                                    <div class="mt-radio-inline text-left">
                                        <label class="mt-checkbox">
                                            <input name="unique" value="1"  type="checkbox" id="unique" <?php echo $item->unique_type == "1" ? "checked" : "" ?>> Unique
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <label class="col-md-2 control-label">Inventory Type :</label>
                            <div class="col-md-4">
                                <div class="mt-radio-inline text-left">
                                    <label class="mt-radio">
                                        <input name="inv_type" value="1"  type="radio" id="inv_type"  <?php echo $item->stock_type == "1" ? "checked" : "" ?>> Inventory
                                        <span></span>
                                    </label>
                                    <label class="mt-radio">
                                        <input name="inv_type" value="2"  type="radio" id="inv_type"  <?php echo $item->stock_type == "1" ? "checked" : "" ?>> Non Inventory
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <label class="col-md-2 control-label">Unit of Measure:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" placeholder="" name="u_o_m" id="u_o_m" value="<?php echo $item->u_o_m ?>" />
                            </div>
                        </div>
                        <?php
                        if ($user->user_type == "user") {
                            ?>
                            <input type="hidden" value="1" name="visibility" id="visibility" >
                            <?php
                        } else {
                            ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Visibility :</label>
                                <div class="col-md-9">
                                    <div class="mt-checkbox-outline text-left">
                                        <label class="mt-checkbox font-green-seagreen">
                                            <input type="checkbox" value="1" name="visibility" id="visibility" <?php echo $item->visibility == "1" ? "checked" : "" ?>>&nbsp;For this Branch Only
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Status :</label>
                            <div class="col-md-9">
                                <div class="mt-radio-inline text-left">
                                    <label class="mt-radio font-green-jungle">
                                        <input name="status" value="1" <?php echo $item->status == "1" ? "checked=''" : "" ?> type="radio"> Active
                                        <span></span>
                                    </label>
                                    <label class="mt-radio font-red-thunderbird">
                                        <input name="status" value="2" <?php echo $item->status == "2" ? "checked=''" : "" ?> type="radio"> Inactive
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <legend>Price Details</legend>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Cost Price :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number" placeholder="Cost Price" name="cost" id="cost" value="<?php echo $item->cost ?>" />
                            </div>
                            <label class="col-md-2 control-label">Wholesale Price :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number" placeholder="Wholesale Price" name="wholesale" id="wholesale" value="<?php echo $item->wholesale ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Selling Price :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number" placeholder="Selling Price" name="selling" id="selling" value="<?php echo $item->selling ?>" />
                            </div>
                            <label class="col-md-2 control-label">Minimum Selling Price :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number" placeholder="Minimum Selling Price" name="min_sell" id="min_sell" value="<?php echo $item->min_selling ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Discount Rate :</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm number" placeholder="Discount" name="discount" id="discount" value="<?php echo $item->discount ?>" />
                                    <span class="input-group-btn" style="width:0px;"></span>
                                    <select class="form-control input-sm" style="width: 80px;" name="dis_type" id="dis_type">
                                        <option <?php echo $item->dis_type == "1" ? "selected" : "" ?> value="p">%</option>
                                        <option <?php echo $item->dis_type == "2" ? "selected" : "" ?> value="r">Rs</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <legend>Stock Details</legend>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Minimum Stock Warning :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm number" placeholder="Warn if the Stock below this Quantity" name="minimum_stock_warn" id="minimum_stock_warn" value="<?php echo $item->minimum_stock_warn ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn green" id="add_itm_btn"><span>Submit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                <button type="reset" class="btn default">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<div class="modal fade" id="cat_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body text-center">

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".number").number(true, 3);
        $("#cat_btn").click(function () {
            $("#cat_model h4").html("Add / Update Categories");
            $("#cat_model .modal-body").html('<h2>Loading... <img src="<?php echo base_url("assets/images/377.gif") ?>" width="30"/></h2>');
            $("#cat_model").modal("show");
            $("#cat_model .modal-body").load('<?php echo base_url("register/load_categories") ?>');
        });
        $("#sub_cat_btn").click(function () {
            var cat = $("#category").val();
            if (cat !== "-1") {
                $("#cat_model h4").html("Add / Update Sub Categories");
                $("#cat_model .modal-body").html('<h2>Loading... <img src="<?php echo base_url("assets/images/377.gif") ?>" width="30"/></h2>');
                $("#cat_model").modal("show");
                $("#cat_model .modal-body").load('<?php echo base_url("register/load_sub_categories") ?>', {cat: cat});
            } else {
                DJ.Notify("Please Select a Category before add a Sub Category", "danger");
            }
        });
        $("#unique").change(function () {
            if ($(this).is(":checked")) {
                $("#serial").prop("disabled", true);
            } else {
                $("#serial").prop("disabled", false);
            }
        });
        $("#category").change(function () {
            $("#sub_category optgroup:last").html("");
            $("#sub-cat-ld-img").removeClass("hidden");
            $.ajax({
                url: "<?php echo base_url("register_c/get_sub_categories") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {cat_id: $("#category").val()},
                success: function (data) {
                    $("#sub-cat-ld-img").addClass("hidden");
                    if (data.msg_type == "OK") {
                        $.each(data.subs, function (i, row) {
                            var option = document.createElement("option");
                            $(option).html(row.sub_name).val(row.id);
                            $("#sub_category optgroup:last").append(option);
                        });
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#add_itm_btn").click(function () {
            DJ.disable_btn("add_itm_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/update_item") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#new_itm_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("add_itm_btn", "Submit");
                    if (data.msg_type === "OK") {
                        DJ.Notify(data.msg, "success");
//                        $("#new_itm_form").trigger("reset");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#rate ,#cost_discount").keyup(function () {
            calculate_cost();
        });
    });

    function calculate_cost() {
        var rate = Number($("#rate").val());
        var cost_discount = Number($("#cost_discount").val());
        var cost = 0;
        if (rate > 0 && cost_discount > 0) {
            console.warn(discount);
            cost = rate - ((rate * cost_discount) / 100);
        }
        $("#cost").val(cost);
    }
</script>