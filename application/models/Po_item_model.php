<?php

/**
 * Description of po_item
 *
 * @author dilshan
 */
class Po_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "po_items";
    }

    public function add_item($order_id) {
        $data = array(
            "order_id" => $order_id,
            "item_id" => $this->input->post("item"),
            "qty" => $this->input->post("qty"),
            "price" => $this->input->post("rate")
        );
        return $this->insert($data);
    }

    public function get_items($po_id) {
        $this->db->where("order_id", $po_id);
        return $this->get_all();
    }

    public function get_items_with_details($po_id) {
        $this->db->select("po_items.id,po_items.is_temp,po_items.item_id,po_items.qty,po_items.price,po_items.order_id,items.itm_code,items.itm_name");
        $this->db->join("stocks", "po_items.item_id = stocks.item_id", "LEFT");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");
        $this->db->where("order_id", $po_id);
        return $this->get_all();
    }

    public function temp_po_items($po_id) {
        $this->db->where("is_temp", 1);
        $this->db->where("order_id", $po_id);
        return $this->get_all();
    }

}
