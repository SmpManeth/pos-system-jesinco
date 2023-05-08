<?php

/**
 * Description of register_c
 *
 * @author dilshan
 */
class Register_c extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function save_supplier() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("company_name", "Company Name", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("tp1", "Telephone Number", "trim|required");
                if ($this->form_validation->run()) {
                    $this->load->model("wl_supplier_model", "sup_model");
                    $id = $this->sup_model->save_supplier($this->branch, $this->user);
                    $this->log_login("suppier", "New Supplier added : " . $this->input->post("company_name"));
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Supplier Saved Successfully";
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Login";
        }
        echo json_encode($json);
    }

    public function update_supplier() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("company_name", "Company Name", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("tp1", "Telephone Number", "trim|required");
                if ($this->form_validation->run()) {
                    $this->load->model("wl_supplier_model", "sup_model");
                    $this->sup_model->update_supplier($this->branch, $this->user);
                    $this->log_login("suppier", "Supplier Updated : " . $this->input->post("company_name"));
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Supplier Updated Successfully";
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Login";
        }
        echo json_encode($json);
    }

    ############################################################################

    public function save_customer() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("cus_name", "Customer Name", "trim|required");
            $this->form_validation->set_rules("tp1", "Customer Telephone", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("nic", "NIC Number", "trim|required|is_unique[wl_customers.nic]");
                if ($this->form_validation->run()) {

                    if (!empty($this->input->post("location")) || $this->input->post("path")) {
                        $this->load->model("wl_customer_model");
                        $this->wl_customer_model->save_customer($this->branch, $this->user);
                        $this->log_login("customer", "New Customer added : " . $this->input->post("cus_name"));
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Customer Added Successfully";
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = 'Customer Location Not Found';
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
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

    public function update_customer() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("cus_name", "Customer Name", "trim|required");
            $this->form_validation->set_rules("tp1", "Customer Telephone", "trim|required");
            if ($this->form_validation->run()) {
                $id = $this->input->post("id");
                $this->form_validation->set_rules("nic", "NIC Number", "trim|required|callback_edit_unique[wl_customers.nic.$id]");
                if ($this->form_validation->run()) {
                    if (!empty($this->input->post("location")) || $this->input->post("path")) {
                        $this->load->model("wl_customer_model");
                        $this->wl_customer_model->update_customer($this->branch, $this->user);
                        $this->log_login("customer", "Customer Updated : " . $this->input->post("cus_name"));
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Customer Updated Successfully";
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = 'Customer Location Not Found';
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
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

    public function get_customer_list() {

        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $search = ($this->input->get("search"));

        $this->load->model("wl_customer_model");
        $customer_count = $this->wl_customer_model->get_customers_list($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE, $search, '1');
        $customers = $this->wl_customer_model->get_customers_list($this->branch, $start, $length, $column, $direction, FALSE, $search, '1');

        $dt_array = array();

        foreach ($customers as $customer) {
            $_d = array(
                "customer_prefix" => $customer->customer_prefix,
                "customer_name" => $customer->customer_name,
                "id" => $customer->id,
                "email" => $customer->email,
                "nic" => $customer->nic,
                "tp1" => $customer->tp1,
                "tp2" => $customer->tp2,
                "username" => $customer->username,
                "e_at" => $customer->e_at,
                "status" => ($customer->status),
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $customer_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function get_customer_list_un_approved() {

        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $search = ($this->input->get("search"));

        $this->load->model("wl_customer_model");
        $customer_count = $this->wl_customer_model->get_customers_list($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE, $search, '0');
        $customers = $this->wl_customer_model->get_customers_list($this->branch, $start, $length, $column, $direction, FALSE, $search, '0');

        $dt_array = array();

        foreach ($customers as $customer) {
            $_d = array(
                "customer_prefix" => $customer->customer_prefix,
                "customer_name" => $customer->customer_name,
                "id" => $customer->id,
                "email" => $customer->email,
                "nic" => $customer->nic,
                "tp1" => $customer->tp1,
                "tp2" => $customer->tp2,
                "username" => $customer->username,
                "e_at" => $customer->e_at,
                "status" => ($customer->status),
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $customer_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    public function approve() {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $id = $this->input->post("id");
            $this->load->model("wl_customer_model");
            $this->wl_customer_model->update($id, array('approved' => 1));
            $json["msg_type"] = "OK";
            $json["msg"] = "Customer Approved";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    ############################################################################

    public function save_employee() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("emp_name", "Employee Name", "trim|required");
            $this->form_validation->set_rules("emp_desig", "Employee Designation", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("p_email", "Employee Personal E-mail", "valid_email");
                $this->form_validation->set_rules("o_email", "Employee Officila E-mail", "valid_email");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("nic", "Employee NIC NUmber", "is_unique[wl_employees.nic]");
                    if ($this->form_validation->run()) {
                        $this->load->model("wl_employee_model");
                        $with_login = $this->input->post("login_data");
                        $b = TRUE;
                        if ($with_login) {
                            $this->form_validation->set_rules("username", "User Name", "trim|required|is_unique[users.username]");
                            $this->form_validation->set_rules("pass", "User Password", "trim|required|matches[c_pass]");
                            $this->form_validation->set_rules("c_pass", "Confirm Password", "trim|required");
                            if ($this->form_validation->run()) {
                                $user_name = $this->input->post("username");
                                $pass = $this->input->post("c_pass");
                                $o_email = $this->input->post("o_email");
                            } else {
                                $b = FALSE;
                                $json["msg_type"] = "ERR";
                                $json["msg"] = validation_errors();
                            }
                        }
                        if ($b) {
                            $id = $this->wl_employee_model->save_employee($this->branch, $this->user);
                            if ($with_login) {
                                $add_data = array(
                                    "first_name" => $this->input->post("emp_name"),
                                    "branch" => $this->branch->id,
                                    "phone" => $this->input->post("tp1"),
                                    "mobile" => $this->input->post("tp1"),
                                    "active" => 0,
                                    "reg_id" => $id,
                                    "user_type" => "user",
                                    "job" => $this->input->post("designation")
                                );
                                $this->ion_auth->register($user_name, $pass, $o_email, $add_data);
                                $this->log_login("employee", "New Employee added with login access : " . $this->input->post("username"));
                            } else {
                                $this->log_login("employee", "New Employee added : " . $this->input->post("username"));
                            }
                            $json["msg_type"] = "OK";
                            $json["msg"] = "Employee Added Successfully";
                        }
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = validation_errors();
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
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

    public function update_employee() {
        $json = array();
        if ($this->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("emp_name", "Employee Name", "trim|required");
            $this->form_validation->set_rules("emp_desig", "Employee Designation", "trim|required");
            if ($this->form_validation->run()) {
                $this->load->model("wl_employee_model");
                $this->load->model("user_model");
                $with_login = $this->input->post("login_data");
                $b = TRUE;
                if ($with_login) {
                    $user = $this->user_model->get_by("reg_id", $id);
                    if ($user) {
                        $this->form_validation->set_rules("username", "User Name", "trim|required|callback_edit_unique[users.username.$user->id]");
                    } else {
                        $this->form_validation->set_rules("username", "User Name", "trim|required|is_unique[users.username]");
                    }
                    $this->form_validation->set_rules("pass", "User Password", "trim|required|matches[c_pass]");
                    $this->form_validation->set_rules("c_pass", "Confirm Password", "trim|required");
                    if ($this->form_validation->run()) {
                        $user_name = $this->input->post("username");
                        $pass = $this->input->post("c_pass");
                        $o_email = $this->input->post("o_email");
                    } else {
                        $b = FALSE;
                        $json["msg_type"] = "ERR";
                        $json["msg"] = validation_errors();
                    }
                }
                if ($b) {
                    $this->wl_employee_model->update_employee($this->branch, $this->user);
                    if ($with_login) {
                        $add_data = array(
                            "username" => $user_name,
                            "password" => $pass,
                            "email" => $o_email,
                            "first_name" => $this->input->post("emp_name"),
                            "branch" => $this->branch->id,
                            "phone" => $this->input->post("tp1"),
                            "mobile" => $this->input->post("tp1"),
                            "reg_id" => $id,
                            "job" => $this->input->post("emp_desig")
                        );
                        if ($user) {
                            $this->ion_auth->update($user->id, $add_data);
                        } else {
                            $add_data = array(
                                "first_name" => $this->input->post("emp_name"),
                                "branch" => $this->branch->id,
                                "phone" => $this->input->post("tp1"),
                                "mobile" => $this->input->post("tp1"),
                                "reg_id" => $id,
                                "user_type" => "user",
                                "job" => $this->input->post("emp_desig")
                            );
                            $this->ion_auth->register($user_name, $pass, $o_email, $add_data);
                        }
                        $this->log_login("employee", "Employee updated with login access : " . $user_name);
                    } else {
                        $this->log_login("employee", "Employee updated");
                    }
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Employee Updated Successfully";
                }
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
    
    public function get_employee_list() {
        $start = intval($this->input->get("offset"));
        $length = intval($this->input->get("limit"));
        $direction = ($this->input->get("order"));
        $column = ($this->input->get("sort"));

        $search = ($this->input->get("search"));

        $this->load->model("wl_employee_model");
        $employee_count = $this->wl_employee_model->get_employee_list($this->branch, FALSE, FALSE, FALSE, FALSE, TRUE, $search, '0');
        $employees = $this->wl_employee_model->get_employee_list($this->branch, $start, $length, $column, $direction, FALSE, $search, '0');

        $dt_array = array();

        foreach ($employees as $employee) {
            $_d = array(
                "emp_prefix" => $employee->emp_prefix,
                "emp_name" => $employee->emp_name,
                "designation" => $employee->designation,
                "id" => $employee->id,
                "o_email" => $employee->o_email,
                "p_email" => $employee->p_email,
                "nic" => $employee->nic,
                "tp1" => $employee->tp1,
                "tp2" => $employee->tp2,
                "username" => $employee->username,
                "address_po_box" => $employee->address_po_box,
                "address_line1" => $employee->address_line1,
                "address_line2" => $employee->address_line2,
                "address_city" => $employee->address_city,
                "counrty" => $employee->counrty,
                "e_at" => $employee->e_at,
                "status" => ($employee->status),
            );
            $dt_array[] = $_d;
        }
        $output = array(
            "total" => $employee_count,
            "rows" => $dt_array
        );
        echo json_encode($output);
    }

    ############################################################################

    public function save_cateory() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("item_category_model");
            $this->form_validation->set_rules("cat_name", "Category Name", "trim|required|is_unique[item_categories.cat_name]");
            if ($this->form_validation->run()) {
                $id = $this->item_category_model->save_category($this->branch);
                $visibility = $this->input->post("visibility");
                $this->log_login("category", "New Category added " . ($visibility == "1" ? "(Global)" : "") . " : " . $this->input->post("username"));
                $json["msg_type"] = "OK";
                $json["msg"] = "Category Added Successfully";
                $json["id"] = $id;
                $json["la_color"] = isset($visibility) == "0" ? "info" : "warning";
                $json["la_text"] = isset($visibility) == "0" ? "Global" : "Private";
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

    public function save_sub_cateory() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("item_sub_category_model");
            $this->form_validation->set_rules("cat_id", "Main Category ", "trim|required");
            $this->form_validation->set_rules("sub_cat_name", "Category Name", "trim|required");
            if ($this->form_validation->run()) {
                $id = $this->item_sub_category_model->save_sub_category($this->branch);
                $visibility = $this->input->post("visibility");
                $this->log_login("category", "New Category added " . ($visibility == "1" ? "(Global)" : "") . " : " . $this->input->post("username"));
                $json["msg_type"] = "OK";
                $json["msg"] = "Subcategory Added Successfully";
                $json["id"] = $id;
                $json["la_color"] = isset($visibility) == "0" ? "info" : "warning";
                $json["la_text"] = isset($visibility) == "0" ? "Global" : "Private";
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

    public function remove_category() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("item_model");
            $this->load->model("item_category_model");
            $id = $this->input->post("id");
            $items = $this->item_model->get_by_category($id);
            if ($items) {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Delete Category.<br/>There are items related to this Category";
            } else {
                $this->item_category_model->delete($id);
                $json["msg_type"] = "OK";
                $json["msg"] = "Category Deleted Successfully";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function remove_sub_category() {
        $json = array();
        if ($this->logged_in()) {
            $this->load->model("item_model");
            $this->load->model("item_sub_category_model");
            $id = $this->input->post("id");
            $items = $this->item_model->get_by_sub_category($id);
            if ($items) {
                $json["msg_type"] = "ERR";
                $json["msg"] = "Can't Delete thi Sub Category.<br/>There are items related to this Sub Category";
            } else {
                $this->item_sub_category_model->delete($id);
                $json["msg_type"] = "OK";
                $json["msg"] = "Sub Category Deleted Successfully";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function get_sub_categories() {
        $json = array();
        if ($this->logged_in()) {
            $cat_id = $this->input->post("cat_id");
            if ($cat_id != "-1") {
                $this->load->model("item_sub_category_model");
                $subs = $this->item_sub_category_model->get_subs($cat_id);
                $json["subs"] = $subs;
            } else {
                $json["subs"] = array();
            }
            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    ############################################################################

    public function save_item() {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("itm_code", "Item Code", "trim|required");
            $this->form_validation->set_rules("itm_name", "Item Name", "trim|required");
            $this->form_validation->set_rules("category", "Item Category", "trim|required|callback_combo");
            $this->form_validation->set_message("combo", "Please Select a Category before Save");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("cost", "Item Cost Price", "trim|required|greater_than[0]");
                if ($this->form_validation->run()) {
                    $this->load->model("item_model");
                    $this->load->model("stock_model");
                    $id = $this->item_model->save_item($this->branch, $this->user);
                    $this->stock_model->insert(array(
                        "item_id" => $id,
                        "qty" => 0,
                        "branch_id" => $this->branch->id
                    ));
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Item Added Successfully";
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
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

    public function update_item() {
        $json = array();
        if ($this->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("itm_code", "Item Code", "trim|required");
            $this->form_validation->set_rules("itm_name", "Item Name", "trim|required");
            $this->form_validation->set_rules("category", "Item Category", "trim|required|callback_combo");
            $this->form_validation->set_message("combo", "Please Select a Category before Save");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("cost", "Item Cost Price", "trim|required|greater_than[0]");
                if ($this->form_validation->run()) {
                    $this->load->model("item_model");

                    $code = $this->input->post("itm_code");
                    $item = $this->item_model->get_iem_by_code($code, $this->branch);
                    if ($item && $item->id != $id) {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Iem Code Already in use.";
                    } else {
                        $this->item_model->update_item($this->branch, $this->user);
                        $json["msg_type"] = "OK";
                        $json["msg"] = "Item Updated Successfully";
                    }
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
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

    ############################################################################

    public function save_site() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("site_name", "Site Name", "trim|required|is_unique[wl_sites.site_name]");
            if ($this->form_validation->run()) {
                $this->load->model("wl_site_model");
                $this->wl_site_model->save_site($this->branch, $this->user);
                $json["msg_type"] = "OK";
                $json["msg"] = "Site Added Successfully";
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

    public function update_site() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("site_name", "Site Name", "trim|required|callback_edit_unique[wl_sites.site_name.$id]");
            if ($this->form_validation->run()) {
                $this->load->model("wl_site_model");
                $this->load->model("wl_site_change_model");
                $site = $this->wl_site_model->get($id);
                if ($site) {
                    $this->wl_site_model->update_site($this->branch, $this->user);
                    $superv = $this->input->post("supervisor");
                    if ($site->cur_supervisor != "-1" && $site->cur_supervisor != $superv) {
                        $this->wl_site_change_model->save_changes($id, $site->cur_supervisor);
                    }
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Site Added Successfully";
                } else {
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Nothing Found";
                }
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

    public function update_categories() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            if ($this->is_admin()) {
                $id = $this->input->post("cat_id");
                $cat_name = $this->input->post("cat_name");
                $visibility = $this->input->post("visibility");

                $this->load->model("item_category_model");

                $this->form_validation->set_rules("cat_name", "category Name", "required");
                if ($this->form_validation->run()) {
                    $data = array(
                        "cat_name" => $cat_name,
                        "visibility" => isset($visibility) ? $visibility : 0
                    );
                    $this->item_category_model->update($id, $data);
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Update Successfull.";
                } else {
                    $json["msg_type"] = "ERR";
                    $json["msg"] = validation_errors();
                }
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = "You can't perform this action.<br/>Permision denied.";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function send_message_customer() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $body = $this->input->post("msg");

            $this->load->model("wl_customer_model");
            $customer = $this->wl_customer_model->get($id);
            if (!empty($customer)) {
                $tp = !empty($customer->tp1)?$customer->tp1:$customer->tp2;
                $data = $this->send_sms_dialog($tp, $body, $id);
                $json["msg_type"] = "OK";
                $json["data"] = json_decode($data);
                $json["msg"] = "SMS Sent Successfully.";
            } else {
                $json["msg_type"] = "OK";
                $json["msg"] = "Employee Not found";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
