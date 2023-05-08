<?php ?>
<title>View All Items</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/item/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Item</a>
    <span class="pull-right">
        <span><i class="fa fa-globe font-green-jungle"></i> Global Item</span>&nbsp;&nbsp;<span><i class="fa fa-stop font-red-thunderbird"></i> Disabled Item</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table">
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("register/get_item_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            columns: [
                {
                    field: 'itm_code',
                    title: '<i class="fa fa-code"></i> Item Code',
                    sortable: true
                }, {
                    field: 'itm_name',
                    title: '<i class="fa fa-shopping-cart"></i> Item Name',
                    sortable: true
                }, {
                    field: 'cat_name',
                    title: '<i class="fa fa-bars"></i> Category',
                    formatter: function (value, row) {
                        return value + (row.sub_name !== null ? (" <br/>" + row.sub_name) : "");
                    }
                }, {
                    field: 'cost',
                    title: '<i class="fa fa-dollar"></i> Cost Price',
                    formatter: function (value, row) {
                        return Number((value));
                    }
                }, {
                    field: 'selling',
                    title: '<i class="fa fa-dollar"></i> Selling Price',
                }, {
                    field: 'operate',
                    title: 'Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("register/item/edit/") ?>' + (row.id) + '" title="Edit">',
                            '<i class="fa fa-edit"></i> Edit/View',
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
                if (row.status == "2") {
                    cls = classes[3];
                }
                return {classes: cls};
            }
        });
    });
</script>