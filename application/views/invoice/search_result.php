<?php
//Sep 18, 2018 6:48:26 PM 
?>
<div class="page-content-col">
    <div class="row">
        <div class="col-md-12 ">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Customer</td>
                                <td>Invoice Number</td>
                                <td>Date</td>
                                <td>Total</td>
                                <td></td>
                            </tr>                
                        </thead>
                        <tbody id="tbody">
                            <?php
                            if (isset($invoices)) {
                                foreach ($invoices as $invoice) {
                                    $inv = decorate_code($invoice->inv_id, "invoice", $this->prefixes);
                                    ?>
                                    <tr>
                                        <td><?php echo $invoice->customer_name ?></td>
                                        <td><?php echo $inv ?></td>
                                        <td><?php echo $invoice->inv_date ?></td>
                                        <td><?php echo is_zero($invoice->total) ?></td>
                                        <td><a target="_blank" href="<?php echo base_url("invoice/payments/" . $inv) ?>">View</a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>