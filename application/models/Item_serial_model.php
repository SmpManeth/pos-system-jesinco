<?php

/**
 * Description of item_serial_model
 *
 * @author dilshan
 */
class Item_serial_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "item_serials";
    }

    public function get_serials($item_id, $status, $grn_id = FALSE, $branch = FALSE) {
        $this->db->where("itm_id", $item_id);
        if ($grn_id) {
            $this->db->where("grn_id", $grn_id);
        }
        if ($branch) {
            $this->db->where("branch", $branch->id);
        }
        if ($status == "grn_edt") {
            
        }
        return $this->get_all();
    }

    public function get_grn_serials($grn_no) {
        $this->db->select("item_serials.id,item_serials.itm_id,items.itm_name,items.itm_code,serial_no");
        $this->db->join("items", "item_serials.itm_id = items.id", "LEFT");
        $this->db->where("grn_id", $grn_no);
        return $this->get_all();
    }

    public function get_grn_serial_ids($grn_no, $item_id) {
        $this->db->select("item_serials.id");
        $this->db->where("grn_id", $grn_no);
        $this->db->where("itm_id", $item_id);
        $ids = array();
        $serials = $this->get_all();
        foreach ($serials as $ser):
            $ids[] = $ser->id;
        endforeach;
        return $ids;
    }

    public function delete_serials($grn_id, $item_id) {
        $this->db->where("grn_id", $grn_id);
        $this->db->where("itm_id", $item_id);
        $this->db->delete("item_serials");
    }

}
