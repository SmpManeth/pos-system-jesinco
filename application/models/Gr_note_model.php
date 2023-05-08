<?php

/**
 * Description of gr_note_model
 *
 * @author dilshan
 */
class Gr_note_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "gr_notes";
    }

    public function get_from_po_ref($ref, $branch) {
        $this->db->where("gr_notes.branch", $branch->id);
        return $this->get_by("po_id", $ref);
    }

    public function get_notes($branch) {
        $this->db->select("gr_notes.*,wl_suppliers.company_name,users.username");
        $this->db->join("wl_suppliers", "gr_notes.supplier = wl_suppliers.id", "LEFT");
        $this->db->join("users", "gr_notes.e_by = users.id", "LEFT");
        $this->db->where("gr_notes.branch", $branch->id);
        $this->order_by("gr_notes.system_date", "DESC");
        return $this->get_all();
    }

    public function save_grn($branch, $user) {
        $id = $this->input->post("id");
        $grn = $this->get($id);
        $data = array(
            "grn_date" => $this->input->post("grn_date"),
            "po_ref" => $this->input->post("po_ref"),
            "supplier" => $this->input->post("supplier"),
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
            "del_location" => $this->input->post("del_location"),
            "branch" => $branch->id,
            "status" => 0
        );
        if ($grn) {
            $this->update($id, $data);
            return [$id, $grn->gr_id];
        } else {
            $display_gr_id = $this->get_next_grn_id($branch);
            $data["gr_id"] = $display_gr_id;
            $data["total"] = "0";
            $data["sub_total"] = "0";
            $data["system_date"] = date("Y-m-d H:i:s");
            $id = $this->insert($data);
            return [$id, $display_gr_id];
        }
    }

    public function get_next_grn_id($branch) {
        $this->db->select("MAX(gr_id) as inv");
        $max = $this->get_by("branch", $branch->id);
        $max_inv = intval($max->inv) + 1;
        return $max_inv;
    }

    public function update_total($id, $total, $type) {
        if ($type == 1) {
            $this->db->set("total", "total+" . $total, FALSE);
            $this->db->set("sub_total", "sub_total+" . $total, FALSE);
        } else {
            $this->db->set("total", "total-" . $total, FALSE);
            $this->db->set("sub_total", "sub_total-" . $total, FALSE);
        }
        $this->db->where("id", $id);
        $this->db->update("gr_notes");
    }

    public function get_grn($id, $branch) {
        $this->db->select("gr_notes.id,gr_notes.gr_id,gr_notes.sub_total,gr_notes.discount,gr_notes.po_id,gr_notes.grn_date,gr_notes.system_date,gr_notes.po_ref,gr_notes.supplier,gr_notes.total,gr_notes.e_by");
        $this->db->select("gr_notes.e_at,gr_notes.`status`,wl_suppliers.company_name,users.username,del_location");
        $this->db->join("wl_suppliers", "gr_notes.supplier = wl_suppliers.id", "LEFT");
        $this->db->join("users", "gr_notes.e_by = users.id", "LEFT");
        $this->db->where("gr_notes.branch", $branch->id);
        return $this->get_by("gr_notes.gr_id", $id);
    }

    public function find_grn_by_po($id) {
        $this->db->where("po_id", $id);
        return $this->get_all();
    }

    public function temp_grn_items($grn_id) {
        $this->db->where("is_temp", 1);
        $this->db->where("grn_id", $grn_id);
        return $this->get_all();
    }

    public function get_all_branch_sales($s, $e, $branch) {
        $this->db->select("branches.branch_name,branches.branch_name_report,Sum(gr_notes.total) AS tot,gr_notes.grn_date");
        $this->db->join("branches", "gr_notes.branch = branches.id", "LEFT");

        $this->db->where("gr_notes.branch", $branch->id);
        if (!empty($s) && !empty($e)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("gr_notes.grn_date", $s);
            } else if (!empty($e)) {
                $this->db->where("gr_notes.grn_date", $e);
            } else {
                return array();
            }
        }


        $this->db->where("gr_notes.status", 1);

        $this->db->group_by("gr_notes.grn_date,branches.id");
        return $this->get_all();
    }

    public function get_daily_summary_supplier($s, $e, $branch) {
        $this->db->select("wl_suppliers.company_name,Count(gr_notes.id) grn_count,Sum(gr_notes.total) as sum_tot,gr_notes.grn_date,gr_notes.system_date,gr_notes.po_ref,gr_notes.supplier");
        $this->db->join("wl_suppliers", "gr_notes.supplier = wl_suppliers.id", "LEFT");
        $this->db->where("gr_notes.status", 1);
        $this->db->where("gr_notes.branch", $branch->id);
        if (!empty($s) && !empty($e)) {
            $this->db->where("gr_notes.grn_date BETWEEN '$s' AND '$e'", NULL, FALSE);
        } else {
            if (!empty($s)) {
                $this->db->where("gr_notes.grn_date", $s);
            }
            if (!empty($e)) {
                $this->db->where("gr_notes.grn_date", $e);
            }
        }
        $this->db->group_by("gr_notes.supplier");
        return $this->get_all();
    }

    public function get_today($branch, $date, $list = FALSE) {
        if ($list) {
            $this->db->select("SUM(total) as tot");
        }
        $this->db->where("gr_notes.grn_date", $date);
        $this->db->where("gr_notes.status", 1);

        if ($list) {
            return $this->get_by("gr_notes.branch", $branch->id)->tot;
        } else {
            $this->db->where("gr_notes.branch", $branch->id);
            return $this->get_all();
        }
    }

}
