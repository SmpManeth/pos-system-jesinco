<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  DOMPDF
 * 
 * Author: Dilshan Jaysanka 
 * 	 	  dilshan.jayasanka88@gmail.com
 *           
 * Created:  2016-06-02
 * 
 * Description:  This is a Codeigniter library which allows you to print report with jasper/ iReport  
 * 
 */
class Jasper {

    public function __construct() {
        include_once("phpjasperxml/class/PHPJasperXML.php");
        include_once('phpjasperxml/class/tcpdf/tcpdf.php');
    }

    public function print_report_native($path, $data_array, $out_type = "I") {

        $xml = simplexml_load_file(site_url($path));
        $PHPJasperXML = new PHPJasperXML();
        if (count($data_array) > 0) {
            $PHPJasperXML->arrayParameter = $data_array;
        }
        $PHPJasperXML->xml_dismantle($xml);
        $PHPJasperXML->transferDBtoArray("localhost", "root", "1234", "payroll");
        $PHPJasperXML->outpage($out_type);
        $PHPJasperXML->test();
    }

}