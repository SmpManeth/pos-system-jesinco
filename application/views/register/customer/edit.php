<title>Edit Customer</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-9 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='edit_cus_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Name :</label>
                            <div class="col-md-2">
                                <select class="form-control input-sm" name="customer_prefix">
                                    <optgroup>
                                        <option value="">---</option>
                                    </optgroup>
                                    <optgroup>
                                        <?php
                                        if (isset($user_prefixes)) {
                                            foreach ($user_prefixes as $u_pref) {
                                                ?>
                                                <option <?php echo $customer->customer_prefix == $u_pref ? "selected" : "" ?> value="<?php echo $u_pref ?>"><?php echo $u_pref ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="hidden" name="id" value="<?php echo $customer->id ?>" />
                                <input type="text" class="form-control input-sm" placeholder="Enter Name" name="cus_name" id="cus_name" value="<?php echo $customer->customer_name ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Address :</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control input-sm" placeholder="PO Box" name="address_po_box" id="address_po_box" value="<?php echo $customer->address_po_box ?>">
                                <input type="text" class="form-control input-sm" placeholder="Street Name" name="address_line1" id="address_line1" value="<?php echo $customer->address_line1 ?>">
                                <input type="text" class="form-control input-sm" placeholder="Address Line 2" name="address_line2" id="address_line2" value="<?php echo $customer->address_line2 ?>">
                                <input type="text" class="form-control input-sm" placeholder="City / Town" id="address_city" name="address_city" value="<?php echo $customer->address_city ?>">
                                <select class="form-control input-sm" name="counrty" id="counrty">
                                    <?php
                                    if (isset($countries)) {
                                        foreach ($countries->result_array() as $country):
                                            ?>
                                            <option <?php echo $country["country_name"] == $customer->counrty ? "selected" : "" ?> value="<?php echo $country["country_name"] ?>"><?php echo $country["country_name"] ?></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Telephone" name="tp1" id="tp1" value="<?php echo $customer->tp1 ?>">
                                <input type="text" class="form-control input-sm input-inline input-medium" placeholder="Fax " id="tp2" name="tp2" value="<?php echo $customer->tp2 ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Address :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="Email" name="email" id="email" value="<?php echo $customer->email ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">N I C Number:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="NIC" name="nic" id="nic" value="<?php echo $customer->nic ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Division:</label>
                            <div class="col-md-4">
                                <select name="devision_id" id="devision_id" class="form-control input-sm">
                                    <option value="">--SELECT--</option>
                                    <?php
                                    if (isset($devisions)) {
                                        foreach ($devisions as $devision) {
                                            ?>
                                            <option <?php echo $devision->id == $customer->devision_id ? "selected" : "" ?> value="<?php echo $devision->id ?>"><?php echo $devision->devision ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Custom Field:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" placeholder="Custom Field 1" name="custom_1" id="custom_1"  value="<?php echo $customer->custom_1 ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Location Image:</label>
                            <div class="col-md-4">
                                <input type="hidden" name="path" id="path" value="<?php echo $customer->location_img ?>" />
                                <button type="button" class="btn btn-default" id="select_image"><i class="fa fa-image"></i> <span>Select Image</span></button>
                                <div id="cover_crop_image_div">
                                    <?php
                                    if ($customer->location_img) {
                                        echo "<img src='" . base_url("public/upload/locations/" . $customer->location_img) . "' class='img-responsive'>";
                                    }
                                    ?>
                                </div>
                                <div class="hidden" id="progress_container">
                                    <div class="progress progress-striped " style="height: 15px;">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <?php
                                $location = json_decode($customer->location)
                                ?>
                                <input type="hidden" name="location[long]" value="<?php echo isset($location->long) ? $location->long : "" ?>" id="loc_long" />
                                <input type="hidden" name="location[lat]" value="<?php echo isset($location->lat) ? $location->lat : "" ?>"id="loc_lat" />
                                <button type="button" class="btn btn-default" id="select_location"><i class="fa fa-map-marker"></i> <span>Get My Location</span></button>

                                <?php
                                if (isset($location->long) && !empty($location->long)) {
                                    ?>
                                    <a href="http://maps.google.com/?q=<?php echo $location->lat ?>,<?php echo $location->long ?>">Open Map</a>
                                    <?php
                                }
                                ?>
                                <span id="location-span"><?php echo isset($location->long) ? ('Your Location is : ' . $location->lat . ', ' . $location->long) : '' ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status :</label>
                            <div class="col-md-9 text-right">
                                <div class="mt-radio-inline text-left">
                                    <label class="mt-radio font-green-jungle">
                                        <input name="status" value="1" <?php echo $customer->status == "1" ? "checked=''" : "" ?> type="radio"> Active
                                        <span></span>
                                    </label>
                                    <label class="mt-radio font-red-thunderbird">
                                        <input name="status" value="2" <?php echo $customer->status == "2" ? "checked=''" : "" ?> type="radio"> Inactive
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($customer->approved == "1") {
                            ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Status :</label>
                                <div class="col-md-9 text-right">
                                    <div class="mt-radio-inline text-left">
                                        <label class="mt-radio font-green-jungle">
                                            <input name="approved" value="1" <?php echo $customer->approved == "1" ? "checked=''" : "" ?> type="radio"> Approve
                                            <span></span>
                                        </label>
                                        <label class="mt-radio font-red-thunderbird">
                                            <input name="approved" value="0" <?php echo $customer->approved == "2" ? "checked=''" : "" ?> type="radio"> Dis-Approve
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group hidden">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9 text-right">
                                <div class="mt-checkbox-outline text-left">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" name="visibility" id="visibility" value="1" <?php echo $customer->visibility == "1" ? "checked=''" : "" ?>>&nbsp;For this Branch Only
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <?php
//                                if ($user->user_type == "superadmin" || $user->user_type == "admin") {
                                if ($customer->approved == "1") {
                                    if (user_can($user, CAN_CUSTOMER_EDIT)) {
                                        ?>
                                        <button type="button" class="btn btn-success" id="edit_cus_btn"><i class="fa fa-save"></i> <span>Submit</span></button>
                                        <?php
                                    }
                                } else {
                                    if (user_can($user, CAN_EDIT_UN_APPROVED_CUSTOMERS)) {
                                        ?>
                                        <button type="button" class="btn btn-success" id="edit_cus_btn"><i class="fa fa-save"></i> <span>Submit</span></button>
                                        <?php
                                    }
                                }
                                ?>
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
<?php echo form_open_multipart("upload/upload_file", "class='d-none' id='upload_form'"); ?>
<?php echo form_hidden("is_ajax_request"); ?>
<?php echo form_hidden("folder", 'upload/locations'); ?>
<input name="userfile" type="file" id="userfile">
<?php echo form_close(); ?>
<script src="<?php echo base_url("assets/js/vendor/jquery.form.js"); ?>"></script>
<script>
    $(document).ready(function () {
        $("#edit_cus_btn").click(function () {
            DJ.disable_btn_fa("edit_cus_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("register_c/update_customer") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#edit_cus_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("edit_cus_btn", "Submit");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#select_image").click(function () {
            $("#userfile").trigger("click");
        });
        $("#userfile").change(function () {
            $("#upload_form").submit();
        });

        $("#upload_form").ajaxForm({
            dataType: "JSON",
            beforeSend: function () { //brfore sending form
                DJ.disable_btn("select_image", "Uploading");
                DJ.disable_btn("upload_btn", "");
                $("#progress_container").removeClass("hidden"); //show progressbar
                $(".progress-bar").css({width: "0%"}); //initial value 0% of progressbar
            },
            uploadProgress: function (event, position, total, percentComplete) { //on progress
//                $("#progress_container").width(percentComplete + '%') //update progressbar percent complete
                $(".progress-bar").css({width: percentComplete + "%"});
            },
            complete: function (data) { // on complete
                $("#upload_form").resetForm();  // reset form
                DJ.enable_btn("select_image", "Select Image");
                DJ.enable_btn("upload_btn", "Upload"); //enable submit button
                $("#progress_container").addClass("hidden"); // hide progressbar
            }, success: function (data) {
                if (data.msg_type == "OK") {
                    $("#path").val(data.name);


                    var img = "<img src='" + data.url + "' class='img-responsive'>";

                    $("#cover_crop_image_div").html(img);

                } else {
                    DJ.Notify(data.msg, "danger");
                }
            }
        });
        $("#select_location").click(function (e) {
            getLocation();
        });
    });
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        $("#loc_long").val(position.coords.longitude);
        $("#loc_lat").val(position.coords.latitude);
        $("#location-span").html('Your Location is : ' + position.coords.latitude + ", " + position.coords.longitude);
    }
</script>
