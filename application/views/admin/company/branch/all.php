<?php ?>
<title>View All Customers</title>
<a href="<?php echo base_url("wl-admin/branches/new") ?>" class="btn green-haze btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Branch</a>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <caption class="text-right">
            <span><i class="fa fa-stop font-red-thunderbird"></i> Disabled Branches</span>&nbsp;&nbsp;&nbsp;
        </caption>
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Branch </th>
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
            if (isset($branches) && count($branches) > 0) {
                foreach ($branches as $branch) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($branch->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    ?>
                    <tr class="<?php echo $status == "danger" ? $status : "" ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php echo $fc ?>" href="javascript:;" onclick="view_sup(<?php echo $branch->id ?>)"> <?php echo $branch->branch_name?> </a>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $branch->address_po_box ?></span><?php echo $branch->address_po_box ? br() : "" ?>
                            <span><?php echo $branch->address_line1 ?></span><?php echo  $branch->address_line1 ? br() : "" ?>
                            <span><?php echo $branch->address_line2 ?></span><?php echo  $branch->address_line2 ? br() : "" ?>
                            <span><?php echo $branch->address_city ?></span><?php echo   $branch->address_city ? br() : "" ?>
                            <span><?php echo $branch->counrty ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $branch->tp1 ?></span><?php echo $branch->tp1 ? br() : "" ?>
                            <span><?php echo $branch->tp2 ?></span>
                        </td>
                        <td> <?php echo $branch->email ?></td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("wl-admin/branches/edit/" . $branch->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit</a>
                                <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $branch->username?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $branch->e_at?></span></small>
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