<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wl_customer_model
 *
 * @author dilshan
 */
class Wl_customer_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_customers";
    }

    public function get_all_customers($branch) {
        $this->db->select("wl_customers.*,users.username,devisions.devision");
        $this->db->join("users", "wl_customers.e_by = users.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->where("(visibility=0 OR (visibility=1 AND wl_customers.branch=$branch->id))", NULL, FALSE);
        $this->db->where("wl_customers.status", 1);
        return $this->get_all();
    }

    public function get_all_customer_report($branch,$user,$from,$to) {
        $this->db->select("wl_customers.*,devisions.devision");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id","LEFT");
        
        if(!empty($from) && !empty($to)){
            $this->db->where("wl_customers.e_at BETWEEN '$from' AND '$to'", NULL, FALSE);
        }else{
            if(!empty($from)){
                $this->db->where("wl_customers.e_at = '$from'", NULL, FALSE);
            }
            if(!empty($to)){
                $this->db->where("wl_customers.e_at = '$to'", NULL, FALSE);
            }
        }
        if($user){
            $this->db->where("wl_customers.e_by", $user);
        }
        $this->db->where("wl_customers.branch", $branch->id);

        return $this->get_all();
    }

    public function get_all_approved_customers($branch,$status=FALSE) {
        $this->db->select("wl_customers.*,users.username,devisions.devision");
        $this->db->join("users", "wl_customers.e_by = users.id", "LEFT");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        $this->db->where("(visibility=0 OR (visibility=1 AND wl_customers.branch=$branch->id))", NULL, FALSE);
        if($status){
            if($status=="a"){
                $this->db->where("wl_customers.approved", 1);
            }
            if($status=="u"){
                $this->db->where("wl_customers.approved", 0);
            }
        }else{
            $this->db->where("wl_customers.approved", 1);
        }
        $this->db->where("wl_customers.status", 1);
        return $this->get_all();
    }

    public function get_customer($id) {
        $this->db->select("wl_customers.*,devisions.devision");
        $this->db->join("devisions", "wl_customers.devision_id = devisions.id", "LEFT");
        return $this->get_by("wl_customers.id", $id);
    }

    public function get_customers_list($branch, $start = FALSE, $length = FALSE, $column = FALSE, $direction = FALSE, $count = FALSE, $search = FALSE, $status = FALSE) {
        $this->db->select("wl_customers.*,users.username");
        $this->db->join("users", "wl_customers.e_by = users.id", "LEFT");
        // $this->db->where('wl_customers.approved',$status?1:0);
        if ($search) {
            $this->db->group_start();
            $this->db->like("wl_customers.customer_name", $search);
            $this->db->or_like("wl_customers.address_city", $search);
            $this->db->or_like("wl_customers.address_line1", $search);
            $this->db->or_like("wl_customers.address_line2", $search);
            $this->db->or_like("wl_customers.tp1", $search);
            $this->db->or_like("wl_customers.tp2", $search);
            $this->db->or_like("wl_customers.nic", $search);
            $this->db->group_end();
        }
        if ($length) {
            $this->db->limit($length, ($start));
        }
        if ($column) {
            $this->db->order_by($column, $direction);
        }
        if ($count) {
            if ($status && $status == '1') {
                $this->db->where('wl_customers.approved', 1);
                return $this->count_by("wl_customers.branch", $branch->id);
            } else {
                return $this->count_by("wl_customers.approved", 0);
            }
        } else {
            if ($status && $status == '1') {
                $this->db->where('wl_customers.approved', 1);
                $this->db->where("wl_customers.branch", $branch->id);
            } else {
                $this->db->where('wl_customers.approved', 0);
            }
            return $this->get_all();
        }
    }

    public function save_customer($branch, $user) {
        $visibility = $this->input->post("visibility");
        $approved = $this->input->post("approved");
        $data = array(
            "customer_prefix" => $this->input->post("customer_prefix"),
            "customer_name" => $this->input->post("cus_name"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "email" => $this->input->post("email"),
            "nic" => $this->input->post("nic"),
            "location_img" => $this->input->post("path"),
            "location" => json_encode($this->input->post("location")),
            "devision_id" => $this->input->post("devision_id"),
            "custom_1" => $this->input->post("custom_1"),
            "visibility" => isset($visibility) ? 1 : 0,
            "approved" => isset($approved) && $approved == "1" ? 1 : 0,
            "branch" => $branch->id,
            "status" => 1,
            "e_by" => $user->id,
            "e_at" => date("Y-m-d H:i:s"),
        );
        return $this->insert($data);
    }

    public function update_customer($branch, $user) {
        $id = $this->input->post("id");
        $visibility = $this->input->post("visibility");
        $approved = $this->input->post("approved");
        $data = array(
            "customer_prefix" => $this->input->post("customer_prefix"),
            "customer_name" => $this->input->post("cus_name"),
            "address_po_box" => $this->input->post("address_po_box"),
            "address_line1" => $this->input->post("address_line1"),
            "address_line2" => $this->input->post("address_line2"),
            "address_city" => $this->input->post("address_city"),
            "counrty" => $this->input->post("counrty"),
            "tp1" => $this->input->post("tp1"),
            "tp2" => $this->input->post("tp2"),
            "email" => $this->input->post("email"),
            "nic" => $this->input->post("nic"),
            "location_img" => $this->input->post("path"),
            "location" => json_encode($this->input->post("location")),
            "devision_id" => $this->input->post("devision_id"),
            "custom_1" => $this->input->post("custom_1"),
            "visibility" => isset($visibility) ? 1 : 0,
            "approved" => isset($approved) && $approved == "1" ? 1 : 0,
            "branch" => $branch->id,
            "status" => $this->input->post("status"),
            // "e_by" => $user->id,
            // "e_at" => date("Y-m-d H:i:s"),
        );
        $this->update($id, $data);
    }

}
