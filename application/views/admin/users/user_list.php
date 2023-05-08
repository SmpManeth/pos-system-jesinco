<?php
//Sep 19, 2018 8:28:39 AM 
?>
<title>Users List</title>
<div class="sub-header-wrapper">
    <h4 class="pull-left"><strong>Users List</strong></h4>
    <span class="pull-right">
        <span><i class="fa fa-stop font-red-thunderbird"></i> Inactive Users</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>E-mail</th>
                <th>Last Login</th>
                <th>User Type</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($users)) {
                foreach ($users as $usr) {
                    $active_class = $usr->active != "1" ? "danger" : "";
                    ?>
                    <tr class="<?php echo $active_class ?>">
                        <td><?php echo $usr->first_name . " " . $usr->last_name ?></td>
                        <td><?php echo $usr->username ?></td>
                        <td><?php echo $usr->email ?></td>
                        <td><?php echo date("M d, Y h:i a", ($usr->last_login)) ?></td>
                        <td><?php echo $usr->display_val ?></td>
                        <td><?php echo $usr->active == "1" ? "Active" : "In-active" ?></td>
                        <td><a href="<?php echo base_url("admin/user-manager/edit-user/" . $usr->id) ?>" class="btn btn-xs btn-outline purple">Manage</a></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>