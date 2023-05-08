<?php

/**
 * Description of user_model
 *
 * @author Dilshan Jayasanka
 */
class User_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "users";
    }

    public function get_user_company($u_id) {
        $this->db->select("users.id as u_id,username,users.email as u_email,last_login,active,f_name,l_name,contact_1,contact_2,phone,company_id,reg_type,avatar,companies.id as c_id,c_name,address,tp_1,tp_2,companies.email as c_email,logo");
        $this->db->join("companies", "users.company_id= companies.id", "LEFT");
        return $this->get_by("users.id", $u_id);
    }

    public function find_user_by_username($un) {
        return $this->get_by("username", $un);
    }

    public function update_profile($user) {
        $data = array(
            "first_name" => $this->input->post("f_name"),
            "last_name" => $this->input->post("l_name"),
            "phone" => $this->input->post("mobile"),
        );
        $this->update($user->id, $data);
    }

    public function get_uses() {
        $this->db->select("users.*,wl_user_types.display_val");
        $this->db->join("wl_user_types","users.user_type=wl_user_types.user_type","LEFT");
        $this->db->not_like("users.user_type", "superadmin");
        return $this->get_all();
    }
    public function get_sales_persons(){
        $this->db->select("users.username,first_name,id");
        $this->db->where_in("users.user_type",['sales_aget']);
        return $this->get_all();
    }
    public function get_marketing_manager(){
        $this->db->select("users.username,first_name,id");
        $this->db->where_in("users.user_type",['manager','makerting_exe']);
        return $this->get_all();
    }

}
