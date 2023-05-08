<?php ?>
<title>View All Sites</title>
<a href="<?php echo base_url("register/site/new") ?>" class="btn green-haze btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Site</a>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <caption class="text-right">
            <span><i class="fa fa-stop font-red-thunderbird"></i> Disabled Sites</span>&nbsp;&nbsp;&nbsp;
        </caption>
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Site Name </th>
                <th>
                    <i class="fa fa-user"></i> Supervisor</th>
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
            if (isset($sites) && count($sites) > 0) {
                foreach ($sites as $site) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($site->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    ?>
                    <tr class="<?php echo $status == "danger" ? $status : "" ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php echo $fc ?>" href="javascript:;" onclick="view_sup(<?php echo $site->id ?>)"> <?php echo $site->site_name ?> </a>
                        </td>
                        <td> <?php echo $site->emp_name ?></td>
                        <td class="hidden-xs"> 
                            <span><?php echo $site->address_po_box ?></span><?php echo $site->address_po_box ? br() : "" ?>
                            <span><?php echo $site->address_line1 ?></span><?php echo  $site->address_line1 ? br() : "" ?>
                            <span><?php echo $site->address_line2 ?></span><?php echo  $site->address_line2 ? br() : "" ?>
                            <span><?php echo $site->address_city ?></span><?php echo   $site->address_city ? br() : "" ?>
                            <span><?php echo $site->counrty ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $site->tp1 ?></span><?php echo $site->tp1 ? br() : "" ?>
                            <span><?php echo $site->tp2 ?></span>
                        </td>
                        <td> <?php echo $site->email ?></td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("register/site/edit/" . $site->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit</a>
                                        <?php 
                                    if ($site->status == "1") {
                                        ?>
                            <a target="_blank" href="<?php echo base_url("site/equipment/" . $site->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Equipments</a>
                                        <?php
                                    }
                                        ?>
                                <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $site->username?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $site->e_at?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center"><i class="fa fa-user-times"></i> &nbsp;No Sites Available
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>