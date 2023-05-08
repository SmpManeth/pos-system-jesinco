<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Installment_model
 *
 * @author dilsh
 */
class Installment_model extends MY_Model{
    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "installments";
    }
    
    public function get_installments() {
        $this->db->where("status",1);
        return $this->get_all();
    }
}
