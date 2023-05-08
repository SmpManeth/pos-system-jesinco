<?php

/**
 * Description of wl-admin
 *
 * @author dilshan
 */
class Wl_admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("common_model");
    }

    public function company() {
        if ($this->logged_in()) {
            $this->data["head"] = "Company Information";
            $countries = $this->common_model->get_countries();
            $this->data["countries"] = $countries;
            $this->data["breadcrums"] = array(array("home", "home"), ("Company Information"));
            $this->load_view(array("admin/company/company"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function branches() {
        if ($this->ion_auth->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("branch_model");
            if ($uri_segment == "" || $uri_segment == "all") {
                $branches = $this->branch_model->get_branches_all();
                $this->data["branches"] = $branches;
                $this->load_view(array("admin/company/branch/all"));
            }
            if ($uri_segment == "new") {
                $countries = $this->common_model->get_countries();
                $this->data["countries"] = $countries;
                $this->load_view(array("admin/company/branch/new"));
            }
            if ($uri_segment == "edit") {
                $br_id = $this->uri->segment(4);
                $branch = $this->branch_model->get($br_id);
                if ($branch) {
                    $countries = $this->common_model->get_countries();
                    $this->data["countries"] = $countries;
                    $this->data["branch"] = $branch;
                    $this->load_view(array("admin/company/branch/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

}
