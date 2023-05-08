<?php

/**
 * Description of Stock_model
 *
 * @author DP4
 * Aug 2, 2018 1:40:25 PM
 */
class Stock_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "stocks";
    }

    public function get_stock($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $search = FALSE, $count = FALSE) {

        $this->db->select("stocks.id,stocks.qty,items.id as itm_id,items.itm_code,items.itm_name,item_categories.cat_name,items.cost,items.selling,items.minimum_stock_warn,items.stock_type,items.visibility");
        $this->db->select("items.e_at,users.username,items.selling,items.wholesale");
        $this->db->join("items", "stocks.item_id = items.id", "RIGHT");
        $this->db->join("item_categories", "items.itm_cat = item_categories.id", "LEFT");
        $this->db->join("users", "items.e_by= users.id", "LEFT");

//        $this->db->where("stocks.branch_id", $branch->id);
        $this->db->where("(items.visibility=0 OR (items.visibility=1 AND stocks.branch_id=$branch->id))", NULL, FALSE);

        if ($search) {
            $this->db->group_start();
            $this->db->like("items.itm_name", $search);
            $this->db->or_like("items.itm_code", $search);
            $this->db->group_end();
        }
        if ($count) {
            return $this->count_by("items.status", 1);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            }
            $this->db->where("items.status", 1);
            return $this->get_all();
        }
    }

    public function update_stock($id, $branch, $qty, $direction, $empty_record = FALSE) {
        if (!$empty_record) {
            $data = array(
                "item_id" => $id,
                "qty" => $qty,
                "branch_id" => $branch->id
            );
            return $this->insert($data);
        } else {
            $this->db->set("qty", "qty+" . ($qty * $direction), FALSE);
            $this->db->where("item_id", $id);
            $this->db->where("branch_id", $branch->id);
            return $this->db->update("stocks");
        }
    }
    public function update_stock_if_not_insert($id, $branch, $qty, $direction) {
        $this->db->where("branch_id", $branch->id);
        $item = $this->get_by("item_id", $id);
        
        if (!empty($item)) {
            $this->db->set("qty", "qty+" . ($qty * $direction), FALSE);
            $this->db->where("item_id", $id);
            $this->db->where("branch_id", $branch->id);
            return $this->db->update("stocks");
        }else{
            $data = array(
                "item_id" => $id,
                "qty" => $qty,
                "branch_id" => $branch->id
            );
            return $this->insert($data);
        }
    }

    public function update_stock_batch($branch, $items) {
        foreach ($items as $item) {
            if ($item->is_temp == "0") {
                $this->db->set("qty", "qty+" . $item->qty, FALSE);
                $this->db->where("item_id", $item->item_id);
                $this->db->where("branch_id", $branch->id);
                $this->db->update("stocks");
                $this->db->reset_query();
            }
        }
    }

    public function get_stock_item($branch, $item_id) {
        $this->db->select("stocks.id,stocks.item_id,stocks.qty,stocks.branch_id,items.itm_code,items.itm_name,items.selling,items.wholesale,items.min_selling,items.discount,items.dis_type");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");
        $this->db->where("stocks.branch_id", $branch->id);
        return $this->get_by("stocks.item_id", $item_id);
    }

    public function get_stock_list($branch) {
        $this->db->select("stocks.id,stocks.item_id,stocks.qty,stocks.branch_id,items.cost,items.itm_code,items.itm_name,items.selling,items.wholesale,items.min_selling,items.discount,items.dis_type");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");
        $this->db->where("stocks.branch_id", $branch->id);
        $this->db->where("items.status", '1');
        return $this->get_all();
    }
    public function get_stock_list_report($b) {
        $this->db->select("stocks.id,stocks.item_id,stocks.qty,stocks.branch_id,items.cost,items.itm_code,items.itm_name,items.selling,items.wholesale,items.min_selling,items.discount,items.dis_type");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");
        $this->db->where_in("stocks.branch_id", $b);
        $this->db->where("items.status", '1');
        return $this->get_all();
    }

    public function get_stock_list_category_vice($branch, $category, $sub_c = FALSE) {
        $this->db->select("stocks.id,stocks.item_id,stocks.qty,stocks.branch_id,items.cost,items.itm_code,items.itm_name,items.selling,items.wholesale,items.min_selling,items.discount,items.dis_type");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");

        $this->db->where("stocks.branch_id", $branch->id);
        $this->db->where("items.itm_cat", $category->id);
        if ($sub_c) {
            $this->db->where("items.sub_cat", $sub_c->id);
        }
        $this->db->where("items.status", 1);
        return $this->get_all();
    }

    public function check_for_availablility($branch, $items) {
        /*
         * @TODO
         * Should not query from DB in a iteration loop. 
         * Get relavent items ids,then query the db and filter through the iteration.
         */
        $b = TRUE;
        $itm = false;
        foreach ($items as $item) {
            $this->db->where("branch_id", $branch->id);
            $stock = $this->get_by("item_id", $item->itm_id);
            if (!empty($stock)) {
                if (doubleval($stock->qty) < doubleval($item->qty)) {
                    $b = FALSE;
                    $itm = $item;
                    break;
                }
            } else {
                $b = FALSE;
                break;
            }
        }
        return array($b, $itm);
    }

    public function check_for_availablility2($branch, $items) {
        /*
         * @TODO
         * Should not query from DB in a loop. 
         * Get relavent items ids,then query the db and filter through the iteration.
         */
        $b = TRUE;
        $itm = false;
        foreach ($items as $item) {
            $this->db->where("branch_id", $branch->id);
            $stock = $this->get_by("item_id", $item->item_id);
            if (!empty($stock)) {
                if (doubleval($stock->qty) < doubleval($item->qty)) {
                    $b = FALSE;
                    $itm = $item;
                    break;
                }
            } else {
                $b = FALSE;
                break;
            }
        }
        return array($b, $itm);
    }

    public function update_stock_bulk($branch, $items) {
        foreach ($items as $item) {
            $this->db->set("qty", "qty - " . $item->qty, FALSE);
            $this->db->where("branch_id", $branch->id);
            $this->db->where("item_id", $item->itm_id);
            $this->db->update("stocks");
        }
    }

    public function update_ret_stock($branch, $item, $qty) {
        $this->db->set("qty", "qty - " . $qty, FALSE);
        $this->db->where("branch_id", $branch->id);
        $this->db->where("item_id", $item);
        $this->db->update("stocks");
    }

    public function get_stock_by($item, $branch) {
        $this->db->where("item_id", $item);
        return $this->get_by('branch_id', $branch->id);
    }

    public function get_all_items($branch) {
        $this->db->select("items.itm_code,items.itm_name,stocks.qty,items.cost,items.wholesale,items.selling,items.min_selling,items.minimum_stock_warn");
        $this->db->select("users.username,items.e_at");
        $this->db->join("items", "stocks.item_id = items.id", "LEFT");
        $this->db->join("users", "items.e_by= users.id", "LEFT");
        $this->db->where("stocks.branch_id", $branch->id);
        return $this->get_all();
    }

    public function update_stock_single($item_id, $branch, $qty) {
        $this->db->set("qty", $qty);
        $this->db->where("item_id", $item_id);
        $this->db->where("branch_id", $branch->id);
        $this->db->update("stocks");
    }

}
