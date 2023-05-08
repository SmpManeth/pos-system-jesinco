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
class Cron extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo "Nothing here\n<br/>";
	log_message('debug', 'USER_INFO: ') ;

    }

    public function index2(){

        echo "Nothing here";
}
    public function due_bills()
    {

	log_message('debug', 'CRON Due bills START') ;
        $this->load->model("invoice_model");
        $due_sms_template = get_option('due_sms_template', 'Before the last {count} installments, If you pay total amount to be paid on the day in installment at once
You can get a {discount}% discount. Conditional.');
        $due_sms_discount_percentage = get_option('due_sms_discount_percentage', '0');
        $due_sms_installment_count = get_option('due_sms_installment_count', '2');
        $due_sms_enabled = get_option('due_sms_enabled', 'no');

        if ($due_sms_enabled == 'yes') {
		log_message('debug', 'CRON Due bills ENABLED: yes') ;
            $payments_list = $this->invoice_model->get_due_bills($due_sms_installment_count);

            $template = str_replace('{discount}', $due_sms_discount_percentage, $due_sms_template);
            $template = str_replace('{count}', $due_sms_installment_count, $template);

            $numbers = ['0761299593','0710479009'];
            foreach ($payments_list as $p) {
                if (intval($p->installment_count) > 2) {
                    $numbers[] = $p->tp1;
                }
            }
		log_message('debug', 'CRON Due bills numbers: '.json_encode($numbers)) ;
            $stat = $this->send_sms_dialog_bulk(implode(',', $numbers), $template, 'reminder');
//log_message('debug', 'CRON Due bills numbers: '.json_encode($numbers)) ;
		log_message('debug', 'CRON Due bills numbers: '.json_encode($stat)) ;
            $date = date('Y-m-d H:i:s');
            file_put_contents('/var/www/html/pos-system/log_cron.txt', $date . '-' . json_encode($numbers) . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('/var/www/html/pos-system/log_cron.txt', $date . '-' . json_encode($stat) . PHP_EOL, FILE_APPEND | LOCK_EX);
        }

    }
}
