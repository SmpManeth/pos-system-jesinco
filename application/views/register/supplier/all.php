<?php ?>
<title>View All Suppliers</title>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/supplier/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Supplier</a>
    <span class="pull-right">&nbsp;&nbsp;&nbsp;<span ><i class="fa fa-globe font-green-jungle"></i> Global Suppliers</span>&nbsp;&nbsp;<span><i class="fa fa-stop font-red-thunderbird"></i> Inactive Suppliers</span></span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Company </th>
                <th class="hidden-xs">
                    <i class="fa fa-user"></i> Contact Person</th>
                <th class="hidden-xs">
                    <i class="fa fa-user"></i> Contact Person</th>
                <th>
                    <i class="fa fa-phone"></i> Telephone </th>
                <th>
                    <i class="fa fa-"></i> Nature of Business  </th>
                <th>
                    <i class="fa fa-cog"></i> Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($suppliers)) {
                foreach ($suppliers as $supplier) {
                    $status = 'success';
                    $fc = 'font-green-jungle';
                    if ($supplier->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    ?>
                    <tr class="<?php echo $status == "danger" ? $status : "" ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php echo $fc ?>" href="javascript:open_supplier(<?php echo $supplier->id ?>);" onclick="view_sup(<?php echo $supplier->id ?>)"> <?php echo $supplier->company_name ?> </a>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $supplier->contact_person_prefix1 . " " . $supplier->contact_person1 ?></span><?php echo $supplier->contact_person1 ? br() : "" ?>
                            <span><?php echo $supplier->contact_person_tp1 ?></span><?php echo $supplier->contact_person_tp1 ? br() : "" ?>
                            <span><?php echo $supplier->contact_person_email1 ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $supplier->contact_person_prefix2 . " " . $supplier->contact_person2 ?></span><?php echo $supplier->contact_person2 ? br() : "" ?>
                            <span><?php echo $supplier->contact_person_tp2 ?></span><?php echo $supplier->contact_person_tp2 ? br() : "" ?>
                            <span><?php echo $supplier->contact_person_email2 ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $supplier->tp1 ?></span><?php echo $supplier->tp1 ? br() : "" ?>
                            <span><?php echo $supplier->tp2 ?></span>
                        </td>
                        <td> <?php echo $supplier->bis_type ?></td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("register/supplier/edit/" . $supplier->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit</a>
                            <?php
                            if ($supplier->visibility == "0") {
                                ?>
                                <i class="fa fa-globe font-green-jungle"></i>
                                <?php
                            }
                            ?>
                            <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $supplier->username ?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $supplier->e_at ?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } if (count($suppliers) == 0) {
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
<script>
    function open_supplier(id) {

    }
</script>