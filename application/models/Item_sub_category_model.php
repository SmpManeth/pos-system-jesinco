<?php

/**
 * Description of item_sub_category_model
 *
 * @author dilshan
 */
class Item_sub_category_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "item_sub_categories";
    }

    public function get_sub_categories($branch, $category = FALSE) {
        $this->db->select("item_sub_categories.id,item_sub_categories.cat_id,item_sub_categories.sub_name,item_sub_categories.branch,item_sub_categories.visibility,item_categories.cat_name");
        $this->db->join("item_categories", "item_sub_categories.cat_id = item_categories.id", "LEFT");
        $this->db->where("(item_sub_categories.visibility=0 OR (item_sub_categories.visibility=1 AND item_sub_categories.branch=$branch->id))", NULL, FALSE);
        if ($category) {
            $this->db->where("item_sub_categories.cat_id", $category);
        }
        return $this->get_all();
    }

    public function save_sub_category($branch) {
        $visibility = $this->input->post("visibility");
        $data = array(
            "cat_id" => $this->input->post("cat_id"),
            "sub_name" => $this->input->post("sub_cat_name"),
            "branch" => $branch->id,
            "visibility" => isset($visibility) ? 1 : 0
        );
        return $this->insert($data);
    }

    public function get_subs($cat) {
        $this->db->where("cat_id", $cat);
        return $this->get_all();
    }

}
