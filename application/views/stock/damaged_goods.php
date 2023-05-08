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
<title>View All Damaged Goods</title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.css") ?>" />
<script src="<?php echo base_url("assets/global/plugins/bootstrap-table/bootstrap-table.min.js") ?>"></script>
<div class="sub-header-wrapper">
    <!-- <a href="<?php echo base_url("invoice/new") ?>" class="btn green-haze btn-sm btn-outline sbold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;New Invoice</a> -->
    <div id="toolbar" class="pull-left">
        <div class="form-inline" role="form">
            <button id="enable_check_box" type="button" class="btn btn-sm btn-default"><i class="fa fa-check-circle-o"></i> Select Rows</button>
            <button id="print_data" type="button" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print Data</button>
        </div>
    </div>
    <span class="pull-right">
        <span><i class="fa fa-info-circle text-primary"></i> Get a Print Document Before mask as <strong>Send to Head office</strong></span>
    </span>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover" id="item_table"></table>
</div>
<script>
    var table;
    var main_branch = <?php echo $branch->main_branch ?>;
    $(document).ready(function () {
        table = $('#item_table').bootstrapTable({
            url: "<?php echo base_url("stock/damaged_list") ?>",
            pagination: true,
            sidePagination: "server",
            queryParamsType: 'limit',
            checkboxHeader: false,
            search: false,
            sortOrder: "desc",
            uniqueId: "id",
            columns: [
                {
                    field: 'check',
                    checkbox: true,
                    visible: false,
                    title: 'id',
                }, {
                    field: 'itm_name',
                    title: '<i class="fa fa-user"></i> Item Name',
                }, {
                    field: 'branch_name',
                    title: '<i class="fa fa-shopping-cart"></i> Branch',
                    sortable: true
                }, {
                    field: 'create_date',
                    title: '<i class="fa fa-calendar"></i> Received Date',
                    sortable: true
                }, {
                    field: 'itm_code',
                    title: '<i class="fa fa-calendar"></i> Item',
                    formatter:function (value, row) {
                        return row.itm_code + "<br/>"+ row.itm_name;
                    }
                }, {
                    field: 'sent_date',
                    title: '<i class="fa fa-calendar"></i> Sent Date',
                }, {
                    field: 'operate',
                    title: '<i class="fa fa-cog"></i> Option',
                    align: 'left',
                    events: function () {
                    },
                    formatter: function (value, row, index) {
                        return [
                            row.status == "0" ? '<button  class="btn btn-outline btn-circle btn-xs purple-studio" onclick="change_status(this,' + row.id + ',1,' + index + ')" title="Send this item to heade office for repair"><i class="fa fa-edit"></i> Send to Head Office</button>' : '',
                            row.status == "1" ? '<button  class="btn btn-outline btn-circle btn-xs yellow-casablanca" onclick="change_status(this,' + row.id + ',2,' + index + ')" title="Mark as repairing"><i class="fa fa-edit"></i> Mark as Repairing</button>' : '',
                            row.status == "2" ? '<button  class="btn btn-outline btn-circle btn-xs grey" onclick="change_status(this,' + row.id + ',3,' + index + ')" title="Repair Done."><i class="fa fa-edit"></i> Repair done</button>' : '',
                            row.status == "3" ? '<button  class="btn btn-outline btn-circle btn-xs green-turquoise" onclick="change_status(this,' + row.id + ',4,' + index + ')" title="Add to Stock."><i class="fa fa-edit"></i> Add to Stock</button>' : '',
                            '<br/><small>Last Edit By : <span class="font-blue-madison">' + row.username + '</span>&nbsp;@<span class="font-green-jungle">' + row.create_date + ' </span></small>',
                        ].join('');
                    }
                },
            ],
            rowStyle: function (row, index) {
                var classes = ['success', 'info', 'warning', 'danger', 'finished', 'primary'];
                var cls = "";
                if (row.status == "6") {
                    cls = classes[0];
                }
                return {classes: cls};
            }
        });
    });
    $("#enable_check_box").click(function (e) {
        var _visible_cols = table.bootstrapTable('getHiddenColumns');
        var visible_cols = _visible_cols.map(function (it) {
            return it.field
        });
        console.log(visible_cols);
        if (visible_cols.includes("check")) {
            table.bootstrapTable('showColumn', "check");
            table.bootstrapTable('uncheckAll');
        } else {
            table.bootstrapTable('hideColumn', "check");
        }
    });
    $("#print_data").click(function (e) {
        var _ids = table.bootstrapTable('getSelections');
        if (_ids.length > 0) {
            var ids = _ids.map(function (it) {
                return it.id
            });
            console.log(ids);
            window.open("<?php echo base_url("stock/print_sendtocompany"); ?>?ids=" + ids.join(","));
        }
    });

    function change_status(ele, id, status,index) {
        var msgs = [
            "Are you want to send to this product as pending?",
            "Are you want to send to this product to company for repair?",
            "Are you want to Mark as this product as repairing?",
            "Are you want to Mark as this product as Repair Done?",
            "Are you want to Add this product to Stock?",
        ];
        DJ.Overlay_confirm({
            title: msgs[status],
            click: function (v) {
                if (v) {
                    DJ.disable_ele_fa(ele, "");

                    $.ajax({
                        url: "<?php echo site_url("stock/change_status") ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: {id: id, status: status},
                        success: function (data) {
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                if (main_branch === 1) {

                                    if (status == 1 || status == 4 || status == 5 || status == 6) {
                                        table.bootstrapTable('removeByUniqueId', id);
                                    } else {
                                        var row = table.bootstrapTable('getRowByUniqueId', id);
                                        row.status = status;
                                        table.bootstrapTable('updateRow', {index: index, row: row});
                                    }

                                } else {
                                    if (status == 4 || status == 5 || status == 6) {
                                        var row = table.bootstrapTable('getRowByUniqueId', id);
                                        row.status = status;
                                        table.bootstrapTable('updateRow', {index: index, row: row});
                                    } else {
                                        table.bootstrapTable('removeByUniqueId', id);
                                    }
                                }

                            } else {
                                DJ.Notify(data.msg, "danger");
                            }
                        }
                    });
                }
            }
        });
    }
</script>
