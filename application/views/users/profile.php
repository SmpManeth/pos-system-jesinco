<title>My Profile</title>
<div class="page-content-col">
    <link href="<?php echo base_url("assets/pages/css/profile.min.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url("assets/global/plugins/jcrop/css/jquery.Jcrop.min.css") ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url("assets/global/plugins/jcrop/js/jquery.Jcrop.min.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/vendor/jquery.form.js") ?>"></script>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PROFILE SIDEBAR -->
            <div class="profile-sidebar">
                <!-- PORTLET MAIN -->
                <div class="portlet light profile-sidebar-portlet bordered">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <?php
                        $im_url = $user->avatar;
                        ?>
                        <img src="<?php echo base_url($im_url ? "public/images/profile/thumb/" . $im_url : "assets/images/annonimusavatar.png") ?>" alt="User Image"  class="img-responsive user-avatar">
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name" id="name"> <?php echo $user->first_name . " " . $user->last_name ?> </div>
                        <div class="profile-usertitle-job" id="job_display"> <?php echo $user->job ?> </div>
                    </div>
                    <style>
                        div#centerDiv {
                            width: 200px;
                            text-align: center;
                            margin: auto;
                        }
                    </style>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <!-- END SIDEBAR BUTTONS -->
                </div>
                <!-- END PORTLET MAIN -->
            </div>
            <!-- END BEGIN PROFILE SIDEBAR -->
            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title tabbable-line">
                                <div class="caption caption-md">
                                    <i class="icon-globe theme-font hide"></i>
                                    <span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab" aria-expanded="true">Personal Info</a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_1_2" data-toggle="tab" aria-expanded="false">Change Avatar</a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_1_3" data-toggle="tab" aria-expanded="false">Change Password</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- PERSONAL INFO TAB -->
                                    <div class="tab-pane active" id="tab_1_1">
                                        <?php echo form_open("#", "class='form-horizontal' id='my_profile_form'"); ?>
                                        <?php echo form_hidden("is_ajax_request"); ?>
                                        <div class="form-group">
                                            <label class="control-label">First Name</label>
                                            <input name="f_name" id="f_nmae" placeholder="John" class="form-control input-sm" type="text" value="<?php echo $user->first_name ?>" /> </div>
                                        <div class="form-group">
                                            <label class="control-label">Last Name</label>
                                            <input name="l_name" id="l_name" placeholder="Doe" class="form-control input-sm" type="text" value="<?php echo $user->last_name ?>"/> </div>
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <input name="mobile" placeholder="" class="form-control input-sm" type="text"  value="<?php echo $user->phone ?>"> </div>
                                        <div class="margiv-top-10">
                                            <button type="button" class="btn green" id="upd_profile_btn"><span>Update Profile</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                            <button class="btn default" type="reset"> Cancel </button>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->
                                    <!-- CHANGE AVATAR TAB -->
                                    <div class="tab-pane" id="tab_1_2">
                                        <p> Upload an Image for Your Avatar </p>
                                        <?php echo form_open("#", "class='form-horizontal' id='profile_photo'"); ?>
                                        <?php echo form_hidden("is_ajax_request"); ?>
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <div class="fileinput-new thumbnail pull-left" id="ori_pic" style="width: 200px; height: 200px;">
                                                            <?php
                                                            $im_url = $user->avatar;
                                                            ?>
                                                            <img src="<?php echo base_url($im_url ? "public/images/profile/thumb/" . $im_url : "http://www.placehold.it/200x200/EFEFEF/AAAAAA&amp;text=no+image") ?>" alt="User Image"  class="img-responsive user-avatar" />
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail pull-left" id="upload_img" style="max-width: 200px; max-height: 200px;margin-left: 10px;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <!--<input type="hidden" id="path" name="path"/>-->
                                                        <input type="hidden" id="img_path" name="img_path"/>
                                                        <a href="javascript:;" class="btn blue" id="open_img_btn"> Select Image </a>
                                                        <a href="javascript:;" id="no_img_btn" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                        <div class="hidden" id="progress_container">
                                                            <div class="progress progress-striped " style="height: 15px;">
                                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="clearfix margin-top-10">
                                                <span class="label label-danger">NOTE! </span>
                                                <span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                            </div>
                                        </div>
                                        <div class="margin-top-10">
                                            <button type="button" class="btn green" id="img_save_btn"><span>Save Changes</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                            <a href="javascript:;" class="btn default" id="img_cancel_btn"> Cancel </a>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                    <?php echo form_open_multipart("upload/upload_cover", "class='d-none' id='upload_form'"); ?>
                                    <?php echo form_hidden("is_ajax_request"); ?>
                                    <input name="userfile" type="file" id="userfile">
                                    <?php echo form_close(); ?>
                                    <!-- END CHANGE AVATAR TAB -->
                                    <!-- CHANGE PASSWORD TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <?php echo form_open("#", "id='c_pass_form'") ?>
                                        <?php echo form_hidden("is_ajax_request") ?>
                                        <div class="form-group">
                                            <label class="control-label">Username</label>
                                            <input class="form-control input-sm" type="text" name="uname" id="uname" value="<?php echo $user->username ?>"> </div>
                                        <div class="form-group">
                                            <label class="control-label">Current Password</label>
                                            <input class="form-control input-sm" type="password" name="c_pass" id="c_pass"> </div>
                                        <div class="form-group">
                                            <label class="control-label">New Password</label>
                                            <input class="form-control input-sm" type="password" name="n_pass" id="n_pass"> </div>
                                        <div class="form-group">
                                            <label class="control-label">Re-type New Password</label>
                                            <input class="form-control input-sm" type="password" name="con_pass" id="con_pass"> </div>
                                        <div class="margin-top-10">
                                            <button type="button" class="btn green" id="c_pass_btn"><span>Change Password</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                            <button href="javascript:;" class="btn default" type="reset"> Cancel </button>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                    <!-- END CHANGE PASSWORD TAB -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
</div>
<div class = "modal" id = "imageCrop_model">
    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <button type = "button" class = "close" data-dismiss = "modal" aria-label = "Close"><span aria-hidden = "true">&times;
                    </span></button>
                <h4 class = "modal-title">Crop Image</h4>
            </div>
            <div class = "modal-body" >
                <div>
                    <!-- hidden crop params -->
                    <input type="hidden" id="x1" name="x1" />
                    <input type="hidden" id="y1" name="y1" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
                    <input type="hidden" id="path" name="path" value=""/>

                    <div id="cover_crop_image_div" style="overflow: scroll">
                        <img src=""/>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="imageCrop_model_btn"><span>Crop</span>&nbsp;<img src="<?php echo base_url("images/ajax-loader.gif") ?>" width="20" class="hidden"/></button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#c_pass_btn").click(function () {
            DJ.disable_btn("c_pass_btn", "Changing");
            $.ajax({
                url: "<?php echo base_url("users/update_pass") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#c_pass_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("c_pass_btn", " Change Password ");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#c_pass").val("");
                        $("#n_pass").val("");
                        $("#con_pass").val("");
                        location.reload();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
        $("#upd_profile_btn").click(function () {
            DJ.disable_btn("upd_profile_btn", "Updating");
            $.ajax({
                url: "<?php echo base_url("users/update_profile") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#my_profile_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("upd_profile_btn", "Update Profile");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#name").html($("#f_nmae").val() + " " + $("#l_name").val());
                        $("#job_display").html($("#job").val());
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });

        $("#open_img_btn").click(function () {
            $("#userfile").trigger("click");
        });
        $("#userfile").change(function () {
            $("#upload_form").submit();
        });

        $("#upload_form").ajaxForm({
            dataType: "JSON",
            beforeSend: function () { //brfore sending form
                DJ.disable_btn("open_img_btn", "Uploading");
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
                DJ.enable_btn("open_img_btn", "Select Image");
                DJ.enable_btn("upload_btn", "Upload"); //enable submit button
                $("#progress_container").addClass("hidden"); // hide progressbar
            }, success: function (data) {
                if (data.msg_type == "OK") {
                    $("#path").val(data.data.file_name);
                    $("#cover_crop_image_div").html("");
                    var img = document.createElement("img");
                    $(img).attr({src: data.img});
                    $("#cover_crop_image_div").append(img);
                    $("#cover_crop_image_div img").Jcrop({
                        onChange: showCoords, aspectRatio: 1
                    });
                    $("#cover_container_btn button").removeClass("hidden");
                    $("#img_path").val(data.data.file_name);
                    $("#imageCrop_model").modal("show");
                }else{
                    DJ.Notify(data.msg,"danger");
                }
            }
        });
        $("#imageCrop_model_btn").click(function () {
            DJ.disable_btn("imageCrop_model_btn", "cropping");
            var x1 = $("#x1").val();
            var y1 = $("#y1").val();
            var w = $("#w").val();
            var h = $("#h").val();
            var file = $("#path").val();
            $.ajax({
                url: "<?php echo base_url("upload/jcrop_image") ?>",
                type: "POST",
                dataType: 'JSON',
                data: {
                    x1: x1,
                    y1: y1,
                    w: w,
                    h: h,
                    file: file
                },
                success: function (data) {
                    DJ.enable_btn("imageCrop_model_btn", "crop");
                    $("#imageCrop_model").modal("hide");
                    var img = document.createElement("img");
                    $(img).css({width: "100%"});
                    $(img).attr({src: data.file_thumb});
                    $("#img_path").val(data.file);
//                    $("#cover_container").html("");
                    $("#upload_img").append(img);
                }
            });
        });
        $("#no_img_btn").click(function () {
            $("#img_path").val("");
        });
        $("#img_save_btn").click(function () {
            DJ.disable_btn("img_save_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("users/save_profile_avatar") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#profile_photo").serialize(),
                success: function (data) {
                    DJ.enable_btn("img_save_btn", "Save Changes");
                    if (data.msg_type == "OK") {
                        var des = $("#ori_pic img").position();
                        var el = $("#upload_img img");
                        el.css("position", "absolute");
                        el.css("width", "200px");
                        el.animate({top: des.top + "px", left: des.left + "px"}, 800, undefined, function () {
//                            var img = $(el).clone();
                            $(el).appendTo("#ori_pic").css({
                                "position": "relative",
                                "z-index": "150",
                                top: "0",
                                left: "0"
                            });
                            $("#ori_pic img").remove();
                            $("#ori_pic").append(el);
                            $(".user-avatar").attr({src: $(el).attr("src")});

                        });
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
    function showCoords(c) {
        $('#x1').val(c.x);
        $('#y1').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    }
</script>