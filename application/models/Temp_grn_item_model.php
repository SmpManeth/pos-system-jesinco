<?php

/**
 * Description of Temp_grn_item_model
 *
 * @author DP4
 * Aug 10, 2018 5:57:29 PM
 */
class Temp_grn_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "temp_grn_items";
    }

    public function save_entry($branch) {
        $data = array(
            "branch" => $branch->id,
            "item_id" => $this->input->post("itm_code"),
            "qty" => $this->input->post("qty"),
            "price" => $this->input->post("rate"),
            "edit_at" => date("Y-m-d H:i:s"),
            "status" => 1
        );
        return $this->insert($data);
    }

    public function get_records($branch) {
        $this->db->select("temp_grn_items.id,temp_grn_items.edit_at,temp_grn_items.item_id,temp_grn_items.qty,temp_grn_items.price,temp_grn_items.`status`,items.itm_name,items.itm_code");
        $this->db->join("items", "temp_grn_items.item_id = items.id", "LEFT");

        $this->db->where("temp_grn_items.branch", $branch->id);
        $this->db->where("temp_grn_items.status", 1);
        $this->db->order_by("temp_grn_items.edit_at", "DESC");
        return $this->get_all();
    }

    public function get_items($ids) {
        $this->db->select("temp_grn_items.*,items.itm_name,items.itm_code");
        $this->db->join("items", "temp_grn_items.item_id = items.id", "LEFT");
        $this->db->where_in("temp_grn_items.id", $ids);
        return $this->get_all();
    }

    public function get_all_ids() {
        $this->db->select('id');
        $this->db->where("status", 1);
        return $this->get_all();
    }

    public function update_po_id($ids, $po_id) {
        $this->update_many($ids, array("po_id" => $po_id, "status" => 2));
    }

    public function update_grn_id($ids, $grn_id) {
        $this->update_many($ids, array("grn_id" => $grn_id, "status" => 3));
    }

}
