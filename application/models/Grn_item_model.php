<?php

/**
 * Description of grn_item_model
 *
 * @author dilshan
 */
class Grn_item_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "grn_items";
    }

    public function add_item($order_id) {
        $data = array(
            "grn_id" => $order_id,
            "item_id" => $this->input->post("item"),
            "qty" => $this->input->post("qty"),
            "price" => $this->input->post("rate"),
            "foc" => $this->input->post("foc")
        );
        return $this->insert($data);
    }

    public function get_items($grn_id) {
        $this->db->where("grn_id", $grn_id);
        return $this->get_all();
    }

    public function update_item_qty($grn_id, $itm_id, $qty, $type) {
        if ($type == 1) {
            $this->db->set("qty", "qty+" . $qty, FALSE);
        } else {
            $this->db->set("qty", "qty-" . $qty, FALSE);
        }
        $this->db->where("grn_id", $grn_id);
        $this->db->where("item_id", $itm_id);
        $this->db->update("grn_items");
    }

    public function get_items_with_details($po_id) {
        $this->db->select("grn_items.id,grn_items.foc,grn_items.is_temp,grn_items.grn_id,grn_items.item_id,grn_items.qty,grn_items.price,items.itm_code,items.itm_name,items.unique_type");
        $this->db->join("items", "grn_items.item_id = items.id", "LEFT");
        $this->db->where("grn_id", $po_id);
        return $this->get_all();
    }

    public function temp_grn_items($po_id) {
        $this->db->where("is_temp", 1);
        $this->db->where("grn_id", $po_id);
        return $this->get_all();
    }

    public function get_daily_summary($start, $end, $s, $branch) {
        $this->db->select("gr_notes.gr_id,gr_notes.grn_date,items.itm_name,items.itm_code,wl_suppliers.company_name,grn_items.grn_id,grn_items.qty,grn_items.price,grn_items.is_temp,grn_items.foc");
        $this->db->join("gr_notes", "gr_notes ON grn_items.grn_id = gr_notes.id", "LEFT");
        $this->db->join("wl_suppliers", "wl_suppliers ON gr_notes.supplier = wl_suppliers.id", "LEFT");
        $this->db->join("items", "items ON grn_items.item_id = items.id", "LEFT");
        if (!empty($start) && !empty($end)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$start' AND '$end'", null, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gr_notes.grn_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gr_notes.grn_date", $end);
            }
        }
        $this->db->where("gr_notes.branch", $branch->id);
        if ($s) {
            $this->db->where("gr_notes.supplier", $s);
        }
        return $this->get_all();
    }

    public function get_daily_summary_category_vice($start, $end, $c, $branch) {
        $this->db->select("gr_notes.grn_date,items.itm_name,items.itm_code,wl_suppliers.company_name,grn_items.grn_id,grn_items.qty,grn_items.price,grn_items.is_temp,grn_items.foc");
        $this->db->join("gr_notes", "gr_notes ON grn_items.grn_id = gr_notes.id", "LEFT");
        $this->db->join("wl_suppliers", "wl_suppliers ON gr_notes.supplier = wl_suppliers.id", "LEFT");
        $this->db->join("items", "items ON grn_items.item_id = items.id", "LEFT");
        $this->db->join("item_categories", "items.itm_cat = item_categories.id", "LEFT");
        if (!empty($start) && !empty($end)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$start' AND '$end'", null, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gr_notes.grn_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gr_notes.grn_date", $end);
            }
        }
        $this->db->where("gr_notes.branch", $branch->id);
        if ($c) {
            $this->db->where("items.itm_cat", $c);
        }
        return $this->get_all();
    }

    public function get_purchasing_summary($branch, $date) {
        $this->db->select("Sum(grn_items.qty) as qty,grn_items.item_id");
        $this->db->join("gr_notes", "grn_items.grn_id = gr_notes.id", "LEFT");
        $this->db->where("gr_notes.status", 1);
        $this->db->where("gr_notes.branch", $branch->id);
        $this->db->where("gr_notes.grn_date", $date);
        $this->db->group_by("grn_items.item_id");
        return $this->get_all();
    }

    public function get_history($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("grn_items.*,gr_notes.grn_date,items.itm_code,items.itm_name,gr_notes.gr_id");
            $this->db->join("items", "grn_items.item_id = items.id", "LEFT");
        } else {
            $this->db->select("SUM(grn_items.qty) as countt");
        }
        $this->db->join("gr_notes", "grn_items.grn_id = gr_notes.id", "LEFT");

        $this->db->where("gr_notes.status", 1);
        $this->db->where("gr_notes.branch", $branch->id);
        if ($itm_id) {
            $this->db->where("grn_items.item_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gr_notes.grn_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gr_notes.grn_date", $end);
            }
        }
        $items = $this->get_all();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $qty += doubleval($itm->qty);
                if ($itm_id) {
                    $data["$itm->grn_date"][] = array(
                        "type" => "grn",
                        "date" => $itm->grn_date,
                        "doc_number" => $itm->gr_id,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->price,
                    );
                } else {
                    $data["g-$itm->itm_code"][] = array(
                        "type" => "grn",
                        "date" => $itm->grn_date,
                        "doc_number" => $itm->gr_id,
                        "code" => $itm->itm_code,
                        "name" => $itm->itm_name,
                        "qty" => $itm->qty,
                        "rate" => $itm->price,
                    );
                }
            }
            return [$data, $qty];
        } else {
            return doubleval($items[0]->countt);
        }
    }

    public function get_history_new($branch, $itm_id, $start, $end) {
        $this->db->select("grn_items.*,gr_notes.grn_date,items.itm_code,items.itm_name,gr_notes.gr_id");
        $this->db->join("items", "grn_items.item_id = items.id", "LEFT");
        $this->db->join("gr_notes", "grn_items.grn_id = gr_notes.id AND gr_notes.branch = " . $branch->id, "LEFT");

        $this->db->where("gr_notes.status", 1);
        $this->db->where("gr_notes.branch", $branch->id);
        if ($itm_id) {
            $this->db->where("grn_items.item_id", $itm_id);
        }

        if (!empty($start) && !empty($end)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("gr_notes.grn_date", $start);
            }
            if (!empty($end)) {
                $this->db->where("gr_notes.grn_date", $end);
            }
        }
        $items = $this->get_all();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $qty += doubleval($itm->qty);
            if ($itm_id) {
                $data["$itm->grn_date"][] = array(
                    "type" => "grn",
                    "date" => $itm->grn_date,
                    "doc_number" => $itm->gr_id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->price,
                );
            } else {
                $data["g-$itm->itm_code"][] = array(
                    "type" => "grn",
                    "date" => $itm->grn_date,
                    "doc_number" => $itm->gr_id,
                    "code" => $itm->itm_code,
                    "name" => $itm->itm_name,
                    "qty" => $itm->qty,
                    "rate" => $itm->price,
                );
            }
        }
        return [$data, $qty];
    }

    public function get_item_sum_report($branch,$from,$to,$assoc=FALSE){
        $this->db->select("grn_items.item_id,grn_items.qty,Sum(grn_items.qty) as sold");
        $this->db->join("gr_notes","grn_items.grn_id = gr_notes.id","LEFT");
        $this->db->where("gr_notes.status",1);
        $this->db->where("gr_notes.branch",$branch);
        if(!empty($from) && !empty($to)){
            $this->db->where("gr_notes.grn_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("gr_notes.grn_date",$from);
            }
            if(!empty($to)){
                $this->db->where("gr_notes.grn_date",$to);
            }
        }
        $this->db->group_by("grn_items.item_id");
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

    public function get_total_grn_summary($branch, $from, $to){
        $this->db->select("gr_notes.grn_date,items.itm_code,items.itm_name,grn_items.qty,grn_items.price,gr_notes.total");
        $this->db->select("grn_items.grn_id,gr_notes.gr_id,gr_notes.discount,gr_notes.del_location");

        $this->db->join("gr_notes","grn_items.grn_id = gr_notes.id","LEFT");
        $this->db->join("items","grn_items.item_id = items.id","LEFT");

        $this->db->where("gr_notes.`status`",1);
        $this->db->where("gr_notes.branch",$branch);

        if(!empty($from) && !empty($to)){
            $this->db->where("gr_notes.grn_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("gr_notes.grn_date",$from);
            }
            if(!empty($to)){
                $this->db->where("gr_notes.grn_date",$to);
            }
        }
        return $this->get_all();
    }

}
