<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists("get_option")) {

    function get_option($key, $default = FALSE,$branch_id=false)
    {
        $CI = &get_instance();
        $CI->load->model("option_model");
        $opt = $CI->option_model->get_by_key($key,$branch_id);
        if ($opt) {
            return $opt->value;
        } else {
            return $default;
        }
    }
}
if (!function_exists("save_option")) {

    function save_option($key, $_value, $branch_id = null)
    {
        if (is_array($_value)) {
            $value = json_encode($_value);
        } else {
            $value = ($_value);
        }
        $CI = &get_instance();
        $CI->load->model("option_model");
        $CI->option_model->add_option($key, $value, $branch_id);
    }

}