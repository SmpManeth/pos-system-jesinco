<?php

/*
 * The MIT License
 *
 * Copyright 2019 Dilshan  Jayasnka.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Description of Damage_good
 *
 * @author Dilshan  Jayasnka
 */
class Damage_good_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "damage_goods";
    }

    public function get_all_damaged_items($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE) {
        $this->db->select("damage_goods.*,users.username,items.itm_name,branches.branch_name,items.itm_code");
        $this->db->join("items", "damage_goods.item_id = items.id", "LEFT");
        $this->db->join("users", "damage_goods.added_by = users.id", "LEFT");
        $this->db->join("branches", "damage_goods.branch_id = branches.id", "LEFT");

        if ($branch->main_branch == "1") {
            $this->db->where_in("damage_goods.status", array(1, 2, 3));
        } else {
            $this->db->where("damage_goods.branch_id", $branch->id);
            $this->db->where_in("damage_goods.status", array(0));
        }
        if ($count) {
            return $this->count_by("damage_goods.branch_id", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("damage_goods.create_date", "DESC");
            }
            return $this->get_all();
        }
    }

    public function get_selected($ids) {
        $this->db->select("damage_goods.*,items.itm_name,items.itm_code");
        $this->db->join("items", "damage_goods.item_id = items.id", "LEFT");
        $this->db->where_in("damage_goods.id", $ids);
        return $this->get_all();
    }

    public function get_item_sum_report($branch, $from, $to, $assoc=FALSE){
        $this->db->select("Count(damage_goods.item_id) AS sold,damage_goods.branch_id,damage_goods.`status`,damage_goods.item_id");

        $this->db->where("damage_goods.branch_id",$branch);
        if(!empty($from) && !empty($to)){
            $this->db->where("sent_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("sent_date",$from);
            }
            if(!empty($to)){
                $this->db->where("sent_date",$to);
            }
        }
        $this->db->group_by("damage_goods.item_id");
        if($assoc){
            $data = $this->get_all();
            $assoc_data = array();
            foreach ($data as $_data) {
                $assoc_data[$_data->item_id] = $_data;
            }
            return $assoc_data;

        } else {
            return $this->get_all();
        }
    }

}
