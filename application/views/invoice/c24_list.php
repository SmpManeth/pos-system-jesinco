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
<title>View All C24 Invoices</title>
<div class="table-scrollable">
    <table class="table table-striped table-bordered table-advance table-hover">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Invoice Amount</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($due_payments)) {
                foreach ($due_payments as $due_pay) {
                    $doc_id = decorate_code($due_pay->inv_id, "invoice", $this->prefixes);
                    ?>
                    <tr>
                        <td><a href="<?php echo base_url("invoice/payments/" . $doc_id); ?>"><?php echo $doc_id ?></a></td>
                        <td><?php echo $due_pay->customer_prefix . " " . $due_pay->customer_name ?></td>
                        <td><?php echo $due_pay->inv_date ?></td>
                        <td><?php echo is_zero($due_pay->total) ?></td>
                        <td><?php echo $due_pay->next_installment_date ?></td>
                        <td><?php echo is_zero($due_pay->installment_amount) ?></td>
                        <td>
                            <?php
                            if ($this->branch->main_branch == "1") {
                                ?>
                                <button type="button" class="btn btn-xs btn-primary mark-btn" data-id="<?php echo $due_pay->id ?>"><i class="fa fa-edit"></i></button>
                                <?php
                            } else {
                                if (!empty($due_pay->c24_remarks)) {
                                    ?>
                                    <button type="button" class="btn btn-xs btn-primary view-btn" data-id="<?php echo $due_pay->id ?>"><i class="fa fa-envelope-o"></i></button>
                                        <?php
                                    }
                                }
                                ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $(".mark-btn").click(function (e) {
            var id = $(this).data("id");
            DJ.load_to_model({
                title: "Add a Remark",
                url: "<?php echo base_url("invoice_c/load_c24_form"); ?>",
                data: {is_ajax_request: "OK", inv_id: id}
            });
        });
        $(".view-btn").click(function (e) {
            var id = $(this).data("id");
            DJ.load_to_model({
                title: "View Remarks",
                url: "<?php echo base_url("invoice_c/load_c24_form"); ?>",
                data: {is_ajax_request: "OK", inv_id: id}
            });
        });
    });
</script>

