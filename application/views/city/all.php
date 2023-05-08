<title>View All Cities</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("city/get_city_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            sortOrder: "desc",
            columns: [
                {
                    field: 'city',
                    title: '<i class="fa fa-user"></i> City',
                }, {
                    field: 'city_alt1',
                    title: '<i class="fa fa-shopping-cart"></i> City Alt',
                }, {
                    field: 'last_updated',
                    title: '<i class="fa fa-calendar"></i> Last Update',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("city/edit-city/") ?>' + (row.id) + '" title="Edit">',
                            '<i class="fa fa-edit"></i> Edit/View',
                            '</a>'
                        ].join('');
                    }
                },
            ]
        });
    });
</script>