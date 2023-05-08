<?php ?>
<title>View All Due Pay Invoices</title>
<div class="sub-header-wrapper">
    <!-- <a href="<?php echo base_url("invoice/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Invoice</a> -->
    <span class="pull-left">
    <div class="form-group">
        <label class="col-lg-4 control-label">Devision</label>
        <div class="col-sm-8">
        <select name="devision" id="devision" class="form-control input-sm">
                <option value="">-ALL-</option>        
                <?php
                foreach ($devisions as $devision) {
                    ?>
                    <option value="<?php echo $devision->id ?>"><?php echo $devision->devision ?></option>        
                    <?php 
                }
                ?>
            </select>
        </div>
    </div>
        
    </span>
    <span class="pull-right">
        <span><i class="fa fa-stop text-success"></i> Due Before 3 Days</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-info"></i> Due Today</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-warning"></i> Due After within 3 Days</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop font-red-thunderbird"></i> Due After 3 Days</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id='due_pays'>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Invoice Amount</th>
                <th>Due Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($due_payments)) {
                foreach ($due_payments as $due_pay) {
                    $doc_id = decorate_code($due_pay->inv_id, "invoice", $this->prefixes);
                    $_diff = date_different(date("Y-m-d"), $due_pay->next_installment_date);
                    $late = is_date_greater_eq_than_last(date("Y-m-d"), $due_pay->next_installment_date);
                    $class = "";
                    if ($_diff == 0) {
                        $class = "info";
                    } else {
                        if ($late) {
                            if ($_diff >= 0 && $_diff <= 3) {
                                $class = "warning";
                            } else {
                                $class = "danger";
                            }
                        } else {
                            if ($_diff >= 0) {
                                $class = "success";
                            }
                        }
                    }
                    ?>
                    <tr class="<?php echo $class ?>" data-filter="<?php echo !empty($due_pay->devision_id)?$due_pay->devision_id:'-1' ?>">
                        <td><a href="<?php echo base_url("invoice/payments/".$doc_id); ?>"><?php echo $doc_id ?></a></td>
                        <td><?php echo $due_pay->customer_prefix . " " . $due_pay->customer_name ?></td>
                        <td><?php echo $due_pay->inv_date ?></td>
                        <td><?php echo is_zero($due_pay->total) ?></td>
                        <td><?php echo $due_pay->next_installment_date ?></td>
                        <td><?php echo is_zero($due_pay->installment_amount) ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    $("#devision").change(function(){
        $("#due_pays tr").show();

        var dev_id = this.value;
        if(dev_id){
            $("#due_pays tbody tr[data-filter != '"+dev_id+"']").hide();
        }


    });
});
</script>
