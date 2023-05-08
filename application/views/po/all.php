<title>View All Orders</title>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("po/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Purchasing Order</a>
    <span class="pull-right">
        <span><i class="fa fa-stop font-green-jungle"></i> Finished Purchasing Orders</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop font-red-thunderbird"></i> Canceled Purchasing Orders</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-ship"></i> Supplier </th>
                <th class="hidden-xs">
                    <i class="fa fa-edit"></i> P O Reference</th>
                <th>
                    <i class="fa fa-calendar"></i> P O Date</th>
                <th>
                    <i class="fa fa-calendar-check-o"></i> Delivery Date </th>
                <th>
                    <i class="fa fa-dollar"></i> Amount</th>
                <th>
                    <i class="fa fa-cog"></i> Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($orders) && count($orders) > 0) {
                foreach ($orders as $order) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($order->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    if ($order->status == "0") {
                        $status = '';
                    }
                    if ($order->status == "1") {
                        $status = 'success';
                    }
                    ?>
                    <tr class="<?php echo $status ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php // echo $fc  ?>" href="javascript:;" onclick="view_items(<?php echo $order->id ?>)"> <?php echo $order->company_name ?> </a>
                        </td>

                        <td class="hidden-xs"> 
                            <span><?php echo $order->po_ref ?></span>
                        </td>
                        <td> <?php echo $order->p_date ?></td>
                        <td> <?php echo $order->del_date ?></td>
                        <td> <?php echo is_zero($order->total) ?></td>
                        <td>
                            <?php
                            if ($order->status == "0") {
                                ?>
                            <a target="_blank" href="<?php echo base_url("po/edit/" . decorate_code($order->po_id, "po", $this->prefixes)) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                    <i class="fa fa-edit"></i>Edit</a>
                                <?php
                            }
                            if ($order->status == "1" || $order->status == "2") {
                                ?>
                                <a target="_blank" href="<?php echo base_url("po/view/" .  decorate_code($order->po_id, "po", $this->prefixes)) ?>" class="btn btn-outline btn-circle btn-xs blue">
                                    <i class="fa fa-print"></i>View</a>
                                <?php
                            }
                            ?>
                            <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $order->username ?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $order->e_at ?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center"><i class="fa fa-file-o"></i> &nbsp;No Purchasing Orders
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>