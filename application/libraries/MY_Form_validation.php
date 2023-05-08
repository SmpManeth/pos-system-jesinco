<?php
class MY_Form_validation extends CI_Form_validation{
    public function __construct($rules = array()) {
        parent::__construct($rules);
    }
    public function sanitize($string) {
        return textile_sanitize($string);
    }
}