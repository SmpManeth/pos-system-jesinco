<?php

/**
 * Description of item_category_model
 *
 * @author dilshan
 */
class Item_category_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "item_categories";
    }

    public function get_categories($branch) {
        $this->db->where("visibility=0 OR (visibility=1 AND branch=$branch->id)", NULL, FALSE);
        return $this->get_all();
    }

    public function save_category($branch) {
        $visibility = $this->input->post("visibility");
        return $this->insert(array(
                    "cat_name" => $this->input->post("cat_name"),
                    "branch" => $branch->id,
                    "visibility" => isset($visibility) ? 1 : 0
        ));
    }

}
