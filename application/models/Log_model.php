<?php

/**
 * Description of Log_model
 *
 * @author DP4
 * Aug 10, 2018 4:32:14 PM
 */
class Log_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "logs";
    }
    
}
