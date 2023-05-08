<?php
//Aug 20, 2018 6:18:15 PM 
?>
<title>View All Invoices</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<style>
    .ribbon {
        width: 100px;
        height: 150px;
        background-color: #fff;
        position: absolute;
        right: 100px;
        top: -350px;
        -webkit-animation: drop forwards 0.8s 1s cubic-bezier(0.165, 0.84, 0.44, 1);
        animation: drop forwards 0.8s 1s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .ribbon:before {
        content: '';
        position: absolute;
        z-index: 2;
        left: 0;
        bottom: -50px;
        border-left: 50px solid #fff;
        border-right: 50px solid #fff;
        border-bottom: 50px solid transparent;
    }
    .ribbon:after {
        content: '';
        width: 200px;
        height: 270px;
        position: absolute;
        z-index: -1;
        left: 0;
        bottom: -120px;
        background-color: #507abd;
        -webkit-transform: skewY(35deg) skewX(0);
        transform: skewY(35deg) skewX(0);
    }
</style>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <!-- <a href="<?php echo base_url("invoice/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Invoice</a> -->
    <div class="pull-left" id="toolbar">
        <div class="form-inline" role="form">
            <select class="form-control" id="devision_id">
                <option value="">--SELECT--</option>
                <?php
                if (isset($devisions)) {
                    foreach ($devisions as $devision) {
                        ?>
                        <option value="<?php echo $devision->id ?>"><?php echo $devision->devision ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <button class="btn btn-default" id='filterNow' type="button">Filter</button>    
        </div><!-- /input-group -->
    </div>

    <span class="pull-left">
        <?php
        if ($user->user_type == "superadmin" || $user->user_type == "admin") {
            ?>
            <a href="<?php echo base_url("invoice/cancelled-invoices") ?>" class="btn btn-danger">Cancelled Invoices</a>
            <a href="<?php echo base_url("invoice/completed-invoices") ?>" class="btn btn-success">Completed Invoices</a>
            <?php
        }
        ?>
    </span>
    <span class="pull-right">
        <span><i class="fa fa-stop text-primary"></i> Finished Invoices</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-info"></i> Reservation Invoices</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-success"></i> Payment Completed Invoices</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop font-red-thunderbird"></i> Canceled Invoices</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("invoice/get_invoice_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            toolbar: "#toolbar",
            queryParams: function (params) {
                params.devision_id = $("#devision_id").val()
                return params;
            },
            columns: [
                {
                    field: 'cusname',
                    title: '<i class="fa fa-user"></i> Customer'
                }, {
                    field: 'inv_id',
                    title: '<i class="fa fa-shopping-cart"></i> Invoice No',
                    sortable: true
                }, {
                    field: 'do_number',
                    title: '<i class="fa fa-shopping-cart"></i> DO Number',
                }, {
                    field: 'inv_date',
                    title: '<i class="fa fa-calendar"></i> Date',
                    sortable: true
                }, {
                    field: 'total',
                    title: '<i class="fa fa-dollar"></i> Amount',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            row.status == "0" ? '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("invoice/edit/") ?>' + (row.inv_id) + '" title="Edit"><i class="fa fa-edit"></i> Edit/View</a>' : '',
                            (row.status == "3" || row.status == "1" || row.status == "2" || row.status == "4") ? '<a target="_blank" class="btn btn-outline btn-circle btn-xs green" href="<?php echo base_url("invoice/view/") ?>' + (row.inv_id) + '" title="Edit"><i class="fa fa-edit"></i> View</a>' : '',
                            (row.status == "4" || row.status == "1") ? '<a target="_blank" class="btn btn-outline btn-circle btn-xs btn-primary" href="<?php echo base_url("invoice/payments/") ?>' + (row.inv_id) + '" title="Edit"><i class="fa fa-edit"></i> Payment</a>' : '',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.last_edit_at + ' </span></small>',
                            row.visibility == "1" ? '<i class="fa fa-globe font-green-jungle"></i>' : '',
                            (row.status == "1" && row.cancel_approved == "1") ? '<span class="badge badge-success"><i class="fa fa-check"></i></span>' : '',
                            (row.status == "2" && row.cancel_approved == "1") ? '<span class="badge badge-danger"><i class="fa fa-check"></i></span>' : '',
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger', 'finished', 'primary'];
                var cls = "";
                if (row.status == "2") {
                    cls = classes[3];
                }
                if (row.status == "3") {
                    cls = classes[1];
                }
                if (row.status == "1") {
                    if (Number(row.balance) == 0) {
                        cls = classes[0];
                    } else {
                        cls = classes[5];
                    }
                }
                return {classes: cls};
            }
        });
        $('#filterNow').click(function () {
            var devision_id = $('#devision_id').val();

            $('#item_table').bootstrapTable('filterBy', {
                devision_id: devision_id
            });
        });
    });
</script>