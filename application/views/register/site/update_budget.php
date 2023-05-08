<title>Edit Site Budget</title>
<div class="page-content-col">
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-9 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <?php echo form_open("#", "class='form-horizontal' id='update_sitebudget_form'"); ?>
                    <?php echo form_hidden("is_ajax_request"); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Site Name :</label>
                            <div class="col-md-7">
                                <input type="hidden" value="<?php echo $site->id ?>" name="id" />
                                <p class="form-control input-sm-static"><?php echo $site->site_name ?></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Address :</label>
                            <div class="col-md-5">
                                <?php
                                if ($site->address_po_box) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $site->address_po_box ?></p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($site->address_line1) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $site->address_line1 ?></p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($site->address_line2) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $site->address_line2 ?></p>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($site->address_city) {
                                    ?>
                                    <p class="form-control input-sm-static"><?php echo $site->address_city ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone :</label>
                            <div class="col-md-9">
                                <p class="form-control input-sm-static"><?php echo $site->tp1 ?>  ,  <?php echo $site->tp2 ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Address :</label>
                            <div class="col-md-9">
                                <p class="form-control input-sm-static"><?php echo $site->email ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Supervisor :</label>
                            <div class="col-md-7">
                                <p class="form-control input-sm-static"><?php echo $site->emp_prefix . " " . $site->emp_name ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Budget Amount :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm input-medium number" name="amount" id="amount" value="<?php echo $amount ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="button" class="btn green" id="add_site_btn"><span>Submit</span>&nbsp;&nbsp;<img src="<?php echo base_url("assets/images/377.gif") ?>" width="20" class="hidden"/></button>
                                <button type="reset" class="btn default">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
        <?php
        if (isset($changes) && count($changes) > 0) {
            ?>
            <div class="col-lg-9">
                <legend>Recent Employees worked on th Site</legend>
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Removed Date Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($changes)) {
                            foreach ($changes as $change) {
                                ?>
                                <tr>
                                    <td><?php echo $change->emp_prefix . " " . $change->emp_name ?></td>
                                    <td><?php echo $change->r_date ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".number").number(true, 2);
        $("#add_site_btn").click(function () {
            DJ.disable_btn("add_site_btn", "Saving");
            $.ajax({
                url: "<?php echo base_url("site/update_site_budget") ?>",
                type: 'POST',
                dataType: 'JSON',
                data: $("#update_sitebudget_form").serialize(),
                success: function (data) {
                    DJ.enable_btn("add_site_btn", "Submit");
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $("#new_cus_form").trigger("reset");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    });
</script>