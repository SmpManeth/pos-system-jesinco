<?php ?>
<title>View All Customers</title>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/customer/new") ?>" class="btn green-haze btn-outline btn-sm sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Customer</a>
    <span class="pull-right">&nbsp;&nbsp;&nbsp;<span><i class="fa fa-globe font-green-jungle"></i> Global Customers</span>&nbsp;&nbsp;<span><i class="fa fa-stop font-red-thunderbird"></i> Disabled Customers</span>&nbsp;&nbsp;&nbsp;</span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Customer </th>
                <th class="hidden-xs">
                    <i class="fa fa-building-o"></i> Address</th>
                <th>
                    <i class="fa fa-phone"></i> Telephone </th>
                <th>
                    <i class="fa fa-phone"></i> Email</th>
                <th>
                    <i class="fa fa-cog"></i> Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($customers) && count($customers) > 0) {
                foreach ($customers as $customer) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($customer->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    ?>
                    <tr class="<?php echo $status == "danger" ? $status : "" ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php echo $fc ?>" href="javascript:;" onclick="view_sup(<?php echo $customer->id ?>)"> <?php echo $customer->customer_prefix . " " . $customer->customer_name ?> </a>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $customer->address_po_box ?></span><?php echo $customer->address_po_box ? br() : "" ?>
                            <span><?php echo $customer->address_line1 ?></span><?php echo $customer->address_line1 ? br() : "" ?>
                            <span><?php echo $customer->address_line2 ?></span><?php echo $customer->address_line2 ? br() : "" ?>
                            <span><?php echo $customer->address_city ?></span><?php echo $customer->address_city ? br() : "" ?>
                            <span><?php echo $customer->counrty ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $customer->tp1 ?></span><?php echo $customer->tp1 ? br() : "" ?>
                            <span><?php echo $customer->tp2 ?></span>
                        </td>
                        <td> <?php echo $customer->email ?></td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("register/customer/edit/" . $customer->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit</a>
                            <?php
                            if ($customer->visibility == "0") {
                                ?>
                                <i class="fa fa-globe font-green-jungle"></i>
                                <?php
                            }
                            ?>
                            <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $customer->username ?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $customer->e_at ?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center"><i class="fa fa-user-times"></i> &nbsp;No Suppliers
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>