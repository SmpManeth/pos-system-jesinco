<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cities
 *
 * @author dilsh
 */
class City extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->all();
    }

    public function all() {
        $this->load_view(array("city/all"));
    }

    public function new_city() {
        
    }

    public function edit_city() {
        if ($this->ion_auth->logged_in()) {
            $id = $this->uri->segment(3);
            $this->load->model("wl_city_model");
            $city = $this->wl_city_model->get($id);
            $this->data["city"] =$city;
            $this->load_view(array("city/edit"));
        } else {
            redirect("login");
        }
    }

    public function get_city_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));
        $search = ($this->input->get("search"));

        $this->load->model("wl_city_model");
        $city_count = $this->wl_city_model->get_all_cities(FALSE, FALSE, FALSE, FALSE, $search, TRUE);
        $cities = $this->wl_city_model->get_all_cities($start, $length, $column, $search, $direction);

        $dt_array = $cities;
        $output = array(
            "total" => $city_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

}
