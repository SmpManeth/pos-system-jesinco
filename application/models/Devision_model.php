<?php 
class Devision_model extends MY_Model{

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "devisions";
    }

    public function get_devisions($branch)
    {
        $this->db->where("branch_id",$branch->id);
        return $this->get_all();
    }
    public function get_all_devisions()
    {
        $this->db->select("devisions.*,branches.branch_name");
        $this->db->join("branches","devisions.branch_id =branches.id","LEFT");
        return $this->get_all();
    }

}