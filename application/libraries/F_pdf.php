<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tcpdf
 *
 * @author Dilshan Jayasanka
 */
require_once APPPATH . 'third_party/fpdf/My_pdf.php';

class F_pdf extends My_pdf {

    public function __construct($config = array()) {
        $orientation = 'P';
        $unit = 'mm';
        $format = 'A4';
        if (isset($config)) {
            if (isset($config["orientation"])) {
                $orientation = $config["orientation"];
            }
            if (isset($config["unit"])) {
                $unit = $config["unit"];
            }
            if (isset($config["format"])) {
                $format = $config["format"];
            }
        }
        $pdf = new My_pdf($orientation, $unit, $format);
//
        $CI = get_instance();
        $CI->tcpdf = $pdf;
    }

}

?>
