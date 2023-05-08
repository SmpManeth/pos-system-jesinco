<?php

/**
 * Description of Supplier_return_item_model
 *
 * @author DP4
 * Aug 28, 2018 4:46:27 PM
 */
class Supplier_return_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "supplier_return_items";
    }

    public function check_item($grn_id, $ret_id, $item_id) {
        $this->db->where("grn_id", $grn_id);
        $this->db->where("ret_id", $ret_id);
        return $this->get_by('item_id', $item_id);
    }

    public function get_items($id) {
        $this->db->select("supplier_return_items.*,items.itm_code,items.itm_name,gr_notes.gr_id");
        $this->db->join("items", "supplier_return_items.item_id = items.id", "LEFT");
        $this->db->join("gr_notes", "supplier_return_items.grn_id = gr_notes.id", "LEFT");
        $this->db->where("ret_id", $id);
        return $this->get_all();
    }

    public function get_item_list($id) {
        $this->db->where("ret_id", $id);
        return $this->get_all();
    }

    public function get_report_items($branch, $from, $to) {
        $this->db->select("supplier_return_items.grn_id,supplier_return_items.ret_id,supplier_return_items.qty,supplier_return_items.rate,items.itm_name,wl_suppliers.company_name,items.itm_code,supplier_returns.ret_date");

        $this->db->join("supplier_returns", "supplier_return_items.item_id = supplier_returns.id", "LEFT");
        $this->db->join("items", "supplier_return_items.item_id = items.id", "LEFT");
        $this->db->join("wl_suppliers", "supplier_returns.supplier = wl_suppliers.id", "LEFT");

        $this->db->where("supplier_returns.branch", $branch->id);

        if (!empty($from) && !empty($to)) {
            $this->db->where("supplier_returns.ret_date BETWEEN '$from' AND '$to'", NULL, FALSE);
        } else {
            if (!empty($from)) {
                $this->db->where("supplier_returns.ret_date", $from);
            }
            if (!empty($to)) {
                $this->db->where("supplier_returns.ret_date", $to);
            }
        }
        return $this->get_all();
    }

    public function get_history($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("supplier_returns.id,supplier_returns.ret_date,supplier_returns.`status`,supplier_return_items.qty,supplier_return_items.rate,supplier_return_items.item_id,items.itm_code,items.itm_name");
            $this->db->join("items", "supplier_return_items.item_id = items.id", "LEFT");
        } else {
            $this->db->select("SUM(supplier_return_items.qty) as countt");
        }

        $this->db->join("supplier_returns", "supplier_return_items.ret_id = supplier_returns.id", "LEFT");

        $this->db->where("supplier_returns.branch", $branch->id);
        $this->db->where("supplier_return_items.item_id", $itm_id);
        $this->db->where("supplier_returns.status", 1);

        if (!empty($start) && !empty($end)) {
            $this->db->where("supplier_returns.ret_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("supplier_returns.ret_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("supplier_returns.ret_date", $end);
            }
        }

        $items = $this->get_all();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $qty += doubleval($itm->qty);
                $data["$itm->ret_date"][] = array(
                    "date" => $itm->ret_date,
                    "type" => "sup_ret",
                    "doc_number" => $itm->id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            }
            return [$data, $qty];
        } else {
            return doubleval($items[0]->countt);
        }
    }

    public function get_history_new($branch, $itm_id, $start, $end) {
        $this->db->select("supplier_returns.id,supplier_returns.ret_date,supplier_returns.`status`,supplier_return_items.qty,supplier_return_items.rate,supplier_return_items.item_id,items.itm_code,items.itm_name");
        $this->db->join("items", "supplier_return_items.item_id = items.id", "LEFT");

        $this->db->join("supplier_returns", "supplier_return_items.ret_id = supplier_returns.id AND supplier_returns.branch = " . $branch->id, "LEFT");

        $this->db->where("supplier_returns.branch", $branch->id);
        $this->db->where("supplier_return_items.item_id", $itm_id);
        $this->db->where("supplier_returns.status", 1);

        if (!empty($start) && !empty($end)) {
            $this->db->where("supplier_returns.ret_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("supplier_returns.ret_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("supplier_returns.ret_date", $end);
            }
        }

        $items = $this->get_all();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $qty += doubleval($itm->qty);
            $data["$itm->ret_date"][] = array(
                "date" => $itm->ret_date,
                "type" => "sup_ret",
                "doc_number" => $itm->id,
                "code" => $itm->itm_code,
                "name" => $itm->itm_name,
                "qty" => $itm->qty,
                "rate" => $itm->rate,
            );
        }
        return [$data, $qty];
    }

}
