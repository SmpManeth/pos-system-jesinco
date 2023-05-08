<?php ?>
<ul class="list-group">
    <?php
    foreach ($installments as $installment) {
        ?>
        <li class="list-group-item text-left form-horizontal installment">
            <span><?php echo $installment->month ?></span> 
            <a class="badge label label-success" href="javascript:void(0)" onclick="edit_month(<?php echo $installment->id ?>, '<?php echo $installment->month ?>')"><i class="fa fa-edit"></i></a>
            <div class="checkbox pull-right">
                <label><input type="checkbox" onchange="edit_month_status(this)" data-id="<?php echo $installment->id ?>" <?php echo $installment->status=="1"?"checked":"" ?> name="visibility"><span></span></label>
            </div>
        </li>
        <?php
    }
    ?>
</ul> 