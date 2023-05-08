<?php

/**
 * Description of Invoice_item_model
 *
 * @author DP4
 * Aug 20, 2018 2:39:57 PM
 */
class Invoice_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "invoice_items";
    }

    public function save_items($id, $branch, $item) {
        $qty = $this->input->post("qty");
        $rate = $this->input->post("rate");
        $display_qty = $this->input->post("qty_display");
        $display_rate = $this->input->post("rate_display");
        $unit_measure = $this->input->post("unit_measure");
        $data = array(
            "inv_id" => $id,
            "itm_id" => $item->item_id,
            "itm_code" => $item->itm_code,
            "itm_name" => $item->itm_name . "" . ($unit_measure ? " (" . strtoupper($unit_measure) . ")" : ""),
            "qty" => $qty,
            "rate" => $rate,
            "display_qty" => $display_qty ? $display_qty : $qty,
            "display_rate" => $display_qty ? $display_rate : $rate,
            "branch" => $branch->id
        );
        return $this->insert($data);
    }

    public function get_items($id) {
        $this->db->where("inv_id", $id);
        return $this->get_all();
    }
    public function get_items_by_ids($keys) {
        $this->db->where_in("id", $keys);
        return $this->get_all();
    }

    public function get_invoice_items($id) {
        $this->db->select("itm_code,itm_name,qty,rate,display_rate");
        $this->db->where("inv_id", $id);
        return $this->get_all();
    }

    public function get_invoice_sales_summary($branch, $date) {
        $this->db->select("Sum(invoice_items.display_qty) AS qty,Sum(invoice_items.display_qty * invoice_items.display_rate) AS amount,invoice_items.itm_id,invoice_items.itm_code,invoice_items.itm_name,invoice_items.rate");
        $this->db->join("invoices", "invoice_items.inv_id = invoices.id AND invoices.`status` = 1", "LEFT");
        $this->db->where("invoices.branch", $branch->id);
        $this->db->where("invoices.status", 1);
        $this->db->where("invoices.inv_date", $date);
        $this->db->group_by(array("invoice_items.itm_id"));
        $q = $this->get_all();
        return $q;
    }

    public function get_history($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("invoice_items.*,invoices.inv_id as invid,invoices.inv_date,invoices.`status`,invoices.is_cash");
        } else {
            $this->db->select("SUM(invoice_items.qty) as countt");
        }
        $this->db->join("invoices", "invoice_items.inv_id = invoices.id AND invoices.status=1", "LEFT");
        $this->db->where("invoice_items.branch", $branch->id);

        if ($itm_id) {
            $this->db->where("itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("invoices.inv_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("invoices.inv_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("invoices.inv_date", $end);
            }
        }

        $items = $this->get_all();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $qty += doubleval($itm->qty);
                if ($itm_id) {
                    $data["$itm->inv_date"][] = array(
                        "type" => "inv",
                        "date" => $itm->inv_date,
                        "doc_number" => $itm->invid,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->rate,
                    );
                } else {
                    $data["i-$itm->itm_code"][] = array(
                        "type" => "inv",
                        "date" => $itm->inv_date,
                        "doc_number" => $itm->invid,
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
        $this->db->select("invoice_items.*,invoices.inv_id as invid,invoices.inv_date,invoices.`status`,invoices.is_cash");

        $this->db->join("invoices", "invoice_items.inv_id = invoices.id AND invoices.status=1 AND invoices.branch = " . $branch->id, "LEFT");
        $this->db->where("invoice_items.branch", $branch->id);

        if ($itm_id) {
            $this->db->where("invoice_items.itm_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("invoices.inv_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("invoices.inv_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("invoices.inv_date", $end);
            }
        }

        $items = $this->get_all();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $qty += doubleval($itm->qty);
            if ($itm_id) {
                $data["$itm->inv_date"][] = array(
                    "type" => "inv",
                    "date" => $itm->inv_date,
                    "doc_number" => $itm->invid,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            } else {
                $data["i-$itm->itm_code"][] = array(
                    "type" => "inv",
                    "date" => $itm->inv_date,
                    "doc_number" => $itm->invid,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->rate,
                );
            }
        }
        return [$data, $qty];
    }

    public function get_item_sum_report($branch,$from,$to,$assoc=FALSE){
        $this->db->select("Sum(invoice_items.qty) AS sold,invoice_items.itm_id,invoices.created_at");
        $this->db->join("invoices","invoice_items.inv_id = invoices.id","LEFT");
        $this->db->where_in("invoices.status",[1, 3, 4, 5]);
        $this->db->where("invoices.branch",$branch);
        if(!empty($from) && !empty($to)){
            $this->db->where("invoices.created_at BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("invoices.created_at",$from);
            }
            if(!empty($to)){
                $this->db->where("invoices.created_at",$to);
            }
        }
        $this->db->group_by("invoice_items.itm_id");
        if($assoc){
            $data = $this->get_all();
            $assoc_data = array();
            foreach ($data as $_data) {
                $assoc_data[$_data->itm_id] = $_data;
            }
            return $assoc_data;

        } else {
            return $this->get_all();
        }
    }
    public function get_return_item_sum_report($branch,$from,$to,$assoc=FALSE){
        $this->db->select("invoice_items.itm_id,invoice_items.itm_code,invoice_items.itm_name,Sum(invoice_items.ret_qty) AS sold,invoice_items.ret_date");

        $this->db->where("invoice_items.branch",$branch);
        if(!empty($from) && !empty($to)){
            $this->db->where("invoice_items.ret_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("invoice_items.ret_date",$from);
            }
            if(!empty($to)){
                $this->db->where("invoice_items.ret_date",$to);
            }
        }
        $this->db->group_by("invoice_items.itm_id");
        $this->db->having("sold > 0",NULL,FALSE);

        if($assoc){
            $data = $this->get_all();
            $assoc_data = array();
            foreach ($data as $_data) {
                $assoc_data[$_data->itm_id] = $_data;
            }
            return $assoc_data;

        } else {
            return $this->get_all();
        }
    }

    public function get_branch_vice_sold_items($branch, $from, $to){
        $this->db->select("Sum(invoice_items.qty) AS sold,invoice_items.itm_id,Sum(invoice_items.rate * invoice_items.qty) AS tot,invoices.inv_date,items.itm_code,items.itm_name,Sum(invoices.balance) as bal");

        $this->db->join("invoices","invoice_items.inv_id = invoices.id","LEFT");
        $this->db->join("items","invoice_items.itm_id = items.id","LEFT");
        $this->db->where("invoice_items.branch",$branch);
        $this->db->where_in("invoices.status",[1, 3, 4, 5]);
        if(!empty($from) && !empty($to)){
            $this->db->where("invoices.inv_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("invoices.inv_date",$from);
            }
            if(!empty($to)){
                $this->db->where("invoices.inv_date",$to);
            }
        }
        $this->db->group_by("invoice_items.itm_id");
        $this->db->having("sold > 0",NULL,FALSE);

        
        return $this->get_all();
        
    }

}
