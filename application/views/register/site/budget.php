<?php ?>
<title>View Sites & Budgets</title>
<a href="<?php echo base_url("register/site/new") ?>" class="btn green-haze btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Site</a>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Site Name </th>
                <th>
                    <i class="fa fa-user"></i> Supervisor</th>
                <th>
                    <i class="fa fa-phone"></i> Telephone </th>
                <th>
                    <i class="fa fa-phone"></i> Current Budget</th>
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
                            <span><?php echo $site->tp1 ?></span><?php echo $site->tp1 ? br() : "" ?>
                            <span><?php echo $site->tp2 ?></span>
                        </td>
                        <td> <?php echo is_zero($site->amount) ?></td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("site/budget/update/" . $site->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-dollar"></i>Edit Budget</a>
                            <a target="_blank" href="<?php echo base_url("register/site/edit/" . $site->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit Site</a>
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