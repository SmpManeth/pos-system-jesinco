<?php ?>
<title>View All Employees</title>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/employee/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Employee</a>
    <span class="pull-right"><span><i class="fa fa-stop font-red-thunderbird"></i> Inactive Employees</span>&nbsp;&nbsp;&nbsp;</span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-briefcase"></i> Employee </th>
                <th>
                    <i class="fa fa-info"></i> Designation</th>
                <th class="hidden-xs">
                    <i class="fa fa-building-o"></i> Address</th>
                <th>
                    <i class="fa fa-phone"></i> Telephone </th>
                <th>
                    <i class="fa fa-envelope"></i> Email</th>
                <th>
                    <i class="fa fa-cog"></i> Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($employees) && count($employees) > 0) {
                foreach ($employees as $employee) {
                    $status = 'success';
                    $fc = '';
                    if ($employee->status == "2") {
                        $status = 'danger';
                        $fc = 'font-red-thunderbird';
                    }
                    ?>
                    <tr class="<?php echo $status == "danger" ? $status : "" ?>">
                        <td class="highlight">
                            <div class="<?php echo $status ?>"></div>
                            <a class="<?php echo $fc ?>" href="javascript:;" onclick="view_sup(<?php echo $employee->id ?>)"> <?php echo $employee->emp_prefix . " " . $employee->emp_name ?> </a>
                        </td>
                        <td> 
                            <span><?php echo $employee->designation ?></span>
                        </td>
                        <td class="hidden-xs"> 
                            <span><?php echo $employee->address_po_box ?></span><?php echo $employee->address_po_box ? br() : "" ?>
                            <span><?php echo $employee->address_line1 ?></span><?php echo $employee->address_line1 ? br() : "" ?>
                            <span><?php echo $employee->address_line2 ?></span><?php echo $employee->address_line2 ? br() : "" ?>
                            <span><?php echo $employee->address_city ?></span><?php echo $employee->address_city ? br() : "" ?>
                            <span><?php echo $employee->counrty ?></span>
                        </td>
                        <td> 
                            <span><?php echo $employee->tp1 ?></span><?php echo $employee->tp1 ? br() : "" ?>
                            <span><?php echo $employee->tp2 ?></span><?php echo $employee->tp2 ? br() : "" ?>
                            <span><?php echo $employee->office_ext ?></span>
                        </td>
                        <td> 
                            <span><?php echo $employee->o_email ?><?php echo $employee->o_email ? br() : "" ?></span>
                            <span><?php echo $employee->p_email ?></span>
                        </td>
                        <td>
                            <a target="_blank" href="<?php echo base_url("register/employee/edit/" . $employee->id) ?>" class="btn btn-outline btn-circle btn-xs purple">
                                <i class="fa fa-edit"></i>Edit</a>
                            <button type="button" class="btn btn-primary btn-xs sendmessage" data-id="<?php echo $employee->id ?>"><i class="fa fa-envelope"></i> <span>Send Message</span></button>
                            <br/><small>Last Edit By : <span class="font-blue-madison"><?php echo $employee->username ?></span>&nbsp;@<span class="font-green-jungle"> <?php echo $employee->e_at ?></span></small>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center"><i class="fa fa-user-times"></i> &nbsp;No Employees
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $(".sendmessage").click(function (e) {
            var id = $(this).data("id");

            DJ.Overlay_input({
                title: "Enter Message Content",
                placeholder: "Message",
                type: "textarea",
                no_empty: false,
                button: {
                    yes: {txt: "SEND"},
                    no: {txt: "CANCEL"}
                },
                click: function (msg) {
                    $.post("<?php echo base_url("users/send_message"); ?>", {id: id, msg, msg}, function (data) {
                        if (data.msg_type == "OK") {
                            DJ.Notify(data.msg, "success");
                        } else {
                            DJ.Notify(data.msg, "danger");
                        }
                    }, "JSON");

                }
            });
        });
    });
</script>
