<?php
//Sep 18, 2018 11:09:50 AM 
?>
<title>View All Goods Issue Notes</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("goods-issue/new-issue") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Goods Issue Note</a>
    <span class="pull-right">
        <span><i class="fa fa-stop text-info"></i> Finished Goods Issue Notes</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop font-red-thunderbird"></i> Canceled Goods Issue Notes</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("goods-issue/get_issue_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: false,
            order: ["gi_id", "desc"],
            columns: [
                {
                    field: 'shop',
                    title: '<i class="fa fa-user"></i> Shop Name',
                }, {
                    field: 'gi_id',
                    title: '<i class="fa fa-shopping-cart"></i> Issue No',
                    sortable: true
                }, {
                    field: 'gi_date',
                    title: '<i class="fa fa-calendar"></i> Date',
                    sortable: true
                }, {
                    field: 'total',
                    title: '<i class="fa fa-dollar"></i> Amount',
//                    formatter: function (value, row) {
//                        return DJ.format_number(value);
//                    }
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            row.status=="0"?'<a target="_blank" class="btn btn-outline btn-circle btn-xs yellow-casablanca" href="<?php echo base_url("goods-issue/edit-issue/") ?>' + (row.gi_id) + '" title="Edit"><i class="fa fa-edit"></i> Edit</a>':'',
                            row.status=="1" || row.status=="2"?'<a target="_blank" class="btn btn-outline btn-circle btn-xs purple-sharp" href="<?php echo base_url("goods-issue/view/") ?>' + (row.gi_id) + '" title="Edit"><i class="fa fa-edit"></i> View</a>':'',
                            ,
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.last_edit_at + ' </span></small>'
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
                    cls = classes[0];
                }
                return {classes: cls};
            }
        });
    });
</script>