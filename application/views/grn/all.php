<title>View All GRNs</title>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("grn/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Good Receive Note</a>
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
                    <i class="fa fa-edit"></i> G R N No</th>
                <th class="hidden-xs">
                    <i class="fa fa-calendar"></i> Date</th>
                <th class="hidden-xs">
                    <i class="fa fa-edit"></i> P O Reference</th>
                <th>
                    <i class="fa fa-dollar"></i> Amount</th>
                <th>
                    <i class="fa fa-cog"></i> Options</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($grns) && count($grns) > 0) {
                foreach ($grns as $grn) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($grn->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    if ($grn->status == "0") {
                        $status = '';
                    }
                    if ($grn->status == "1") {
                        $status = 'success';
                    }
                    ?>
                    <tr class="<?php echo $status ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php // echo $fc    ?>" href="<?php echo base_url("register/supplier/edit/" . $grn->supplier) ?>" onclick="view_items(<?php echo $grn->id ?>)"> <?php echo $grn->company_name ?> </a>
                        </td>

                        <td> <?php echo decorate_code($grn->gr_id, "grn",$this->prefixes) ?></td>
                        <td> <?php echo $grn->grn_date ?></td>
                        <td> <?php echo $grn->po_ref ?></td>
                        <td> <?php echo is_zero($grn->total) ?></td>
                        <td>
                            <?php
                            $dec_id = decorate_code($grn->gr_id, "grn", $this->prefixes);
                            if ($grn->status == "0") {
                                ?>
                                <a target="_blank" href="<?php echo base_url("grn/edit/" . $dec_id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                    <i class="fa fa-edit"></i>Edit</a>
                                <?php
                            }
                            if ($grn->status == "1" || $grn->status == "2") {
                                ?>
                                <a target="_blank" href="<?php echo base_url("grn/view/" . $dec_id) ?>" class="btn btn-outline btn-circle btn-xs blue">
                                    <i class="fa fa-print"></i>View</a>
                                <?php
                            }
                            ?>
                            <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $grn->username ?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $grn->e_at ?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center"><i class="fa fa-file-o"></i> &nbsp;No Goods Receive Notes
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>