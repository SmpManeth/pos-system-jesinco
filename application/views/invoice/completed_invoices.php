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
<title>Completed Invoice List</title>
<div class="sub-header-wrapper">
    <h4 class="pull-left"><strong>Completed Invoices</strong></h4>
</div>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($invoices as $invoice) {
                ?>
                <tr>
                    <td><?php echo $invoice->branch_name ?></td>
                    <td><a target="_blank" href="<?php echo base_url("invoice/view-cancelled-invoice/" . $invoice->ii_id) ?>"><?php echo $invoice->prefix . str_pad($invoice->inv_id, 5, "0", STR_PAD_LEFT) ?></a></td>
                    <td><?php echo $invoice->inv_date ?></td>
                    <td><?php echo number_format($invoice->total, 2) ?></td>
                    <td><button  class="btn btn-xs btn-outline blue" data-id="<?php echo $invoice->ii_id ?>" onclick="approve_cancel(this)">Approve</button></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {

    });
    function approve_cancel(ele) {

        var id = $(ele).data("id");
        DJ.Overlay_confirm({
            title: "Are you sure to Complete this Invoice",
            click: function (v) {
                if (v) {
                    DJ.disable_ele_fa(ele, "");
                    $.ajax({
                        url: "<?php echo site_url("invoice/approve-cancellation") ?>",
                        type: "POST",
                        dataType: "JSON",
                        data: {id: id,type:1},
                        success: function (data) {
                            DJ.enable_ele_fa(ele, "")
                            if (data.msg_type == "OK") {
                                DJ.Notify(data.msg, "success");
                                $(ele).closest("tr").hide(500, function () {
                                    $(ele).closest("tr").remove();
                                });
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
