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
<title>View Un-Approved Customers</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <div id="toolbar">
        <a href="<?php echo base_url("register/customer/new") ?>" class="btn green-haze btn-outline btn-sm sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Customer</a>
    </div>
    <span class="pull-right">
        <span><i class="fa fa-stop font-red-thunderbird"></i> Inactive Customers</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        var can_user_approve = <?php echo user_can($user, CAN_APPROVE_CUSTOMER)?'true':'false' ?>;
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("register_c/get_customer_list_un_approved") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            uniqueId: 'id',
            toolbar: '#toolbar',
            sortName: 'customer_name',
            sortOrder: "desc",
            columns: [
                {
                    field: 'customer_name',
                    title: '<i class="fa fa-user"></i> Customer',
                    formatter: function (value, row, index) {
                        return [
                            row.customer_prefix + " " + row.customer_name,
                            "<small>" + row.nic + "</small>"
                        ].join('<br/>');
                    }
                }, {
                    field: 'inv_id',
                    title: '<i class="fa fa-map-marker"></i> Address',
                    sortable: true,
                    formatter: function (value, row, index) {
                        return [
                            row.address_line1,
                            row.address_line2,
                            row.address_city
                        ].join('<br/>,');
                    }
                }, {
                    field: 'tp1',
                    title: '<i class="fa fa-phone"></i> Telephone',
                    formatter: function (value, row, index) {
                        return [
                            row.tp1,
                            row.tp2
                        ].join('<br/>,');
                    }
                }, {
                    field: 'email',
                    title: '<i class="fa fa-email"></i> Email',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("register/customer/edit/") ?>' + (row.id) + '" title="Edit"><i class="fa fa-edit"></i> Edit/View</a>',
                            can_user_approve?'<a class="btn btn-outline btn-circle btn-xs green" onclick="approve_customer(' + row.id + ',event)" href="#/" title="Edit"><i class="fa fa-edit"></i> Approve</a>':'',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.e_at + ' </span></small>',
                            row.visibility == "1" ? '<i class="fa fa-globe font-green-jungle"></i>' : '',
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
                return {classes: cls};
            }
        });
    });
    function approve_customer(id, event) {
        event.preventDefault();
        DJ.Confirm('Are you wanto approve this customer?', function () {
            $.ajax({
                url: "<?php echo site_url("register_c/approve") ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                        $('#item_table').bootstrapTable('removeByUniqueId', id)
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }
            });
        });
    }
</script>
