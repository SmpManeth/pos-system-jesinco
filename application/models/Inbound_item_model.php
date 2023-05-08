<?php

/**
 * Description of Inbound_item_model
 *
 * @author DP4
 * Sep 21, 2018 5:09:24 PM
 */
class Inbound_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "inbound_items";
    }

    public function get_latest_items($branch, $limit) {
        $this->db->select("users.username,inbound_items.*");
        $this->db->join("users", "inbound_items.user = users.id", "LEFT");
        $this->db->where("inbound_items.branch", $branch->id);
        $this->db->order_by("inbound_items.use_date", "DESC");
        $this->db->limit($limit);
        return $this->get_all();
    }

    public function get_history($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("inbound_items.*");
        } else {
            $this->db->select("SUM(inbound_items.qty) as countt");
        }
        $this->db->where("inbound_items.branch", $branch->id);
        $this->db->where("inbound_items.status", 1);
        if ($itm_id) {
            $this->db->where("itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("inbound_items.use_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("inbound_items.use_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("inbound_items.use_date", $end);
            }
        }

        $items = $this->get_all();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $qty += doubleval($itm->qty);
                if ($itm_id) {
                    $data["$itm->use_date"][] = array(
                        "type" => "inb",
                        "date" => $itm->use_date,
                        "doc_number" => $itm->id,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->rate,
                    );
                } else {
                    $data["i-$itm->itm_code"][] = array(
                        "type" => "inb",
                        "date" => $itm->use_date,
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
        $this->db->select("inbound_items.*");
        $this->db->where("inbound_items.branch", $branch->id);
        $this->db->where("inbound_items.status", 1);
        if ($itm_id) {
            $this->db->where("inbound_items.itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("inbound_items.use_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("inbound_items.use_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("inbound_items.use_date", $end);
            }
        }

        $items = $this->get_all();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $qty += doubleval($itm->qty);
            if ($itm_id) {
                $data["$itm->use_date"][] = array(
                    "type" => "inb",
                    "date" => $itm->use_date,
                    "doc_number" => $itm->id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            } else {
                $data["i-$itm->itm_code"][] = array(
                    "type" => "inb",
                    "date" => $itm->use_date,
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

    public function get_today($branch, $date, $list = FALSE) {
        if ($list) {
            $this->db->select("SUM(qty * rate) as total");
        } else {
            $this->db->select("(qty * rate) as total");
        }
        $this->db->where("inbound_items.use_date", $date);
        $this->db->where("inbound_items.status", 1);
        if ($list) {
            return $this->get_by("inbound_items.branch", $branch->id)->tot;
        } else {
            $this->db->where("inbound_items.branch", $branch->id);
            return $this->get_all();
        }
    }

}
