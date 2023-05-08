<?php

/**
 * Description of item_model
 *
 * @author dilshan
 */
class Item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "items";
    }

    public function save_item($branch, $user) {
        $data = array(
            "itm_code" => $this->input->post("itm_code"),
            "itm_cat" => $this->input->post("category"),
            "itm_name" => $this->input->post("itm_name"),
            "sub_cat" => $this->input->post("sub_category"),
            "itm_description" => $this->input->post("description"),
            "bar_code" => $this->input->post("serial"),
            "unique_type" => $this->input->post("unique"),
            "stock_type" => $this->input->post("inv_type"),
            "u_o_m" => $this->input->post("u_o_m"),
            "cost" => $this->input->post("cost"),
            "wholesale" => $this->input->post("wholesale"),
            "selling" => $this->input->post("selling"),
            "min_selling" => $this->input->post("min_sell"),
            "discount" => $this->input->post("discount"),
            "dis_type" => $this->input->post("dis_type"),
            "status" => 1,
            "visibility" => $this->input->post("visibility"),
            "minimum_stock_warn" => $this->input->post("minimum_stock_warn"),
            "branch" => $branch->id,
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->insert($data);
    }

    public function update_item($branch, $user) {
        $id = $this->input->post("id");
        $visibility = $this->input->post("visibility");
        $data = array(
            "itm_code" => $this->input->post("itm_code"),
            "itm_cat" => $this->input->post("category"),
            "itm_name" => $this->input->post("itm_name"),
            "sub_cat" => $this->input->post("sub_category"),
            "itm_description" => $this->input->post("description"),
            "bar_code" => $this->input->post("serial"),
            "unique_type" => $this->input->post("unique"),
            "stock_type" => $this->input->post("inv_type"),
            "u_o_m" => $this->input->post("u_o_m"),
            "cost" => $this->input->post("cost"),
            "wholesale" => $this->input->post("wholesale"),
            "selling" => $this->input->post("selling"),
            "min_selling" => $this->input->post("min_sell"),
            "discount" => $this->input->post("discount"),
            "dis_type" => $this->input->post("dis_type"),
            "status" => $this->input->post("status"),
            "minimum_stock_warn" => $this->input->post("minimum_stock_warn"),
            "visibility" => isset($visibility) ? 1 : 0,
            "branch" => $branch->id,
            // "e_by" => $user->id,
            // "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->update($id, $data);
    }

    public function get_all_items($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $search = FALSE) {
        $this->db->select("items.id,items.itm_code,items.itm_name,items.unique_type,items.stock_type,items.cost,items.selling,items.`status`");
        $this->db->select("items.visibility,items.branch,item_categories.cat_name,item_sub_categories.sub_name,users.username,items.e_at");

        $this->db->join("item_categories", "items.itm_cat = item_categories.id", "LEFT");
        $this->db->join("item_sub_categories", "items.sub_cat = item_sub_categories.id", "LEFT");
        $this->db->join("users", "items.e_by = users.id", "LEFT");
        $this->db->where("(items.visibility=0 OR (items.visibility=1 AND items.branch=$branch->id))", NULL, FALSE);
        if ($search) {
            $this->db->group_start();
            $this->db->like("items.itm_name", $search, FALSE);
            $this->db->or_like("items.itm_code", $search, FALSE);
            $this->db->group_end();
        }
        if ($length) {
            $this->db->limit($length, $start);
        }
        if ($column) {
            $this->db->order_by($column, $direction);
        }
        return $this->get_all();
    }

    public function get_by_category($id) {
        $this->db->where("itm_cat", $id);
        return $this->get_all();
    }

    public function get_by_sub_category($id) {
        $this->db->where("sub_cat", $id);
        return $this->get_all();
    }

    public function get_item($id) {
        $this->db->select("items.*,item_categories.cat_name");
        $this->db->join("item_categories", "items.itm_cat = item_categories.id", "LEFT");
        return $this->get_by("items.id", $id);
    }

    public function get_iem_by_code($code, $branch) {
        $this->db->where("(items.visibility=0 OR (items.visibility=1 AND items.branch=$branch->id))", NULL, FALSE);
        return $this->get_by("itm_code", $code);
    }

    public function get_items_by_branch($branch) {
        $this->db->where("items.branch", $branch->id);
        return $this->get_all();
    }

}
