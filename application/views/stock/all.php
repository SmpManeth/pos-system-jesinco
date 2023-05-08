<?php
//Jun 26, 2018 11:43:52 AM 
?>

<title>View All Items</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/item/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Item</a>
    <a href="<?php echo base_url("reporter/stock/download-all-items") ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i><span> Download All Data</span></a>
    <?php
    if ($user->username == "superadmin") {
        ?>
            <!--<a href="<?php echo base_url("stock/manual-adjust") ?>" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i><span> Manual Adjust (Superadmin)</span></a>-->
        <?php
    }
    ?>
    <span class="pull-right">
        <span><i class="fa fa-globe font-green-jungle"></i> Global Item</span>&nbsp;&nbsp;<span><i class="fa fa-stop font-red-thunderbird"></i> Reached Minimum Stock</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="stock_table">
    </table>
</div>
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $('#stock_table').bootstrapTable({
            url: "<?php echo base_url("stock/get_stock") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            columns: [
                {
                    field: 'itm_code',
                    title: '<i class="fa fa-code"></i> Item Code ',
                    sortable: true
                }, {
                    field: 'itm_name',
                    title: '<i class="fa fa-shopping-cart"></i> Item Name',
                    sortable: true
                }, {
                    field: 'cat_name',
                    title: '<i class="fa fa-bars"></i> Category '
                }, {
                    field: 'selling',
                    title: '<i class="fa fa-dollar"></i> Selling Price',
                }, {
                    field: 'qty',
                    title: '<i class="fa fa-dollar"></i> Quantity',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Options',
                    align: 'left',
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("stock/adjutment/") ?>' + (row.itm_id) + '" title="Edit">',
                            '<i class="fa fa-exchange fa-rotate-90"></i> Adjust/View',
                            '</a>',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.e_at + ' </span></small>',
                            row.visibility == "0" ? '<i class="fa fa-globe font-green-jungle"></i>' : '',
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger'];
                var cls = "";
                if (Number(row.qty) <= Number(row.minimum_stock_warn)) {
                    cls = classes[3];
                }
                return {classes: cls};
            }
        });
    });
</script>