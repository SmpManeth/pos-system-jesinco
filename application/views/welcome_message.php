<meta charset="utf-8">
<title>Home</title>
<div id="container">
    <h2>Today Summary</h2>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="<?php echo base_url("reservation/new-note") ?>">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="1349"><?php echo is_zero($invoice[0], "0.00") ?></span>
                    </div>
                    <div class="desc">(<?php echo $invoice[1]?>)  Invoice </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="<?php echo base_url("grn/new") ?>">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="12,5"><?php echo is_zero($grn[0], "0.00") ?></span></div>
                    <div class="desc">(<?php echo $grn[1]?>)  Goods Receive Note</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 purple" href="<?php echo base_url("po/new") ?>">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value="89"></span><?php echo is_zero($po[0], "0.00") ?> </div>
                    <div class="desc">(<?php echo $po[1]?>)  Purchasing Orders </div>
                </div>
            </a>
        </div>
    </div>
</div>