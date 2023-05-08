<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fine_model
 *
 * @author dilsh
 */
class Fine_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "fines";
    }

    public function save_fine() {
        $day = $this->input->post("day");
        $fine_amount = $this->input->post("fine_amount");

        return $this->insert(array("day" => $day, "fine" => $fine_amount));
    }

    public function update_fine($id) {
        $day = $this->input->post("day");
        $fine_amount = $this->input->post("fine_amount");

        return $this->update($id, array("day" => $day, "fine" => $fine_amount));
    }

    public function get_all_fines() {
        $this->order_by("day");
        return $this->get_all();
    }

}
