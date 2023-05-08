<?php
/**
 * Description of invoice_return_model
 *
 * @author DP4
 * Aug 24, 2018 9:56:27 AM
 */
class Invoice_return_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "invoice_returns";
    }

    public function save_return($data) {
        return $this->insert($data);
    }

    public function get_inv_return_items($inv_id) {
        $this->db->select("invoice_returns.id as ret_id,invoice_returns.with_refund,invoice_returns.status,users.username,invoice_items.itm_code,invoice_items.itm_name,invoice_returns.ret_qty,invoice_returns.displaY_qty,invoice_returns.ret_date,invoice_returns.remarks");
        $this->db->join("users", "invoice_returns.ret_by = users.id", "LEFT");
        $this->db->join("invoice_items", "invoice_returns.inv_item_id = invoice_items.id", "LEFT");
        $this->db->where("invoice_returns.inv_id", $inv_id);
        return $this->get_all();
    }

    public function cancel_invoice_return($id) {
        $this->db->set("status", 2);
        $this->db->where("invoice_returns.inv_id", $id);
        $this->db->update("invoice_returns");
    }

}
