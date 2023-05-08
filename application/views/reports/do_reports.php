<title>D/O Reports</title>
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
            "Delivery Notes Summary"=>"reporter/reports/sales/do-summary",
            "DO Report"=>"reporter/reports/sales/do-report",
            "Return DO Report"=>"reporter/reports/sales/return-do-report",
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
