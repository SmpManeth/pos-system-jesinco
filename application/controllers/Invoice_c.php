<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Invoice_c
 *
 * @author dilsh
 */
class Invoice_c extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        echo 'none of your business';
    }

    public function add_to_return() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("repair_model");
            $this->load->model("invoice_item_model");
            $inv_item = $this->invoice_item_model->get($id);


            $this->repair_model->insert(array(
                "inv_id" => $inv_item->inv_id,
                "inv_item_id" => $id,
                "item_id" => $inv_item->itm_id,
                "status" => 0,
                "created_by" => $this->user->id,
                "created_date" => date("Y-m-d")
            ));

            $json["msg_type"] = "OK";
            $json["msg"] = "Return Item Added Successfully.";
            $json["url"] = base_url("repairs/repair-list/");
            ;
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function load_c24_form() {
        $id = $this->input->post("inv_id");
        $this->load->model("invoice_model");
        $invoice = $this->invoice_model->get($id);
        $this->load->view('invoice/c24_form', array("invoice" => $invoice));
    }

    public function add_c24_remark() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("inv_id");
            $c24_remarks = $this->input->post("c24_remarks");
            $this->load->model("invoice_model");
            $this->invoice_model->update($id, array("c24_remarks" => $c24_remarks,"c24_date"=> date("Y-m-d H:i:s")));
            $json["msg_type"] = "OK";
            $json["msg"] = "Remark added Successfully";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
