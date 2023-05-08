<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Settings_model
 *
 * @author dilsh
 */
class Option_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "options";
    }

    public function get_by_key($key, $branch_id = false)
    {
        if($branch_id){
            $this->db->where('branch_id',$branch_id);
        }
        return $this->get_by('option', $key);
    }

    public function get_by_branch($branch_id)
    {
        $this->db->where('branch_id', $branch_id);
        return $this->get_all();
    }

    public function add_option($key, $value, $branch_id = null)
    {
        $option = $this->get_by_key($key,$branch_id);
        if (empty($option)) {
            $this->insert(array("option" => $key, "value" => $value, 'branch_id' => $branch_id));
        } else {
            $this->update($option->id, array("option" => $key, "value" => $value));
        }
    }

    public function filter_options($filter, $side = 'both')
    {
        $this->db->like("option", $filter, $side);
        $opts = $this->get_all();
        $arr = [];
        foreach ($opts as $opt) {
            $arr[$opt->option] = $opt->value;
        }
        return $arr;
    }
}
