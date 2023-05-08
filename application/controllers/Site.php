<?php

/**
 * Description of site
 *
 * @author dilshan
 */
class Site extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function budget() {
        if ($this->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("wl_site_model");
            if ($uri_segment == "" || $uri_segment == "all") {
                $site_budgets = $this->wl_site_model->get_budgets();
                $this->data["head"] = "Site & Budget";
                $this->data["sites"] = $site_budgets;
                $this->data["breadcrums"] = array(array("home", "home"), ("Site & Budgets"));
                $this->load_view(array("register/site/budget"));
            }
            if ($uri_segment == "update") {
                $site_id = $this->uri->segment(4);
                $site = $this->wl_site_model->get_site($site_id);
                $this->data["head"] = "Update Site Budget";
                $this->data["breadcrums"] = array(array("home", "home"), array("site/budget", "Site & Budgets"), ("Update"));
                if ($site) {
                    $this->load->model("wl_site_budget_model");
                    $s_budget = $this->wl_site_budget_model->get_budget($site_id, date("Y"));
                    $this->data["site"] = $site;
                    if ($s_budget) {
                        $this->data["amount"] = $s_budget->amount;
                    } else {
                        $this->data["amount"] = 0;
                    }
                    $this->load_view(array("register/site/update_budget"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function update_site_budget() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("amount", "Budget Amount", "trim|required|greater_than[0]");
            if ($this->form_validation->run()) {
                $site_id = $this->input->post("id");
                $this->load->model('wl_site_budget_model');
                $this->wl_site_budget_model->update_budget($this->user, date("Y"));
                $json["msg_type"] = "OK";
                $json["msg"] = "Site Budget Updated Successfully";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }
    
    public function equipments() {
        if ($this->ion_auth->logged_in()) {
            $site_id=$this->uri->segment(3);
            $this->load->model("wl_site_model");
            $site = $this->wl_site_model->get_site($site_id);
        } else {
            redirect(site_url("login"));
        }
    }

}
