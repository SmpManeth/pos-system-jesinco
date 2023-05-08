<?php

/**
 * Description of wl_employee_model
 *
 * @author dilshan
 */
class Wl_employee_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_employees";
    }
    
    public function get_all_emps($branch) {
        $this->db->select("wl_employees.*,users.username");
        $this->db->join("users", "wl_employees.e_by = users.id","LEFT");
        $this->db->where("wl_employees.branch", $branch->id);
        return $this->get_all();
    }

    public function save_employee($branch,$user) {
        $data = array(
            "emp_prefix" => $this->input->post("emp_prefix"),
            "emp_name" => $this->input->post("emp_name"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "nic" => $this->input->post("nic"),
            "office_ext" => $this->input->post("of_ext"),
            "designation" => $this->input->post("emp_desig"),
            "p_email" => $this->input->post("p_email"),
            "o_email" => $this->input->post("o_email"),
            "branch" => $branch->id,
            "status" => 1,
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->insert($data);
    }
    public function update_employee($branch,$user) {
        $id = $this->input->post("id");
        $data = array(
            "emp_prefix" => $this->input->post("emp_prefix"),
            "emp_name" => $this->input->post("emp_name"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "nic" => $this->input->post("nic"),
            "office_ext" => $this->input->post("of_ext"),
//            "bank_name" => $this->input->post("bank_name"),
//            "bank_branch" => $this->input->post("bank_branch"),
//            "bank_acc_no" => $this->input->post("bank_acc_no"),
//            "bank_acc_name" => $this->input->post("bank_acc_name"),
            "designation" => $this->input->post("emp_desig"),
            "p_email" => $this->input->post("p_email"),
            "o_email" => $this->input->post("o_email"),
            "branch" => $branch->id,
            "status" => $this->input->post("status"),
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->update($id,$data);
    }
    public function get_employee($id) {
        $this->db->select("wl_employees.*,users.username");
        $this->db->join("users", "wl_employees.id = users.reg_id","LEFT");
        return $this->get_by("wl_employees.id",$id);
    }
    
    public function get_employee_list($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE, $search = FALSE) {
        $this->db->select("wl_employees.*,users.username");
        $this->db->join("users", "wl_employees.e_by = users.id", "LEFT");
        if ($search) {
            $this->db->group_start();
            $this->db->like("wl_employees.emp_name", $search);
            $this->db->or_like("wl_employees.address_city", $search);
            $this->db->or_like("wl_employees.address_line1", $search);
            $this->db->or_like("wl_employees.address_line2", $search);
            $this->db->or_like("wl_employees.tp1", $search);
            $this->db->or_like("wl_employees.tp2", $search);
            $this->db->or_like("wl_employees.nic", $search);
            $this->db->group_end();
        }
        if ($length) {
            $this->db->limit($length, ($start));
        }
        if ($column) {
            $this->db->order_by($column, $direction);
        }
        if ($count) {
            return $this->count_by("wl_employees.branch", $branch->id);
        } else {
            $this->db->where("wl_employees.branch", $branch->id);
            return $this->get_all();
        }
    }

}
