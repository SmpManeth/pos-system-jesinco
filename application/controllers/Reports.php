<?php

/**
 * Description of Reports
 *
 * @author DP4
 * Aug 29, 2018 5:01:22 PM
 */
class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function stock() {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Stock Reports";
            $this->data["breadcrums"] = array(array("home", "Home"), "Stock Reports");
            $this->load_view(array("reports/stock-report"));
        } else {
            redirect(base_url("login"));
        }
    }

}
