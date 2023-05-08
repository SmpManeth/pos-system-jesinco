<?php ?>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <h2>Fines Conditions</h2>
        <div class="col-lg-8 col-lg-offset-2">
            <?php echo form_open("#", "class='form-horizontal' id='devision_form'"); ?>
            <?php echo form_hidden("is_ajax_request"); ?>

            <div class="form-group">
                <div class="col-md-6">
                    <label class="control-label">Devision Name :</label>
                    <input type="text" class="form-control input-sm" name="devision" id="devision" value="" />
                </div>
                <div class="col-md-2">
                    <label class="control-label"><br/></label>
                    <button type="button" class="btn btn-success btn-sm" id="save_devision"><i class="fa fa-check"></i> <span>Save</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="row">
            <div class="col-lg-12" id="devisions_div">
                <table id="devisions_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td>Devision</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($devisions)){
                        foreach($devisions as $devision){
                            ?>
                            <tr>
                            <td><?php echo $devision->devision ?></td>
                            <td><a href="#" onclick="edit_devision(this,event)" data-id="<?php echo $devision->id ?>">Edit</a></td>
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
</div>
<script>
    $(document).ready(function () {
        $("#save_devision").click(function (e) {
            DJ.disable_btn_fa("save_devision", "Saving");
            $.ajax({
                url: "<?php echo site_url("admin/settings/save_devision") ?>",
                type: "POST",
                dataType: "JSON",
                data: $("#devision_form").serialize(),
                success: function (data) {
                    DJ.enable_btn_fa("save_devision", "Save");
                    if (data.msg_type == "OK") {
                        var tr = document.createElement("tr");
                        var td1 = document.createElement("td");
                        var td2 = document.createElement("td");
                        var a = document.createElement("a");
                        $(td1).html($("#devision").val());
                        $(a).html("Edit").attr({"data-id":data.id}).click(function(e){
                            edit_devision(a,e);
                        });
                        $(td2).append(a);
                        $(tr).append(td1,td2);
                        $("#devisions_table tbody").append(tr);
                        DJ.Notify(data.msg, "success");
                        $("#devision_form").trigger("reset");

                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
    function edit_devision(ele,e){
        e.preventDefault();
        var id = $(ele).data("id");
        var dev = $(ele).closest("tr").find("td").first().html();
        DJ.Overlay_input({
            title: "Edit Division Name",
            value: dev,
            type: "text",
            no_empty: true,
            button: {
                yes: {txt: "Save"},
                no: {txt: "CANCEL"}
            },
            click: function (v) {
                $.ajax({
                    url: "<?php echo site_url("admin/settings/update_devision") ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        id:id,
                        devision:v
                    },
                    success: function (data) {
                        $(ele).closest("tr").find("td").first().html(v);
                        DJ.Notify(data.msg, "success");
                    }
                });
            }
        });
    }
</script>
