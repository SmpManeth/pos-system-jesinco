<?php

/**
 * Description of branch_model
 *
 * @author dilshan
 */
class Branch_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "branches";
    }

    public function get_branches_all($status = FALSE) {
        $this->db->select("branches.*,users.username");
        $this->db->join("users", "branches.e_by = users.id", "LEFT");
        if ($status) {
            $this->db->where("branches.status", $status);
        }
        return $this->get_all();
    }

    public function get_branches_without_this($branch, $status = FALSE) {
        $this->db->select("branches.*,users.username");
        $this->db->join("users", "branches.e_by = users.id", "LEFT");
        if ($status) {
            $this->db->where("branches.status", $status);
        }
        $this->db->not_like("branches.id", $branch->id);
        return $this->get_all();
    }

    public function save_branch($com_id, $user) {
        $data = array(
            "company_id" => $com_id,
            "branch_name" => $this->input->post("company_name"),
            "branch_name_report" => $this->input->post("branch_name_report"),
            "email" => $this->input->post("email"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "status" => 1,
            "bank_name" => $this->input->post("bank_name"),
            "bank_branch" => $this->input->post("bank_branch"),
            "bank_acc_no" => $this->input->post("bank_acc_no"),
            "bank_acc_name" => $this->input->post("bank_acc_name"),
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s")
        );
        $this->insert($data);
    }

    public function update_branch($com_id, $user) {
        $id = $this->input->post("id");
        $main_branch = $this->input->post("main_branch");
        $data = array(
            "company_id" => $com_id,
            "branch_name" => $this->input->post("company_name"),
            "branch_name_report" => $this->input->post("branch_name_report"),
            "email" => $this->input->post("email"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "status" => $this->input->post("status"),
            "main_branch" => isset($main_branch) ? 1 : 0,
            "bank_name" => $this->input->post("bank_name"),
            "bank_branch" => $this->input->post("bank_branch"),
            "bank_acc_no" => $this->input->post("bank_acc_no"),
            "bank_acc_name" => $this->input->post("bank_acc_name"),
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s")
        );
        $this->update($id, $data);
    }

    public function get_branches($user) {
        if ($user->branches == "all" || $user->user_type == "admin" || $user->user_type == "superadmin") {
            return $this->get_all();
        } else {
//            return $this->get_all();

            $branch_list = json_decode($user->branches);
            if (empty($branch_list)) {
                return array();
            }
            return $this->get_many($branch_list);
        }
    }

}
