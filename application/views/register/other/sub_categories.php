<title>Sub Categories</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_sub_cat_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <?php
                        if (isset($from) && $from == "item") {
                            ?>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <span >Add Sub Categories for </span></label> <span class="badge badge-info"><?php echo isset($cat) ? $cat->cat_name : "" ?></span>&nbsp;Category
                                <input type="hidden" id="cat_id" name="cat_id" value="<?php echo isset($cat) ? $cat->id : "-1" ?>"/>
                                </div>
                            </div>
                            <br/>
                            <?php
                        } else {
                            ?>
                            <select class="form-control input-sm" name="category" id="category">
                                <optgroup>
                                    <option value="-1">--SELECT--</option>
                                </optgroup>
                                <optgroup>
                                    <?php
                                    if (isset($cats)) {
                                        foreach ($cats as $cat) {
                                            ?>
                                            <option value="<?php echo $cat->id ?>"><?php echo $cat->cat_name ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </optgroup>
                            </select>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sub Category:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" placeholder="" name="sub_cat_name" id="sub_cat_name">
                            </div>
                            <div class="col-md-4">
                                <div class="mt-checkbox-outline text-left">
                                    <label class="mt-checkbox">
                                        <input type="checkbox" name="visibility" value="1" id="visibility">&nbsp;For this Branch Only
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn green" id="add_sub_cat_btn"><span>Add</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
                <div class="portlet-body form" style="max-height: 400px;overflow-y: scroll">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sub Category</th>
                                <th>Visibility</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="sub_cat_tbody">
                            <?php
                            if (isset($sub_categories)) {
                                foreach ($sub_categories as $sub) {
                                    ?>
                                    <tr class="text-left" id="ics_<?php echo $sub->id ?>">
                                        <td><?php echo $sub->sub_name ?></td>
                                        <td><label class="label label-<?php echo $sub->visibility == "0" ? "info" : "warning" ?>"><?php echo $sub->visibility == "0" ? "Global" : "Private" ?></label></td>
                                        <td><a class="javascript:;" onclick="remove_sub_category(<?php echo $sub->id ?>)"><i class="fa fa-remove font-red-thunderbird"></i></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".number").number(true, 2);
        $("#add_sub_cat_btn").click(function () {
            var sub_cat_name = $("#sub_cat_name").val();
            if (sub_cat_name != "") {
                DJ.disable_btn("add_sub_cat_btn", "Saving");
                $.ajax({
                    url: "<?php echo base_url("register_c/save_sub_cateory") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: $("#new_sub_cat_form").serialize(),
                    success: function (data) {
                        DJ.enable_btn("add_sub_cat_btn", "Add");
                        if (data.msg_type == "OK") {
                            var tr = document.createElement("tr");
                            var td1 = document.createElement("td");
                            var td2 = document.createElement("td");
                            var td3 = document.createElement("td");

                            var a = document.createElement("a");
                            var i = document.createElement("i");
                            var label = document.createElement("label");

                            var option = document.createElement("option");

                            $(i).addClass("fa fa-remove font-red-thunderbird");
                            $(a).append(i).click(function () {
                                remove_sub_category(data.id);
                            });
                            $(label).html(data.la_text).addClass("label label-" + data.la_color);
                            $(td1).html(sub_cat_name);
                            $(td2).append(label);
                            $(td3).append(a);
                            $(tr).append(td1, td2, td3).attr({id: "ic_" + data.id});
                            $("#sub_cat_tbody").prepend(tr);
                            $(tr).addClass("success text-left");
                            $(option).val(data.id).html(sub_cat_name);
                            $("#sub_category optgroup:last").append(option);
                            setTimeout(function () {
                                $(tr).removeClass("success");
                            }, 1000);
                            $("#new_sub_cat_form").trigger("reset");
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            } else {
                DJ.Notify("Cant Save.<br/>Empty Item Category.", "danger");
            }
        });
    });

    function remove_sub_category(id) {
        DJ.Confirm("Are You want to remove This Subcategory?", function () {
            $.ajax({
                url: "<?php echo base_url("register_c/remove_sub_category") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#ics_" + id).slideUp(500, function () {
                            $(this).remove();
                            $("#sub_category option[value='" + id + "']").remove();
                        });
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>