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
<title>View All Invoices</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <a href="<?php echo base_url("register/employee/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Employee</a>
    <span class="pull-right">
        <span><i class="fa fa-stop font-red-thunderbird"></i> Inactive Employees</span>&nbsp;&nbsp;&nbsp;
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    $(document).ready(function () {
        $('#item_table').bootstrapTable({
            url: "<?php echo base_url("register_c/get_employee_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            search: true,
            sortName: 'emp_name ',
            sortOrder: "desc",
            columns: [
                {
                    field: 'emp_name',
                    title: '<i class="fa fa-user"></i> Employee',
                    formatter: function (value, row, index) {
                         return [
                            row.emp_prefix + " " + row.emp_name,
                            "<small>" + row.nic + "</small>"
                        ].join('<br/>');
                    }
                }, {
                    field: 'designation',
                    title: '<i class="fa fa-info"></i> Designation',
                }, {
                    field: 'address_po_box',
                    title: '<i class="fa fa-map-marker"></i> Address',
                    sortable: true,
                    formatter: function (value, row, index) {
                        return [
                            row.address_po_box,
                            row.address_line1,
                            row.address_line2,
                            row.address_city,
                            row.counrty,
                        ].join('<br/>,');
                    }
                }, {
                    field: 'tp1',
                    title: '<i class="fa fa-phone"></i> Telephone',
                    formatter: function (value, row, index) {
                        return [
                            row.tp1,
                            row.tp2,
                            row.office_ext
                        ].join('<br/>,');
                    }
                }, {
                    field: 'o_email',
                    title: '<i class="fa fa-email"></i> Email',
                    formatter: function (value, row, index) {
                        return [
                            row.o_email,
                            row.p_email
                        ].join('<br/>,');
                    }
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            '<a target="_blank" class="btn btn-outline btn-circle btn-xs purple" href="<?php echo base_url("register/employee/edit/") ?>' + (row.id) + '" title="Edit"><i class="fa fa-edit"></i> Edit/View</a>',
                            '<button type="button" class="btn btn-primary btn-xs sendmessage" data-id="' + (row.id) + '" onclick="send_msg(this)"><i class="fa fa-envelope"></i> <span>Send Message</span></button>',
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

    function send_msg(ele) {
        var id = $(ele).data("id");

        DJ.Overlay_input({
            title: "Enter Message Content",
            placeholder: "Message",
            type: "textarea",
            no_empty: false,
            button: {
                yes: {txt: "SEND"},
                no: {txt: "CANCEL"}
            },
            click: function (msg) {
                $.post("<?php echo base_url("users/send_message"); ?>", {id: id, msg, msg}, function (data) {
                    if (data.msg_type == "OK") {
                        DJ.Notify(data.msg, "success");
                    } else {
                        DJ.Notify(data.msg, "danger");
                    }
                }, "JSON");

            }
        });
    }
</script>
