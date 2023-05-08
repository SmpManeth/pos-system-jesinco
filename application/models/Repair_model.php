<?php

/*
 * The MIT License
 *
 * Copyright 2019 Dilshan Jayasanka.
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
 * Description of Repair_model
 *
 * @author dilsh
 */
class Repair_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "repairs";
    }

    public function get_all_repairs($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE) {
        $this->db->select("repairs.*,invoices.inv_id as invid,invoices.inv_date,wl_customers.customer_prefix,wl_customers.customer_name,wl_customers.tp1,users.username,items.itm_name");
        $this->db->join("invoices", "repairs.inv_id = invoices.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("items", "repairs.item_id = items.id", "LEFT");
        $this->db->join("users", "repairs.created_by = users.id", "LEFT");

        if ($branch->main_branch == "1") {
            $this->db->where_in("repairs.status", array(1, 2, 3));
        } else {
            $this->db->where("invoices.branch", $branch->id);
            $this->db->where_in("repairs.status", array(0, 4, 5, 6));
        }
        if ($count) {
            return $this->count_by("invoices.branch", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("repairs.created_date", "DESC");
            }
            return $this->get_all();
        }
    }

    public function get_one_row($id) {
        $this->db->select("repairs.*,invoices.inv_date,wl_customers.customer_prefix,wl_customers.customer_name,wl_customers.tp1,users.username,items.itm_name");
        $this->db->join("invoices", "repairs.inv_id = invoices.id", "LEFT");
        $this->db->join("wl_customers", "invoices.customer = wl_customers.id", "LEFT");
        $this->db->join("items", "repairs.item_id = items.id", "LEFT");
        $this->db->join("users", "repairs.created_by = users.id", "LEFT");

        return $this->get_by("repairs.id", $id);
    }
    
    public function get_selected($ids) {
        $this->db->select("repairs.*,invoices.inv_date,items.itm_name,items.itm_code");
        $this->db->join("invoices", "repairs.inv_id = invoices.id", "LEFT");
        $this->db->join("items", "repairs.item_id = items.id", "LEFT");
        $this->db->where_in("repairs.id",$ids);
        return $this->get_all();
    }

}
