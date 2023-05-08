<?php

/**
 * Description of Gi_item_model
 *
 * @author DP4
 * Sep 17, 2018 7:00:20 PM
 */
class Gi_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "gi_items";
    }

    public function save_item($id, $branch, $item) {
        $data = array(
            "gi_id" => $id,
            "itm_id" => $item->item_id,
            "itm_code" => $item->itm_code,
            "itm_name" => $item->itm_name,
            "qty" => $this->input->post("qty"),
            "rate" => $this->input->post("rate"),
            "foc" => $this->input->post("foc"),
            "branch" => $branch->id
        );

        return $this->insert($data);
    }

    public function get_items($id, $branch) {
        $this->db->where("gi_id", $id);
        $this->db->where("branch", $branch->id);
        return $this->get_all();
    }

    public function get_history($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("gi_items.*,gi_notes.issue_date,gi_notes.gi_id as issue_id");
        } else {
            $this->db->select("SUM(gi_items.qty) as countt");
        }
        $this->db->join("gi_notes", "gi_items.gi_id = gi_notes.id AND gi_notes.branch = " . $branch->id, "LEFT");
        $this->db->where("gi_notes.branch", $branch->id);
        $this->db->where("gi_notes.status", 1);
        if ($itm_id) {
            $this->db->where("gi_items.itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("gi_notes.issue_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gi_notes.issue_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gi_notes.issue_date", $end);
            }
        }

        $items = $this->get_all();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $qty += doubleval($itm->qty);
                if ($itm_id) {
                    $data["$itm->issue_date"][] = array(
                        "date" => $itm->issue_date,
                        "type" => "gi",
                        "doc_number" => $itm->issue_id,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->rate,
                    );
                } else {
                    $data["gi-$itm->itm_code"][] = array(
                        "date" => $itm->issue_date,
                        "type" => "gi",
                        "doc_number" => $itm->id,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->rate,
                    );
                }
            }
            return [$data, $qty];
        } else {
            return doubleval($items[0]->countt);
        }
    }

    public function get_history_new($branch, $itm_id, $start, $end) {
        $this->db->select("gi_items.*,gi_notes.issue_date,gi_notes.gi_id as issue_id");
        $this->db->join("gi_notes", "gi_items.gi_id = gi_notes.id AND gi_notes.branch = " . $branch->id, "LEFT");
        $this->db->where("gi_notes.branch", $branch->id);
        $this->db->where("gi_notes.status", 1);
        if ($itm_id) {
            $this->db->where("gi_items.itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("gi_notes.issue_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gi_notes.issue_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gi_notes.issue_date", $end);
            }
        }

        $items = $this->get_all();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $qty += doubleval($itm->qty);
            if ($itm_id) {
                $data["$itm->issue_date"][] = array(
                    "date" => $itm->issue_date,
                    "type" => "gi",
                    "doc_number" => $itm->issue_id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            } else {
                $data["gi-$itm->itm_code"][] = array(
                    "date" => $itm->issue_date,
                    "type" => "gi",
                    "doc_number" => $itm->id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            }
        }
        return [$data, $qty];
    }

    public function get_issued_items($branch, $start, $end) {
        $this->db->select("gi_notes.issue_date,gi_notes.gi_id,gi_items.qty,gi_items.rate,gi_items.itm_name,gi_items.foc,branches.branch_name");
        $this->db->join("gi_notes", "gi_items.gi_id = gi_notes.id", "LEFT");
        $this->db->join("branches", "gi_notes.shop_id = branches.id", "LEFT");
        $this->db->where("gi_items.branch", $branch->id);

        if (!empty($start) && !empty($end)) {
            $this->db->where("gi_notes.issue_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gi_notes.issue_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gi_notes.issue_date", $end);
            }
        }
        return $this->get_all();
    }

    public function get_issued_items_category($branch, $start, $end, $category) {
        $this->db->select("gi_notes.issue_date,gi_notes.gi_id,gi_items.qty,gi_items.rate,gi_items.itm_name,gi_items.foc,branches.branch_name,item_categories.cat_name");
        $this->db->join("gi_notes", "gi_items.gi_id = gi_notes.id", "LEFT");
        $this->db->join("items", "gi_items.itm_id = items.id", "LEFT");
        $this->db->join("item_categories", "items.itm_cat = item_categories.id", "LEFT");
        $this->db->join("branches", "gi_notes.shop_id = branches.id", "LEFT");
        $this->db->where("gi_items.branch", $branch->id);

        if (!empty($start) && !empty($end)) {
            $this->db->where("gi_notes.issue_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gi_notes.issue_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gi_notes.issue_date", $end);
            }
        }
        $this->db->where("item_categories.id", $category);
        return $this->get_all();
    }

}
