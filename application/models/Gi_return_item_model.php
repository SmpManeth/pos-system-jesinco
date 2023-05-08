<?php

/**
 * Description of gi_return_item_model
 *
 * @author DP4
 * Sep 18, 2018 4:51:58 PM
 */
class Gi_return_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "gi_return_items";
    }

    public function add_return($item, $qty) {
        $data = array(
            "gi_id" => $item->gi_id,
            "gii_id" => $item->id,
            "itm_id" => $item->itm_id,
            "itm_code" => $item->itm_code,
            "itm_name" => $item->itm_name,
            "qty" => $qty,
            "rate" => $item->rate,
            "branch" => $item->branch,
            "status" => 1,
        );
        $this->insert($data);
    }

    public function get_returns($id) {
        $this->db->where("gi_id", $id);
        return $this->get_all();
    }

}
