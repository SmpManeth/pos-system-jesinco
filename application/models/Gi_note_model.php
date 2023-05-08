<?php

/**
 * Description of Gi_note_model
 *
 * @author DP4
 * Sep 17, 2018 6:35:19 PM
 */
class Gi_note_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "gi_notes";
    }

    public function save_note($gid, $branch, $user) {
        $note = $this->get_note_by_id($gid, $branch);
        if (!empty($note)) {
            $data = array(
                "shop_id" => $this->input->post("shop"),
                "issue_date" => $this->input->post("date"),
                "system_date" => date("Y-m-d H:i:s"),
                "issue_ref" => $this->input->post("ref"),
                "supplier" => $this->input->post("supplier"),
                "e_by" => $user->id,
                "e_at" => date("Y-m-d H:i:s"),
            );
            $this->update($note->id, $data);
            return $note->id;
        } else {
            $data = array(
                "shop_id" => $this->input->post("shop"),
                "issue_date" => $this->input->post("date"),
                "system_date" => date("Y-m-d H:i:s"),
                "issue_ref" => $this->input->post("ref"),
//                "supplier" => $this->input->post("supplier"),
                "total" => 0,
                "e_by" => $user->id,
                "e_at" => date("Y-m-d H:i:s"),
                "branch" => $branch->id,
                "status" => 0,
                "discount" => 0,
                "sub_total" => 0,
                "gi_id" => $gid,
            );
            return $this->insert($data);
        }
    }

    public function get_max_id($branch) {
        $this->db->select("MAX(gi_id) as gid");
        $max = $this->get_by("branch", $branch->id);
        $max_inv = intval($max->gid) + 1;
        return $max_inv;
    }

    public function get_note_by_id($id, $branch) {
        $this->db->where("branch", $branch->id);
        return $this->get_by("gi_id", $id);
    }

    public function get_all_invoices($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE) {
        $this->db->select("gi_notes.*,branches.branch_name,users.username");
        $this->db->join("branches", "gi_notes.shop_id = branches.id", "LEFT");
        $this->db->join("users", "gi_notes.e_by = users.id", "LEFT");

        $this->db->where("gi_notes.branch", $branch->id);
        if ($count) {
            return $this->count_by("gi_notes.branch", $branch->id);
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("gi_id", "DESC");
            }
            return $this->get_all();
        }
    }

    public function update_total($id, $amount, $direction) {

        $this->db->set("total", "total+" . ($direction * $amount), FALSE);
        $this->db->set("sub_total", "sub_total+" . ($direction * $amount), FALSE);
        $this->db->where("id", $id);
        $this->db->update("gi_notes");
    }

    public function get_note($id, $branch) {
        $this->db->select("gi_notes.*,branches.branch_name,wl_suppliers.company_name");
        $this->db->join("branches", "gi_notes.shop_id = branches.id", "LEFT");
        $this->db->join("wl_suppliers", "gi_notes.supplier = wl_suppliers.id AND wl_suppliers.branch = gi_notes.branch", "LEFT");
        $this->db->where("gi_notes.gi_id", $id);
        return $this->get_by("gi_notes.branch", $branch->id);
    }

    public function get_today($branch, $date, $list = FALSE) {
        if ($list) {
            $this->db->select("SUM(total) as tot");
        }
        $this->db->where("gi_notes.issue_date", $date);
        $this->db->where("gi_notes.status", 1);

        if ($list) {
            return $this->get_by("gi_notes.branch", $branch->id)->tot;
        } else {
            $this->db->where("gi_notes.branch", $branch->id);
            return $this->get_all();
        }
    }

}
