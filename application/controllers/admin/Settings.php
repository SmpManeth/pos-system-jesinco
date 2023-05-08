<?php

/**
 * Description of Settings
 *
 * @author DP4
 * Jun 26, 2018 12:16:50 PM
 */
class Settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data["head"] = "Settings";
            $this->load->model("wl_doc_code_model");
            $this->load->model("item_category_model");
            $this->load->model("installment_model");
            $this->load->model("devision_model");
            $this->load->model("fine_model");
            $categories = $this->item_category_model->get_categories($this->branch);
            $devisoins = $this->devision_model->get_devisions($this->branch);
            $this->data["categories"] = $categories;
            $this->data["devisions"] = $devisoins;
            $_prefixes = $this->wl_doc_code_model->get_prefixes($this->branch->id);
            $installments = $this->installment_model->get_all();
            $prefixes = array();
            if (count($_prefixes) > 0) {
                foreach ($_prefixes as $_pref) {
                    $prefixes[$_pref->doc] = $_pref;
                }
            }
            $this->data["installments"] = $installments;
            $this->data["prefixes"] = $prefixes;


            $this->load->model("option_model", "om");
            $_options = $this->om->get_by_branch($this->branch->id);
            if (count($_options) == 0) {
                $_options = $this->om->get_all();
            }
            $options = [];
            foreach ($_options as $value) {
                $options[$value->option] = $value->value;
            }
            $this->data["options"] = $options;

            $fines = $this->fine_model->get_all_fines();
            $this->data["fines"] = $fines;

            $this->load_view(array("admin/settings"));
        } else {
            redirect(base_url("login"));
        }
    }

    #############################################################################
    #                                                                           #
    #                                                                           #
    #                           AJAX CALLS                                      #
    #                                                                           #
    #                                                                           #
    #############################################################################

    public function save_prefixes()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("wl_doc_code_model");

            $data = $this->input->post(NULL, TRUE);
            foreach ($data as $key => $val) {
                if ($key != 'is_ajax_request') {
                    $this->wl_doc_code_model->save_prefixes($key, strtoupper($val), $this->branch);
                }
            }
            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function save_installment()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $installment = $this->input->post("installment");
            $this->load->model("installment_model");
            $this->form_validation->set_rules("installment", "Installment Month", "trim|required|is_unique[installments.month]");
            if ($this->form_validation->run()) {
                $this->installment_model->insert(array("month" => $installment));
                $json["msg_type"] = "OK";
                $json["msg"] = "Installment Month added successfully.";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function update_installment()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $installment = $this->input->post("installment");
            $this->load->model("installment_model");
            $id = $this->input->post("id");
            $this->form_validation->set_rules("installment", "Installment Month", "trim|required|callback_edit_unique[installments.month.$id]");
            if ($this->form_validation->run()) {
                $this->installment_model->update($id, array("month" => $installment));
                $json["msg_type"] = "OK";
                $json["msg"] = "Installment Month updated successfully.";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function update_staus_installment()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("installment_model");
            $status = $this->input->post("status");
            $id = $this->input->post("id");
            $this->installment_model->update($id, array("status" => $status));
            $json["msg_type"] = "OK";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function load_installments()
    {
        $this->load->model("installment_model");
        $installments = $this->installment_model->get_all();
        $this->load->view("admin/components/installments", array("installments" => $installments));
    }

    public function save_options()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $token_key = $this->security->get_csrf_token_name();
            $options = $this->input->post();
            unset($options["is_ajax_request"]);
            unset($options[$token_key]);

            foreach ($options as $key => $value) {
                save_option($key, $value,$this->branch->id);
            }

            $json["msg_type"] = "OK";
            $json["msg"] = "Setting Saved Successfully.";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function load_fines()
    {
        $this->load->model("fine_model");
        $fines = $this->fine_model->get_all_fines();
        $this->load->view("admin/components/fine_conditions", array("fines" => $fines));
    }

    public function save_fines()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {

            $this->form_validation->set_rules("day", "Day", "trim|required|greater_than[0]|is_unique[fines.day]");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("fine_amount", "Fine Amount", "trim|required|greater_than[0]");
                if ($this->form_validation->run()) {
                    $this->load->model("fine_model");
                    $this->fine_model->save_fine();
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Fine Condition added Successfully";
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
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function update_fines()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("day", "Day", "trim|required|greater_than[0]|callback_edit_unique[fines.day.$id]");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("fine_amount", "Fine Amount", "trim|required|greater_than[0]");
                if ($this->form_validation->run()) {
                    $this->load->model("fine_model");
                    $this->fine_model->update_fine($id);
                    $json["msg_type"] = "OK";
                    $json["msg"] = "Fine Condition added Successfully";
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
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function delete_fine()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->load->model("fine_model");
            $this->fine_model->delete($id);

            $json["msg_type"] = "OK";
            $json["msg"] = "Fine Condition Deleted Successfully";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function save_devision()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("devision", "Devision Name", "required");
            if ($this->form_validation->run()) {

                $this->load->model("devision_model");
                $id = $this->devision_model->insert(array(
                    "devision" => $this->input->post("devision"),
                    "branch_id" => $this->branch->id,
                ));

                $json["msg_type"] = "OK";
                $json["id"] = $id;
                $json["msg"] = "Devision Saved Successfully";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function update_devision()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("devision", "Devision Name", "required");
            if ($this->form_validation->run()) {

                $this->load->model("devision_model");
                $this->devision_model->update($id, array(
                    "devision" => $this->input->post("devision"),
                    "branch_id" => $this->branch->id,
                ));

                $json["msg_type"] = "OK";
                $json["msg"] = "Devision Saved Successfully";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

}
