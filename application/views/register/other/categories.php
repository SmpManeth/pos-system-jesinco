<title>Categories</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='new_cat_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" placeholder="Enter Category Name" name="cat_name" id="cat_name">
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
                                <button type="button" class="btn green" id="add_cat_btn"><span>Add</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
                <div class="portlet-body form" style="max-height: 400px;overflow-y: scroll">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Visibility</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cat_tbody">
                            <?php
                            if (isset($cats)) {
                                foreach ($cats as $cat) {
                                    ?>
                                    <tr class="text-left" id="ic_<?php echo $cat->id ?>">
                                        <td><?php echo $cat->cat_name ?></td>
                                        <td><label class="label label-<?php echo $cat->visibility == "0" ? "info" : "warning" ?>"><?php echo $cat->visibility == "0" ? "Global" : "Private" ?></label></td>
                                        <td>
                                            <?php
                                            if (user_can_edit()) {
                                                ?>
                                                <a class="javascript:;" onclick="remove_category(<?php echo $cat->id ?>)"><i class="fa fa-remove font-red-thunderbird"></i></a>
                                                    <?php
                                                }
                                                ?>
                                        </td>
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
        $("#add_cat_btn").click(function () {
            var item_cat = $("#cat_name").val();
            if (item_cat != "") {
                DJ.disable_btn("add_cat_btn", "Saving");
                $.ajax({
                    url: "<?php echo base_url("register_c/save_cateory") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: $("#new_cat_form").serialize(),
                    success: function (data) {
                        DJ.enable_btn("add_cat_btn", "Add");
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
                                remove_category(data.id);
                            });
                            $(label).html(data.la_text).addClass("label label-" + data.la_color);
                            $(td1).html(item_cat);
                            $(td2).append(label);
                            $(td3).append(a);
                            $(tr).append(td1, td2, td3).attr({id: "ic_" + data.id});
                            $("#cat_tbody").prepend(tr);
                            $(tr).addClass("success text-left");
                            $(option).val(data.id).html(item_cat);
                            $("#category optgroup:last").append(option);
                            setTimeout(function () {
                                $(tr).removeClass("success");
                            }, 1000);
                            $("#new_cat_form").trigger("reset");
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

    function remove_category(id) {
        DJ.Confirm("Are You want to remove This Category?", function () {
            $.ajax({
                url: "<?php echo base_url("register_c/remove_category") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#ic_" + id).slideUp(500, function () {
                            $(this).remove();
                            $("#category option[value='" + id + "']").remove();
                        });
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>