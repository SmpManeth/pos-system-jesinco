<?php
/**
 * Description of company_model
 *
 * @author dilshan
 */
class Company_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "companies";
    }
    public function upate_company($data) {
        unset($data["webl_cm"]);
        unset($data["is_ajax_request"]);
        $this->update(1, $data);
    }
}