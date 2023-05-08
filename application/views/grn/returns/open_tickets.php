<?php
//Aug 28, 2018 8:21:29 AM 
?>
<table class="table table-bordered" >
    <thead>
        <tr>
            <td>#</td>
            <td>Supplier</td>
            <td>Note ID</td>
            <td>Date</td>
            <td style="width: 90px;"></td>
            <td></td>
        </tr>                
    </thead>
    <tbody id="tbody">
        <?php
        if (isset($notes)) {
            $i = 0;
            foreach ($notes as $note) {
                $i++;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $note->company_name; ?></td>
                    <td><?php echo decorate_code($note->id, "supreturn", $this->prefixes); ?></td>
                    <td><?php echo $note->ret_date; ?></td>
                    <td><input type="number" class="form-control input-sm" /></td>
                    <td><button type="button" class="btn btn-primary btn-xs" onclick="open_qty_dialog(<?php echo $note->id ?>, this)"><i class="fa fa-check"></i> <span>Select</span></button></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
<script>
    function open_qty_dialog(id, ele) {
        var v = $(ele).parent().parent().find("input").val();
        if (v !== "") {
            if (v <= item.max) {
                item.ret_id = id;
                item.ret_qty = v;
//                console.log(item);
                DJ.disable_ele_fa(ele, "");
                $.ajax({
                    url: "<?php echo base_url("grn/add_return") ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: item,
                    success: function (data) {
                        DJ.disable_ele_fa(ele, "Select");
                        if (data.msg_type == "OK") {
                            DJ.close_model();
                            DJ.Notify(data.msg, "success");
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }
                });
            } else {
                DJ.Notify("Return Quantity Exceeds the G R N Quantity.", "danger");
            }
        } else {
            DJ.Notify("Empty Return Quantity.", "danger");

        }
    }
</script>