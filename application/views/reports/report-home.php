<title>Sales Reports</title>
<br/>
<style>
.btn{
    margin: 10px;
}
</style>
<div class="row">
    <div class="col-lg-12">
    <?php
        $reports = [
            "Credit Bill"=>"reporter/reports/sales/credit-bills",
            "Due bill summery"=>"reporter/reports/sales/due-bill-summary",
//            "Live Due bill summary"=>"reporter/reports/sales/live-due-bill-summary", // Moved to separate menu
            // "Collection bills"=>"reporter/reports/sales/collection-bills",
            "Due Date Collected Bill Summary"=>"reporter/reports/sales/forward-date-collection-bills",
            "Collection bills"=>"reporter/reports/sales/collection-bills",
            "Branch vice total collection"=>"reporter/reports/sales/branch-vice-total-collection",
            "Payment Complete Bills"=>"reporter/reports/sales/payment-complete-bills",
            "Monthly return bills"=>"reporter/reports/sales/monthly-return-bills",
            "Bill issue summery"=>"reporter/reports/sales/bill-issue-summary",
            "Branch vice sold items"=>"reporter/reports/sales/branch-vice-sold-items",
            "Stock adjustment report"=>"reporter/reports/stock/stock-movements",
            "C24 report"=>"reporter/reports/sales/c24-report",
            "Completed Invoices (Approved)"=>"reporter/reports/sales/completed-invoices",
            "Cancelled Invoices (Approved)"=>"reporter/reports/sales/cancelled-invoices",
            "Cancelled DOs (Approved)"=>"reporter/reports/sales/cancelled-dos"
        ];
        foreach ($reports as $key => $url) {
            ?>
            <a class="btn btn-primary" href="<?php echo base_url($url) ?>">
                <?php echo $key ?>
            </a>
            <?php
        }
    ?>
        
    </div>
</div>
