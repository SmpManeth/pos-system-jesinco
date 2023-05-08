<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            redirect("home");
        } else {
            redirect("login");
        }
    }

    public function not_found() {
        if ($this->ion_auth->logged_in()) {
            $this->load_view(array("nothing"));
        } else {
            $this->load->view("nothing");
        }
    }
    
    public function sendSmsToCustomers() {
        
    }

}
