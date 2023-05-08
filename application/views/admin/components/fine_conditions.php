<?php ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Days</th>
            <th>Fine Amount</th>
            <th></th>
        </tr>
    </thead>   
    <tbody>
        <?php
        foreach ($fines as $fine) {
            ?>
            <tr>
                <td><?php echo $fine->day != "999999" ? $fine->day : "Above"; ?></td>
                <td><?php echo number_format($fine->fine, 2); ?></td>
                <td>
                    <button type="button" class="btn btn-xs btn-primary  edit_fine" data-id="<?php echo $fine->id ?>" data-day="<?php echo $fine->day ?>" data-fine="<?php echo $fine->fine ?>" ><i class="fa fa-edit"></i> <span>Edit</span></button>
                    <?php
                    if ($fine->day != "999999") {
                        ?>
                        <button type="button" class="btn btn-xs btn-danger delete_fine" data-id="<?php echo $fine->id ?>" ><i class="fa fa-times"></i> <span>Delete</span></button>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $(".edit_fine").click(function (e) {
            $("#id_edit").val($(this).data("id"));
            if ($(this).data("day") == "999999") {
                $("#edit_above").show();
                $("#day_edit").hide();
            } else {
                $("#edit_above").hide();
                $("#day_edit").show();
            }
            $("#day_edit").val($(this).data("day"));
            $("#fine_amount_edit").val($(this).data("fine"));
            DJ.show_model({
                title: "Edit Fine Condition",
                selector: "#edit_form_div"
            });
        });
        $(".delete_fine").click(function (e) {
            var btn = $(this);
            DJ.Overlay_confirm({
                title: "Are you sure to delete this record?",
                click: function (v) {
                    if (v) {
                        var id = btn.data("id");
                        DJ.disable_ele_fa(btn, "Deleting");
                        $.post("<?php echo base_url("admin/settings/delete_fine"); ?>", {id: id}, function (data) {
                            DJ.enable_ele_fa(btn, "Delete");
                            if (data.msg_type == "OK") {
                                load_fines();
                                DJ.Notify(data.msg, "success");
                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }, "JSON");
                    }
                }
            });
        });
    });
</script>