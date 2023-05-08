<?php

/**
 * Description of supplier_model
 *
 * @author dilshan
 */
class Wl_supplier_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_suppliers";
    }

    public function get_all_branch($branch) {
        $this->db->select("wl_suppliers.*,users.username");
        $this->db->join("users", "wl_suppliers.e_by = users.id", "LEFT");
        $this->db->where("visibility=0 OR (visibility=1 AND wl_suppliers.branch=$branch->id)", NULL, FALSE);
        return $this->get_all();
    }

    public function get_all_branch_active($branch) {
        $this->db->select("wl_suppliers.*,users.username");
        $this->db->join("users", "wl_suppliers.e_by = users.id", "LEFT");
        $this->db->where("(visibility=0 OR (visibility=1 AND wl_suppliers.branch=$branch->id))", NULL, FALSE);
        $this->db->where("wl_suppliers.status", 1);
        return $this->get_all();
    }
    public function get_all_names_branch_active($branch) {
        $this->db->select("wl_suppliers.id,wl_suppliers.company_name,users.username");
        $this->db->join("users", "wl_suppliers.e_by = users.id", "LEFT");
        $this->db->where("(visibility=0 OR (visibility=1 AND wl_suppliers.branch=$branch->id))", NULL, FALSE);
        $this->db->where("wl_suppliers.status", 1);
        return $this->get_all();
    }

    public function save_supplier($branch, $user) {
        $nb_option = $this->input->post("nb_option") == "Other" ? $this->input->post("nb_option_other") : $this->input->post("nb_option");
        $visibility = $this->input->post("visibility");
        $data = array(
            "company_name" => $this->input->post("company_name"),
            "contact_person1" => $this->input->post("contact_person1"),
            "contact_person_prefix1" => $this->input->post("contact_person_prefix1"),
            "contact_person_prefix2" => $this->input->post("contact_person_prefix2"),
            "contact_person2" => $this->input->post("contact_person2"),
            "contact_person_tp1" => $this->input->post("contact_person_tp1"),
            "contact_person_tp2" => $this->input->post("contact_person_tp2"),
            "contact_person_email1" => $this->input->post("contact_person_email1"),
            "contact_person_email2" => $this->input->post("contact_person_email2"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "bis_type" => $nb_option,
            "tax_id" => $this->input->post("tax_id"),
            "bank_name" => $this->input->post("bank_name"),
            "bank_branch" => $this->input->post("bank_branch"),
            "bank_acc_no" => $this->input->post("bank_acc_no"),
            "bank_acc_name" => $this->input->post("bank_acc_name"),
            "bank_swift_code" => $this->input->post("bank_swift_code"),
            "visibility" => isset($visibility) ? 1 : 0,
            "branch" => $branch->id,
            "status" => 1,
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->insert($data);
    }

    public function update_supplier($branch, $user) {
        $nb_option = $this->input->post("nb_option") == "Other" ? $this->input->post("nb_option_other") : $this->input->post("nb_option");
        $id = $this->input->post("id");
        $visibility = $this->input->post("visibility");
        $data = array(
            "company_name" => $this->input->post("company_name"),
            "contact_person_prefix1" => $this->input->post("contact_person_prefix1"),
            "contact_person_prefix2" => $this->input->post("contact_person_prefix2"),
            "contact_person1" => $this->input->post("contact_person1"),
            "contact_person2" => $this->input->post("contact_person2"),
            "contact_person_tp1" => $this->input->post("contact_person_tp1"),
            "contact_person_tp2" => $this->input->post("contact_person_tp2"),
            "contact_person_email1" => $this->input->post("contact_person_email1"),
            "contact_person_email2" => $this->input->post("contact_person_email2"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "bis_type" => $nb_option,
            "tax_id" => $this->input->post("tax_id"),
            "bank_name" => $this->input->post("bank_name"),
            "bank_branch" => $this->input->post("bank_branch"),
            "bank_acc_no" => $this->input->post("bank_acc_no"),
            "bank_acc_name" => $this->input->post("bank_acc_name"),
            "bank_swift_code" => $this->input->post("bank_swift_code"),
            "visibility" => isset($visibility) ? 1 : 0,
            "branch" => $branch->id,
            "status" => $this->input->post("status"),
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->update($id, $data);
    }

    public function insert_sup($data) {
        return $this->insert($data);
    }

}
