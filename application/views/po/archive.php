<?php
//Aug 8, 2018 4:40:39 PM 
?>
<title>View Archived Orders</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<div class="sub-header-wrapper">
    <span class="pull-right">
        <span><i class="fa fa-stop font-green-jungle"></i> Finished Purchasing Orders</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop font-red-thunderbird"></i> Canceled Purchasing Orders</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="">
    <table class="table table-striped table-bordered table-advance table-hover" id="archive_table" data-row-style="rowStyle">
        <thead>
            <tr>
                <th data-field="company_name">
                    <i class="fa fa-ship"></i> Supplier </th>
                <th data-field="po_ref" class="hidden-xs">
                    <i class="fa fa-edit"></i> P O </th>
                <th data-field="p_date">
                    <i class="fa fa-calendar"></i> P O Date</th>
                <th data-field="del_date">
                    <i class="fa fa-calendar-check-o"></i> Delivery Date </th>
                <th data-field="total">
                    <i class="fa fa-dollar"></i> Amount</th>
                <th data-field="status">
                    <i class="fa fa-cog"></i> Options</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div id="archive_table_jtable"></div>
</div>

<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<script>
    $(document).ready(function () {
        $('#archive_table').bootstrapTable({
            url: "<?php echo base_url("po/get_po_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            columns: [
                {
                    field: 'company_name',
                    title: '<i class="fa fa-ship"></i> Supplier',
                    sortable: true
                }, {
                    field: 'po_id',
                    title: '<i class="fa fa-edit"></i> P O'
                }, {
                    field: 'p_date',
                    title: '<i class="fa fa-calendar"></i> P O Date',
                    sortable: true
                }, {
                    field: 'del_date',
                    title: '<i class="fa fa-calendar-check-o"></i> Delivery Date',
                    sortable: true
                }, {
                    field: 'total',
                    title: '<i class="fa fa-dollar"></i> Amount',
                }, {
                    field: 'operate',
                    title: '',
                    align: 'center',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="like" href="<?php echo base_url("po/view/") ?>' + (row.po_id) + '" title="Edit/View">',
                            '<i class="fa fa-edit"></i> Edit/View',
                            '</a>'
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger'];
                var cls = "";
                if (row.status == "1") {
                    cls = classes[0];
                }
                if (row.status == "2") {
                    cls = classes[3];
                }
                return {classes: cls};
            }
        });
    });
</script>