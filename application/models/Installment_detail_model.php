<?php

/**
 * Description of Installment_detail_model
 *
 * @author dilsh
 */
class Installment_detail_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "installment_details";
    }

    public function get_invoice_installments($id) {
        return $this->get_by("inv_id", $id);
    }

    public function update_day($id, $day) {
        $row = $this->get_by("inv_id", $id);
        
        $_date= explode("-", $row->next_installment_date);
        $_date[2]=$day;
        
        $data=array(
            "installment_day"=>$day,
            "next_installment_date"=> implode("-", $_date)
        );
        $this->update($row->id, $data);
    }

}
