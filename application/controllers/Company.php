<?php

/**
 * Description of company
 *
 * @author dilshan
 */
class Company extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function home() {
        if ($this->ion_auth->logged_in()) {
            if ($this->logged_in()) {
                $this->data["head"] = "Dashboard";

                $this->load->model("invoice_model", "iim");
                $this->load->model("gr_note_model", "grm");
                $this->load->model("p_order_model", "pom");


                $date = date("Y-m-d");
                $_invoices = $this->iim->get_today($this->branch, $date);
                $_grns = $this->grm->get_today($this->branch, $date);
                $_pos = $this->pom->get_today($this->branch, $date);

                $invoices = 0;
                $grns = 0;
                $gins = 0;
                $pos = 0;
                $inbound = 0;

                foreach ($_invoices as $_inv) {
                    $invoices += doubleval($_inv->total);
                }
                foreach ($_grns as $_grn) {
                    $grns += doubleval($_grn->total);
                }


                $this->data["po"] = array($pos, count($_pos));
                $this->data["grn"] = array($grns, count($_grns));
                $this->data["invoice"] = array($invoices, count($_invoices));

                $this->load_view(array("welcome_message"));
            } else {
                redirect(site_url("company_select"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function view_company() {
        if ($this->logged_in()) {
            $this->data["head"] = "Company Information";
            $this->load_view(array("admin/company/view"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function company_select() {
        if ($this->ion_auth->logged_in()) {
            $branch_id = $this->session->userdata("branch");
            if ($branch_id) {
                redirect("home");
            } else {
                $this->load->model("branch_model");
                $usr = $this->ion_auth->user()->row();
                $branches = $this->branch_model->get_branches($usr);
                $this->data["branches"] = $branches;
                $this->load_login(array("users/tp_company"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function change_company() {
        if ($this->ion_auth->logged_in()) {
            $this->session->unset_userdata("branch");
            redirect("company_select");
        } else {
            redirect(base_url("login"));
        }
    }

    public function proceed() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("company", "Company Name", "trim|required|callback_combo");
            $this->form_validation->set_message("combo", "Please select a Company to Proceed");
            if ($this->form_validation->run()) {
                $this->session->set_userdata(array("branch" => $this->input->post("company")));
                $json["msg_type"] = "OK";
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

    public function update_company() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("company_name", "Company Name", "trim|required");
            if ($this->form_validation->run()) {
                $this->load->model("company_model");
                $this->company_model->upate_company($_POST);
                $json["msg_type"] = "OK";
                $json["msg"] = "Updated Successfully";
            } else {
                $json["msg_type"] = "ERE";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function save_branch() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("company_name", "Company Name", "trim|required|is_unique[branches.branch_name]");
            if ($this->form_validation->run()) {
                $this->load->model("branch_model");
                $this->branch_model->save_branch(1, $this->user);
                $json["msg_type"] = "OK";
                $json["msg"] = "Banch Added Successfully";
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

    public function update_branch() {
        $json = array();
        if ($this->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("company_name", "Company Name", "trim|required|callback_edit_unique[branches.branch_name.$id]");
            if ($this->form_validation->run()) {
                $this->load->model("branch_model");
                $this->branch_model->update_branch(1, $this->user);
                $json["msg_type"] = "OK";
                $json["msg"] = "Banch updated Successfully";
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

}
