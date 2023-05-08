<?php

/**
 * Description of register
 *
 * @author dilshan
 */
class Register extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect("home");
    }

    public function employee() {
        if ($this->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("wl_employee_model");
            $this->load->model("common_model");
            if ($uri_segment == "" || $uri_segment == "all") {
//                $employees = $this->wl_employee_model->get_all_emps($this->branch);
//                $this->data["employees"] = $employees;
                $this->data["head"] = "Employee List";
                $this->data["breadcrums"] = array(array("home", "home"), ("Employee"));
                $this->load_view(array("register/employee/all_new"));
            }
            if ($uri_segment == "new") {
                $countries = $this->common_model->get_countries();
                $this->data["countries"] = $countries;
                $this->data["head"] = "New Employee";
                $this->data["breadcrums"] = array(array("home", "home"), array("register/employee", "Employee"), ("new"));
                $this->load_view(array("register/employee/new"));
            }
            if ($uri_segment == "edit") {
                $s_id = $this->uri->segment(4);
                $employee = $this->wl_employee_model->get_employee($s_id);
                if ($employee) {
                    $countries = $this->common_model->get_countries();
                    $this->data["employee"] = $employee;
                    $this->data["head"] = "Edit Employee";
                    $this->data["countries"] = $countries;
                    $this->data["breadcrums"] = array(array("home", "home"), array("register/Employee", "Employee"), ("Edit"));
                    $this->load_view(array("register/employee/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function customer() {
        if ($this->ion_auth->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("wl_customer_model");
            $this->load->model("common_model");
            if ($uri_segment == "" || $uri_segment == "all") {
                $this->data["head"] = "Customer List";
                $this->data["breadcrums"] = array(array("home", "home"), ("customer"));
                $this->load_view(array("register/customer/all_new"));
            }
            if ($uri_segment == "un-approved") {
                $this->data["head"] = "Customer List";
                $this->data["breadcrums"] = array(array("home", "home"), ("customer"));
                $this->load_view(array("register/customer/all_unapproved"));
            }
            $this->data["cities"] = $this->common_model->get_cities();
            if ($uri_segment == "new") {
                $this->load->model("devision_model");
                $devisions = $this->devision_model->get_devisions($this->branch);
                $countries = $this->common_model->get_countries();
                $this->data["countries"] = $countries;
                $this->data["devisions"] = $devisions;
                $this->data["head"] = "New Customer";
                $this->data["breadcrums"] = array(array("home", "home"), array("register/customer", "customer"), ("new"));
                $this->load_view(array("register/customer/new"));
            }
            if ($uri_segment == "edit") {
                $s_id = $this->uri->segment(4);
                $customer = $this->wl_customer_model->get($s_id);
                if ($customer) {
                    $this->load->model("devision_model");
                    $devisions = $this->devision_model->get_devisions($this->branch);
                    $countries = $this->common_model->get_countries();
                    $this->data["countries"] = $countries;
                    $this->data["devisions"] = $devisions;
                    $this->data["head"] = "Edit Customer";
                    $this->data["customer"] = $customer;
                    $this->data["breadcrums"] = array(array("home", "home"), array("register/customer", "customer"), ("Edit"));
                    $this->load_view(array("register/customer/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
            if ($uri_segment == "send-sms-all") {
                $customers = $this->wl_customer_model->get_all_approved_customers($this->branch);
                $this->data["head"] = "Send SMS to Customers";
                $this->data["customers"] = $customers;
                $this->data["breadcrums"] = array(array("home", "home"), array("register/customer", "customer"),("Send SMS"));
                $this->load_view(array("register/customer/send-sms-all"));
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function supplier() {
        if ($this->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("wl_supplier_model");
            $this->load->model("common_model");
            if ($uri_segment == "" || $uri_segment == "all") {
                $suppliers = $this->wl_supplier_model->get_all_branch($this->branch);
                $this->data["head"] = "Supplier List";
                $this->data["suppliers"] = $suppliers;
                $this->data["breadcrums"] = array(array("home", "home"), ("supplier"));
                $this->load_view(array("register/supplier/all"));
            }
            if ($uri_segment == "new") {
                $countries = $this->common_model->get_countries();
                $this->data["countries"] = $countries;
                $this->data["head"] = "New Supplier";
                $this->data["breadcrums"] = array(array("home", "home"), array("register/supplier", "supplier"), ("new"));
                $this->load_view(array("register/supplier/new"));
            }
            if ($uri_segment == "edit") {
                $s_id = $this->uri->segment(4);
                $suppler = $this->wl_supplier_model->get($s_id);
                if ($suppler) {
                    $countries = $this->common_model->get_countries();
                    $this->data["supplier"] = $suppler;
                    $this->data["head"] = "Edit Supplier";
                    $this->data["countries"] = $countries;
                    $this->data["breadcrums"] = array(array("home", "home"), array("register/supplier", "supplier"), ("Edit"));
                    $this->load_view(array("register/supplier/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function item() {
        if ($this->logged_in()) {
            $uri_segment = $this->uri->segment(3);
            $this->load->model("item_model");
            $this->load->model("item_category_model");
            if ($uri_segment == "" || $uri_segment == "all") {
                $this->data["head"] = "Item List";
//                $this->data["items"] = $items;
                $this->data["breadcrums"] = array(array("home", "home"), ("Items"));
                $this->load_view(array("register/item/all"));
            }
            if ($uri_segment == "new") {
                $categories = $this->item_category_model->get_categories($this->branch);
                $this->data["head"] = "New Item";
                $this->data["categories"] = $categories;
                $this->data["breadcrums"] = array(array("home", "home"), array("register/item", "items"), ("new"));
                $this->load_view(array("register/item/new"));
            }
            if ($uri_segment == "history") {
                $i_id = $this->uri->segment(4);
                $this->item_history($i_id);
            }
            if ($uri_segment == "edit") {
                $i_id = $this->uri->segment(4);
                $item = $this->item_model->get($i_id);
                if ($item) {
                    $this->load->model("item_sub_category_model");
                    $categories = $this->item_category_model->get_categories($this->branch);
                    $subs = $this->item_sub_category_model->get_subs($item->itm_cat);
                    $this->data["item"] = $item;
                    $this->data["head"] = "Edit Item";
                    $this->data["categories"] = $categories;
                    $this->data["subs"] = $subs;
                    $this->data["breadcrums"] = array(array("home", "home"), array("register/item", "items"), ("Edit"));
                    $this->load_view(array("register/item/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

    private function item_history($id) {
        $this->data["head"] = "Item History";
        $this->data["breadcrums"] = array(array("home", "home"), array("register/item", "items"), ("History"));

        $this->load->model("grn_item_model");
        $this->load->model("gi_item_model");
        $this->load->model("invoice_item_model");
        $this->load->model("common_model");

        $grn_items = $this->grn_item_model->get_history($id, $this->branch);


        $this->load_view(array("register/item/new"));
    }

    public function load_categories() {
        if ($this->logged_in()) {
            $this->load->model("item_category_model");
            $cats = $this->item_category_model->get_categories($this->branch);
            $this->load->view("register/other/categories", array("cats" => $cats));
        } else {
            $this->load->view("login_expired");
        }
    }

    public function load_sub_categories() {
        if ($this->logged_in()) {
            $this->load->model("item_category_model");
            $this->load->model("item_sub_category_model");
            $cat_id = $this->input->post("cat");
            $cat = $this->item_category_model->get($cat_id);
            $cats = $this->item_category_model->get_categories($this->branch);
            $sub_cats = $this->item_sub_category_model->get_sub_categories($this->branch, $cat->id);
            $this->load->view("register/other/sub_categories", array("cats" => $cats, "sub_categories" => $sub_cats, "from" => "item", "cat" => $cat));
        } else {
            $this->load->view("login_expired");
        }
    }

    public function site() {
        if ($this->ion_auth->logged_in()) {
            $this->load->model("wl_site_model");
            $this->load->model("wl_employee_model");
            $uri_segment = strtolower($this->uri->segment(3));
            if ($uri_segment == "" || $uri_segment == "all") {
                $sites = $this->wl_site_model->get_sites($this->branch);
                $this->data["head"] = "Site Management";
                $this->data["sites"] = $sites;
                $this->data["breadcrums"] = array(array("home", "home"), ("new"));
                $this->load_view(array("register/site/all"));
            }
            if ($uri_segment == "new") {
                $employees = $this->wl_employee_model->get_all_emps($this->branch);
                $this->data["head"] = "Site Management";
                $this->data["employees"] = $employees;
                $this->data["breadcrums"] = array(array("home", "home"), array("register/site", "Site"), ("new"));
                $this->load_view(array("register/site/new"));
            }
            if ($uri_segment == "edit") {
                $employees = $this->wl_employee_model->get_all_emps($this->branch);
                $id = strtolower($this->uri->segment(4));
                $site = $this->wl_site_model->get($id);
                if ($site) {
                    $this->load->model("wl_site_change_model");
                    $site_changes = $this->wl_site_change_model->get_changes($id);
                    $this->data["head"] = "Site Management";
                    $this->data["employees"] = $employees;
                    $this->data["changes"] = $site_changes;
                    $this->data["site"] = $site;
                    $this->data["breadcrums"] = array(array("home", "home"), array("register/site", "Site"), ("Edit"));
                    $this->load_view(array("register/site/edit"));
                } else {
                    $this->load_view(array("nothing"));
                }
            }
        } else {
            redirect(site_url("login"));
        }
    }

    public function get_item_list() {
        $output = array();
        if ($this->ion_auth->logged_in()) {
            $start = intval($this->input->get("offset"));
            $length = intval($this->input->get("limit"));
            $direction = ($this->input->get("order"));
            $column = ($this->input->get("sort"));
            $search = ($this->input->get("search"));

            $this->load->model("item_model");
            $items_all = $this->item_model->get_all_items($this->branch);
            $items = $this->item_model->get_all_items($this->branch, $start, $length, $column, $direction, $search);
            $output = array(
                "total" => count($items_all),
                "rows" => $items
            );
        } else {
            $output = array(
                "total" => 0,
                "rows" => array()
            );
        }
        echo json_encode($output);
    }

}
