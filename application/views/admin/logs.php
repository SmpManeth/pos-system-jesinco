<?php
//Sep 17, 2018 3:47:23 PM 
?>
<title>Logs</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<link href="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css") ?>" rel="stylesheet" type="text/css"/>

<br/>
<div id="toolbar">
<a href="#" id="download-log" class="btn green-haze btn-outline btn-sm sbold uppercase"><i class="fa fa-download"></i>&nbsp;Download Logs</a>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-hover" id="logs_table" 
        data-toggle="table"
        data-search="true"
        data-toolbar="#toolbar"
        data-pagination="true">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Type/ Action</th>
                <th>Description</th>
            </tr>                
        </thead>
        <tbody id="tbody">
            <?php
            if (isset($logs)) {
                foreach ($logs as $log) {
                    ?>
                    <tr>
                        <td><?php
                            $dates = explode(" ", $log->at);
                            ?>
                            <span class="label label-primary label-inverse"><?php echo date("M d, Y",  strtotime($dates[0])) ?></span>
                            <span class="label label-info"><?php echo $dates[1] ?></span>
                            <?php
                            ?></td>
                        <td class="text-uppercase text-success"><?php echo $log->username ?></td>
                        <td class="text-uppercase"><?php echo $log->section ?></td>
                        <td><?php echo $log->action ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<div id="log-download-modal">
    <div>
        <?php echo form_open(base_url("reporter/logs/get_logs"), "class='form-horizontal' method='GET'"); ?>
            <div class="form-group">
                <label for="first_name" class="col-lg-4 control-label">From</label>
                <div class="col-sm-6">
                    <input type="text" name="s"  class="form-control input-sm datepicker" />
                </div>
            </div>
            <div class="form-group">
                <label for="first_name" class="col-lg-4 control-label">To</label>
                <div class="col-sm-6">
                    <input type="text" name="e" class="form-control input-sm datepicker" value="" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6 col-lg-offset-4">
                    <button class="btn btn-primary">Download</button>
                </div>
            </div>
        <?php echo form_close() ?>
    </div>
</div>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $(".datepicker").datepicker({format: "yyyy-mm-dd", autoclose: true, endDate: '<?php echo date("Y-m-d") ?>'});

        $("#download-log").click(function (e) {
            e.preventDefault();
            DJ.show_model({
                title: "Download Logs",
                selector: "#log-download-modal",
                success:function(){

                }
            });
        });
    });
</script>