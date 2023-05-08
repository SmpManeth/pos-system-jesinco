<?php
//Aug 27, 2018 3:15:49 PM 
?>
<title>Supplier Returns</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("grn/returns/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Return Note</a>
    <span class="pull-right">
        <span><i class="fa fa-stop text-success"></i> Finished Invoices</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-danger"></i> Canceled Invoices</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("grn/get_return_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: false,
            order: ['inv_date', "desc"],
            columns: [
                {
                    field: 'supname',
                    title: '<i class="fa fa-user"></i> Supplier',
                }, {
                    field: 'ret_id',
                    title: '<i class="fa fa-shopping-cart"></i> Return No',
                    sortable: true
                }, {
                    field: 'ret_date',
                    title: '<i class="fa fa-calendar"></i> Date',
                    sortable: true
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("grn/returns/edit/") ?>' + (row.ret_id) + '" title="Edit">',
                            '<i class="fa fa-edit"></i> Edit/View',
                            '</a>',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.edit_at + '</span>&nbsp;@<span class="font-green-jungle">' + row.edit_by + ' </span></small>',
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger', 'finished'];
                var cls = "";
                if (row.status == "2") {
                    cls = classes[3];
                }
                if (row.status == "1") {
                    if (Number(row.balance) == 0) {
                        cls = classes[0];
                    } else {
                        cls = classes[1];
                    }
                }
                console.log(row);
                return {classes: cls};
            }
        });
    });
</script>