<?php
//Aug 14, 2018 5:49:52 PM 
?>
<?php echo form_open("#", "class='form-horizontal' id='tgrn_seleform'"); ?>
<?php echo form_hidden("is_ajax_request"); ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <td><input type="checkbox" id="sel_all_none" /></td>
            <td>Item Code</td>
            <td>Item Name</td>
            <td>Quantity</td>
            <td>Price</td>
            <td>Total</td>
            <td>Added Date</td>
        </tr>                
    </thead>
    <tbody id="tgrn_tbody">
        <?php
        if (isset($tgrn_items)) {
            foreach ($tgrn_items as $tgrni) {
                ?>
                <tr>
                    <td><input type="checkbox" name="tgrn_items[]" value="<?php echo $tgrni->id ?>" /></td>
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
<div class="row">
    <div class="col-lg-12 text-right">
        <span class="pull-left alert alert-info">The Items, already added this Purchasing Order will not Added.</span>
        <button type="button" class="btn btn-primary" id="proceed_btn"><span>Proceed</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
    </div>
</div>
<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        $("#sel_all_none").click(function () {
            if ($(this).is(":checked")) {
                $("#tgrn_tbody input[type='checkbox']").prop({checked: true});
            } else {
                $("#tgrn_tbody input[type='checkbox']").prop({checked: false});
            }
        });
        $("#proceed_btn").click(function () {
            DJ.disable_btn("proceed_btn", "Proceeding");
            var ids = $("#tgrn_seleform").serializeArray();

            var id = $("#gr_id").val();
            var supplier = $("#supplier").val();
            var po_date = $("#po_date").val();
            var del_date = $("#del_date").val();
            var po_ref = $("#po_ref").val();
            var del_location = $("#del_location").val();

            ids.push({name: "grn_date", value: po_date});
            ids.push({name: "po_ref", value: po_ref});
            ids.push({name: "supplier", value: supplier});
            ids.push({name: "del_location", value: del_location});
            ids.push({name: "id", value: id});
            ids.push({name: "del_date", value: del_date});
            ids.push({name: "po_ref", value: po_ref});

            $.ajax({
                url: "<?php echo base_url("grn/add_pre_rec_items") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: ids,
                success: function (data) {
                    DJ.enable_btn("proceed_btn", "Proceed");
                    if (data.msg_type == "OK") {
                        $("#cancel_po_btn,#finish_po_btn").show();
                        $("#doc_info b").html(data.display_id);
                        $("#gr_id").val(data.p_id);
                        $("#doc_info").show();
                        add_rows(data.ret_items, data.p_id);
                        $(DJ.modal).modal("hide");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });

    function add_rows(items, po_id) {
        $.each(items, function (i, row) {
            var tr = document.createElement("tr");
            var td1 = document.createElement("td");
            var span = document.createElement("span");
            var td2 = document.createElement("td");
            var td3 = document.createElement("td");
            var td4 = document.createElement("td");
            var td5 = document.createElement("td");
            var a = document.createElement("a");
            $(a).html("<i class='fa fa-remove font-red-thunderbird'></i>").attr({href: "javascript:;"}).click(function () {
                remove_pre_item_po(a, items[i].id);
            });
            $(span).html(items[i].item_code).addClass("small text-muted");
            $(td1).append(items[i].item_name, "&nbsp;&nbsp;", span);
            $(td2).html(items[i].qty);
            $(td3).html(DJ.format_number(items[i].price));
            $(td4).html(items[i].total).addClass("text-right");

            $(td5).append(a);
            $(tr).append(td1, td2, td3, td4, td5).attr({id: "po_" + po_id});
            $("#pre_item_body").append(tr);
        });
        calculate();
    }

</script>
