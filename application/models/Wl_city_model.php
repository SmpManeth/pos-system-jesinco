<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Wl_city_model
 *
 * @author dilsh
 */
class Wl_city_model extends MY_Model{
    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_cities";
    }
    public function get_all_cities($start=FALSE, $length=FALSE, $column=FALSE, $search=FALSE, $direction=FALSE,$count=FALSE) {
        $this->db->select("wl_cities.*");
        
        $this->db->like("wl_cities.city", $search);
        if ($count) {
            return $this->count_all();
        } else {
            if ($length) {
                $this->db->limit($length, $start);
            }
            if ($column) {
                $this->db->order_by($column, $direction);
            } else {
                $this->db->order_by("city", "ASC");
            }
            return $this->get_all();
        }
    }
}
