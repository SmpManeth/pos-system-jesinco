<?php
/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>
<title>Reservation Notes</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <div id="toolbar" class="pull-left">
        <div class="form-inline" role="form">

        <?php
        if ($user->user_type == "superadmin" || $user->user_type == "admin" || $user->user_type == "caler_lvl_1" || $user->user_type == "admin_lvl2") {
            ?>
            <a href="<?php echo base_url("reservation/cancelled-do-list") ?>" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Cancelled DO List</a>
            <?php
        }
        ?>
            <a href="<?php echo base_url("reservation/new-note") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Delivery Order Note</a> 
            <select class="form-control" id="filter-name">
                <option value="">--SELECT--</option>
                <?php
                foreach ($users as $user) {
                    ?>
                    <option value="<?php echo $user->id ?>"><?php echo $user->first_name ?></option>
                    <?php
                }
                ?>
            </select>
            <button type="button" class="btn btn-primary" id="filter-btn"><i class="fa fa-filter"></i> <span>Filter</span></button>
        </div>
    </div>
    <span class="pull-right">
        <span><i class="fa fa-stop text-info"></i> Finished Reservation Notes</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-danger"></i> Cancelled Reservation Notes</span>&nbsp;&nbsp;&nbsp;
        <span><i class="fa fa-stop text-warning"></i> Older than 10 days</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("reservation/get_reservation_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            queryParams: function(params){
                params.username=$("#filter-name").val();
                return params;
            },
            toolbar: '#toolbar',
            sortName: 'do_id',
            sortOrder: "desc",
            columns: [
                {
                    field: 'cusname',
                    title: '<i class="fa fa-user"></i> Customer',
                }, {
                    field: 'inv_id',
                    title: '<i class="fa fa-shopping-cart"></i> DO Number',
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
                    field: 'created',
                    title: '<i class="fa fa-dollar"></i> Created By',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            row.status == "0" ? '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("reservation/edit/") ?>' + (row.inv_id) + '" title="Edit"><i class="fa fa-edit"></i> Edit/View</a>' : '',
                            (row.status == "3" || row.status == "6") ? '<a target="_blank" class="btn btn-outline btn-circle btn-xs green" href="<?php echo base_url("reservation/view/") ?>' + (row.inv_id) + '" title="View Reservation Note"><i class="fa fa-edit"></i> View</a>' : '',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.last_edit_at + ' </span></small>',
                            row.visibility == "1" ? '<i class="fa fa-globe font-green-jungle"></i>' : '',
                            (row.status == "6" && row.cancel_approved == "1") ? '<span class="badge badge-danger"><i class="fa fa-check"></i></span>' : '',
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger', 'finished', 'primary'];
                var cls = "";
                if (row.older == true) {
                    cls = classes[2];
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
                if (row.status == "6") {
                    cls = classes[3];
                }
                return {classes: cls};
            }
        });
        $("#filter-btn").click(function (e) {
            console.log($("#filter-name").val());
            $('#item_table').bootstrapTable('filterBy', {
                username: $("#filter-name").val()
            })
        });
    });

    function mark_as_delivered() {
        DJ.Overlay_confirm({
            title: "Are you want to masr this Reservation note as Delivered?",
            click: function (v) {
                if (v) {

                }
            }
        });
    }
</script>