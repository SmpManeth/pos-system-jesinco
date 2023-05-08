<?php
//Aug 24, 2018 12:05:21 PM 
?>
<table class="table table-bordered">
    <caption>
        <span><i class="fa fa-square text-danger"></i> Canceled Returns</span>
    </caption>
    <thead>
        <tr>
            <td>#</td>
            <td>Item</td>
            <td>Return Quantity</td>
            <td>With Refund</td>
            <td></td>
        </tr>
    </thead>
    <tbody id="tbody">
        <?php
        if (isset($ret_items)) {
            $i = 0;
            foreach ($ret_items as $ret_item) {
                $i++;
                ?>
                <tr class="<?php echo $ret_item->status == "2" ? "danger" : "" ?>">
                    <td><?php echo $i ?></td>
                    <td>
                        <?php echo $ret_item->itm_name ?><br/>
                        <small class="text-muted"><?php echo $ret_item->itm_code ?></small>
                    </td>
                    <td>
                        <?php echo $ret_item->ret_qty ?>
                        <?php echo doubleval($ret_item->ret_qty) !== doubleval($ret_item->displaY_qty) ? ("<br/> Display: " . is_zero($ret_item->displaY_qty)) : "" ?>
                    </td>
                    <td>
                        <?php echo $ret_item->with_refund=="1"?"Yes":"No" ?>
                    </td>
                    <td>
                        <?php
                        if ($ret_item->status == "1") {
                            ?>
                            <a href="#/" onclick="remove_return(<?php echo $ret_item->ret_id ?>, this, event)"><i class="fa fa-times text-danger"></i></a>
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
<script>
    function remove_return(id, ele, e) {
        e.preventDefault();
        DJ.Confirm("Are you want to cancel this Return Entry?", function () {
            $(ele).find("i").removeClass("fa-times").addClass("fa-spin fa-spinner");
            $.ajax({
                url: "<?php echo base_url("invoice/cancel_invoice_return") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data) {
                    $(ele).find("i").removeClass("fa-spin fa-spinner").addClass("fa-times");
                    if (data.msg_type == "OK") {
                        $(ele).remove();
                        $("#return_item_table").html('<h4 class="text-center"> Loading... <i class="fa fa-spin fa-spinner"></i></h4>');
//                        $("#return_item_table").load("<?php echo base_url("invoice/get_returns") ?>", {id:<?php echo $inv_id ?>});
                        window.location.reload();
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>