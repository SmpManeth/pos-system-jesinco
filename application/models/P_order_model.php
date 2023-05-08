<?php

/**
 * Description of p_order_model
 *
 * @author dilshan
 */
class P_order_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "p_orders";
    }

    public function save_po($branch, $user) {
        $id = $this->input->post("id");
        $po = $this->get($id, $branch);
        $data = array(
            "branch_id" => $branch->id,
            "p_date" => $this->input->post("po_date"),
            "del_date" => $this->input->post("del_date"),
            "supplier" => $this->input->post("supplier"),
            "po_ref" => $this->input->post("po_ref"),
            "del_location" => $this->input->post("del_location"),
            "e_at" => date("Y-m-d H:i:s"),
            "status" => 0
        );
        if (empty($po)) {
            $display_po_id = $this->get_next_po_id($branch);
            $data["po_id"] = $display_po_id;
            $data["total"] = 0;
            $data["system_date"] = date("Y-m-d H:i:s");
            $data["e_by"] = $user->id;
            $id = $this->insert($data);
            return [$id, $display_po_id];
        } else {
            $this->update($id, $data);
            return [$id, $po->po_id];
        }
    }

    public function get_next_po_id($branch) {
        $this->db->select("MAX(po_id) as pid");
        $max = $this->get_by("branch_id", $branch->id);
        $max_pid = intval($max->pid) + 1;
        return $max_pid;
    }

    public function get_po($id, $branch) {
        $this->db->select("p_orders.*,wl_suppliers.company_name,users.username");
        $this->db->join("wl_suppliers", "p_orders.supplier=wl_suppliers.id", "LEFT");
        $this->db->join("users", "p_orders.e_by=users.id", "LEFT");
        $this->db->where("p_orders.branch_id", $branch->id);
        return $this->get_by("p_orders.po_id", $id);
    }

    public function update_total($id, $total, $type) {
        if ($type == 1) {
            $this->db->set("total", "total+" . $total, FALSE);
        } else {
            $this->db->set("total", "total-" . $total, FALSE);
        }
        $this->db->where("id", $id);
        $this->db->update("p_orders");
    }

    public function get_orders($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE) {
        $this->db->select("p_orders.*,wl_suppliers.company_name,users.username");
        $this->db->join("wl_suppliers", "p_orders.supplier=wl_suppliers.id", "LEFT");
        $this->db->join("users", "p_orders.e_by=users.id", "LEFT");
        $this->db->where("branch_id", $branch->id);
        if ($length) {
            $this->db->limit($length, $start);
        }
        if ($column) {
            $this->db->order_by($column, $direction);
        } else {
            $this->db->order_by("p_orders.id", "DESC");
        }
        return $this->get_all();
    }

    public function get_today($branch, $date, $list = FALSE) {
        if ($list) {
            $this->db->select("SUM(total) as tot");
        }
        $this->db->where("p_orders.p_date", $date);
        $this->db->where("p_orders.status", 1);
        if ($list) {
            return $this->get_by("p_orders.branch_id", $branch->id)->tot;
        } else {
            $this->db->where("p_orders.branch_id", $branch->id);
            return $this->get_all();
        }
    }


    public function get_orders_report($branch, $from,$to) {
        $this->db->select("p_orders.*,wl_suppliers.company_name,users.username");
        $this->db->join("wl_suppliers", "p_orders.supplier=wl_suppliers.id", "LEFT");
        $this->db->join("users", "p_orders.e_by=users.id", "LEFT");
        $this->db->where("branch_id", $branch);
        if(!empty($from) && !empty($to)){
            $this->db->where("p_orders.p_date BETWEEN '$from' AND '$to' ",NULL,FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("p_orders.p_date",$from);
            }
            if(!empty($to)){
                $this->db->where("p_orders.p_date",$to);
            }
        }
        return $this->get_all();
    }

}
