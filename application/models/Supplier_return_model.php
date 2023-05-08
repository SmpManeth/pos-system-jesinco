<?php

/**
 * Description of Supplier_return_model
 *
 * @author DP4
 * Aug 27, 2018 4:21:25 PM
 */
class Supplier_return_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "supplier_returns";
    }

    public function get_return_list($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE) {
        $this->db->select("supplier_returns.*,wl_suppliers.company_name,users.username");
        $this->db->join("wl_suppliers", "supplier_returns.supplier=wl_suppliers.id", "LEFT");
        $this->db->join("users", "supplier_returns.edit_by=users.id", "LEFT");

        $this->db->where("supplier_returns.branch", $branch->id);
        if ($count) {
            return $this->count_by("supplier_returns.branch", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("create_date", "DESC");
            }
            return $this->get_all();
        }
    }

    public function get_open_notes($branch, $supplier = FALSE) {
        $this->db->select("supplier_returns.*,wl_suppliers.company_name");
        $this->db->join("wl_suppliers", "supplier_returns.supplier=wl_suppliers.id", "LEFT");
        $this->db->where("supplier_returns.branch", $branch->id);
        $this->db->where("supplier_returns.status", 0);
        if ($supplier) {
            $this->db->where("supplier_returns.supplier", $supplier);
        }
        return $this->get_all();
    }

    public function get_ret_note($id, $branch) {
        $this->db->select("supplier_returns.*,wl_suppliers.company_name");
        $this->db->join("wl_suppliers", "supplier_returns.supplier=wl_suppliers.id", "LEFT");
//        $this->db->where("supplier_returns.branch", $branch->id);
        return $this->get_by("supplier_returns.id", $id);
    }

}
