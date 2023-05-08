<?php

/**
 * Description of Logs
 *
 * @author DP4
 * Sep 17, 2018 3:45:32 PM
 */
class Logs extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            
            $this->load->model("common_model");
            $logs = $this->common_model->get_Logs_days(4,  $this->branch);
            $this->data["logs"] = $logs;
            $this->load_view(array("admin/logs"));
        } else {
            redirect(base_url("login"));
        }
    }
    public function change_location() {
        if ($this->ion_auth->logged_in()) {

            $this->load->model("wl_customer_model","wcm");
            $customers = $this->wcm->get_all();
            $this->db->trans_start();
            try{
                $count =0;
                foreach ($customers as $customer) {
                    if($customer->location){
                        $location = json_decode($customer->location);
                        $data = array(
                            "location"=>json_encode(array('lat'=>$location->long,'long'=>$location->lat))
                        );
                        $this->wcm->update($customer->id,$data);
                        $count++;
                    }       
                }
                $this->db->trans_complete();
                echo $count." Updated";
            } catch (Exception $exc) {
                $this->db->trans_rollback();
                echo $exc->getTraceAsString();
            }
        } else {
            redirect(base_url("login"));
        }
    }

}
