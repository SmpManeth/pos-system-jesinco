<?php

/**
 * Description of common_model
 *
 * @author dilshan
 */
class Common_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_countries() {
        return $this->db->get("countries");
    }

    public function adjust_the_stock($item_id, $branch, $direction, $qty, $remark, $user) {
        $data = array(
            "itm_id" => $item_id,
            "branch_id" => $branch->id,
            "direction" => $direction,
            "remarks" => $remark,
            "qty" => $qty,
            "user" => $user->id,
            "adj_time" => date("Y-m-d H:i:s")
        );
        $this->db->insert("stock_adjustments", $data);
    }

    public function get_adjustments($branch, $item_id = FALSE) {
        $this->db->select("stock_adjustments.*,users.first_name,items.itm_code,items.itm_name,users.username");
        $this->db->join("users", "stock_adjustments.user = users.id");
        $this->db->join("items", "stock_adjustments.itm_id = items.id");
        if ($item_id) {
            $this->db->where("itm_id", $item_id);
            $this->db->limit(50);
        }
        $this->db->order_by("adj_time", "DESC");
        $this->db->where("branch_id", $branch->id);
        return $this->db->get("stock_adjustments")->result_object();
    }
    public function get_adjustments_report($branch, $from, $to, $item_id = FALSE) {
        $this->db->select("stock_adjustments.*,users.first_name,items.itm_code,items.itm_name,users.username");
        $this->db->join("users", "stock_adjustments.user = users.id");
        $this->db->join("items", "stock_adjustments.itm_id = items.id");
        if (!empty($item_id)) {
            $this->db->where("itm_id", $item_id);
        }
        if(!empty($from) && !empty($to)){
            $this->db->where("DATE(`stock_adjustments`.`adj_time`) BETWEEN '$from' AND '$to'", NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("DATE(`stock_adjustments`.`adj_time`)", $from);
            }
            if(!empty($to)){
                $this->db->where("DATE(`stock_adjustments`.`adj_time`)", $to);
            }
        }
        $this->db->order_by("adj_time", "ASC");
        $this->db->where("branch_id", $branch->id);
        return $this->db->get("stock_adjustments")->result_object();
    }

    public function get_Logs($limit, $branch) {
        $this->db->select("`logs`.section,`logs`.action,`logs`.by,`logs`.`at`,`logs`.ip,`logs`.browser,`logs`.branch,users.username");
        $this->db->join("users", "logs.by = users.id", "LEFT");
        $this->db->where("logs.branch", $branch->id);
        $this->db->limit($limit);
        $this->db->order_by("`logs`.`at`", "DESC");
        return $this->db->get("logs")->result_object();
    }
    public function get_Logs_days($days, $branch) {
        $this->db->select("`logs`.section,`logs`.action,`logs`.by,`logs`.`at`,`logs`.ip,`logs`.browser,`logs`.branch,users.username");
        $this->db->join("users", "logs.by = users.id", "LEFT");
        $this->db->where("logs.branch", $branch->id);
        $this->db->where("logs.at >=", date("Y-m-d", strtotime('-'.$days.' day')));
        $this->db->order_by("`logs`.`at`", "DESC");
        return $this->db->get("logs")->result_object();
    }
    public function get_Logs_report($branch,$from,$to) {
        $this->db->select("`logs`.section,`logs`.action,`logs`.by,`logs`.`at`,`logs`.ip,`logs`.browser,`logs`.branch,users.username");
        $this->db->join("users", "logs.by = users.id", "LEFT");
        if(!empty($from) && !empty($to)){
            $this->db->where("DATE(`logs`.`at`) BETWEEN '$from' AND '$to'", NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("DATE(`logs`.`at`)", $from);
            }
            if(!empty($to)){
                $this->db->where("DATE(`logs`.`at`)", $to);
            }
        }
        $this->db->where("logs.branch", $branch->id);
        $this->db->order_by("`logs`.`at`", "ASC");
        return $this->db->get("logs")->result_object();
    }

    public function get_user_levels() {
        $this->db->select("wl_user_types.*");
        return $this->db->get("wl_user_types")->result_object();
    }

    public function get_history_adjust($branch, $itm_id, $start, $end, $not_count_only = FALSE) {
        if (!$not_count_only) {
            $this->db->select("*");
        } else {
            $this->db->select("SUM(stock_adjustments.qty * stock_adjustments.direction) as countt,adj_time,direction");
        }
        if ($itm_id) {
            $this->db->where("itm_id", $itm_id);
        }
        $this->db->where("branch_id", $branch->id);

        if (!empty($start) && !empty($end)) {
            $this->db->where("DATE(adj_time) BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("DATE(adj_time)", $start);
            }
            if (!empty($end)) {
                $this->db->where("DATE(adj_time)", $end);
            }
        }
        $this->db->from("stock_adjustments");
        $items = $this->db->get()->result_object();
        if (!$not_count_only) {
            $data = array();
            $qty = 0;
            foreach ($items as $itm) {
                $dt = date("Y-m-d", strtotime($itm->adj_time));
                if ($itm->direction == "1") {
                    $qty += doubleval($itm->qty);
                } else {
                    $qty -= doubleval($itm->qty);
                }
                if ($itm_id) {
                    $data["$dt"][] = array(
                        "date" => $dt,
                        "type" => "adjust",
                        "doc_number" => $itm->direction == "1" ? "Positive" : "Negative",
                        "code" => "",
                        "name" => "",
                        "qty" => $itm->qty,
                        "rate" => "",
                    );
                } else {
                    $data["ad-$itm_id"][] = array(
                        "date" => $dt,
                        "type" => "adjust",
                        "doc_number" => $itm->direction == "1" ? "Positive" : "Negative",
                        "code" => "",
                        "name" => "",
                        "qty" => $itm->qty,
                        "rate" => "",
                    );
                }
            }
            return [$data, $qty];
        } else {
            return doubleval($items[0]->countt);
        }
    }

    public function get_history_adjust_new($branch, $itm_id, $start, $end) {
        $this->db->select("*");
        if ($itm_id) {
            $this->db->where("itm_id", $itm_id);
        }
        $this->db->where("branch_id", $branch->id);

        if (!empty($start) && !empty($end)) {
            $this->db->where("DATE(adj_time) BETWEEN '$start' AND '$end'", NULL, FALSE);
        } else {
            if (!empty($start)) {
                $this->db->where("DATE(adj_time)", $start);
            }
            if (!empty($end)) {
                $this->db->where("DATE(adj_time)", $end);
            }
        }
        $this->db->from("stock_adjustments");
        $items = $this->db->get()->result_object();
        $data = array();
        $qty = 0;
        foreach ($items as $itm) {
            $dt = date("Y-m-d", strtotime($itm->adj_time));
            if ($itm->direction == "1") {
                $qty += doubleval($itm->qty);
            } else {
                $qty -= doubleval($itm->qty);
            }
            if ($itm_id) {
                $data["$dt"][] = array(
                    "date" => $dt,
                    "type" => "adjust",
                    "doc_number" => $itm->direction == "1" ? "Positive" : "Negative",
                    "code" => "",
                    "name" => "",
                    "qty" => $itm->qty,
                    "rate" => "",
                );
            } else {
                $data["ad-$itm_id"][] = array(
                    "date" => $dt,
                    "type" => "adjust",
                    "doc_number" => $itm->direction == "1" ? "Positive" : "Negative",
                    "code" => "",
                    "name" => "",
                    "qty" => $itm->qty,
                    "rate" => "",
                );
            }
        }
        return [$data, $qty];
    }

    public function get_cities() {
        $this->db->select("wl_cities.*");
        return $this->db->get("wl_cities")->result_object();
    }

}
