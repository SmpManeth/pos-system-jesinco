<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users
 *
 * @author Rish
 */
class Users extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect("home");
    }

    public function loginform()
    {
        if ($this->ion_auth->logged_in()) {
            redirect(site_url("home"));
        } else {
            $this->load_login(array("users/tp_login"));
        }
    }

    public function login()
    {
        $json = array();
        if ($this->ion_auth->logged_in() == TRUE) {
            redirect(site_url());
        }
        // Validate the form
        $this->form_validation->set_rules('login_form_name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('login_form_password', 'Password', 'trim|required');
        if ($this->form_validation->run() == true) {
            $remember_me = FALSE;
            if (($this->input->post("remember"))) {
                $remember_me = TRUE;
            }
            if ($this->ion_auth->login($this->input->post('login_form_name'), $this->input->post('login_form_password'), $remember_me) == TRUE) {
                $usr = $this->ion_auth->user()->row();
                $json['msg_type'] = 'OK';
                $branches = $usr->branches;
                $branch_list = json_decode($branches);
//                if (count($branch_list) > 1 || $branches == "all") {
                $json['url'] = site_url("company_select");
//                } else {
//                    $this->load->library("session");
//                    $this->session->set_userdata(array("branch" => $usr->branch));
//                    $json['url'] = site_url("home");
//                }
            } else {
                $json['msg_type'] = 'ERR';
                $json['msg'] = 'We Can\'t Log You in. Try Again';
            }
        } else {
            $json['msg_type'] = 'ERR';
            $json['msg'] = 'We Can\'t Log You in. Try Again';
        }
        echo json_encode($json);
    }

    public function login_inner()
    {
        $json = array();
        if ($this->ion_auth->logged_in() == TRUE) {
            redirect(site_url());
        }
        // Validate the form
        $this->form_validation->set_rules('login_form_name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('login_form_password', 'Password', 'trim|required');
//        $this->form_validation->set_rules('w_company', '', 'trim|required|callback_combo');
//        $this->form_validation->set_message("combo", "Select Working Company");
        if ($this->form_validation->run() == true) {
            $remember_me = FALSE;
            if (($this->input->post("remember"))) {
                $remember_me = TRUE;
            }
            if ($this->ion_auth->login($this->input->post('login_form_name'), $this->input->post('login_form_password'), $remember_me) == TRUE) {
                $usr = $this->ion_auth->user()->row();
//                $this->load->library('session');
//                $this->session->set_userdata(array("company_id" => $this->input->post("w_company")));
                $json['msg_type'] = 'OK';
            } else {
                $json['msg_type'] = 'ERR';
                $json['msg'] = 'We Can\'t Log You in. Try Again pw';
            }
        } else {
            $json['msg_type'] = 'ERR';
            $json['msg'] = validation_errors();
        }
        echo json_encode($json);
    }

    public function logout()
    {
        $this->load->library("session");
        $this->session->sess_destroy();
        $this->ion_auth->logout();
        redirect(site_url());
    }

    public function fetch_login()
    {
        $json = array();
        $json["html"] = $this->load->view("users/login2", array(), TRUE);
        echo json_encode($json);
    }

    public function settings()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data["user"] = $this->user;
            $this->load_view(array("users/c_pass"));
        } else {
            redirect("login");
        }
    }

    public function my_profile()
    {
        if ($this->logged_in()) {
            $this->load_view(array("users/profile"));
        } else {
            redirect(site_url("login"));
        }
    }

    public function update_profile()
    {
        $json = array();
        if ($this->logged_in()) {
            $this->form_validation->set_rules("f_name", "First Name", "trim|required");
            $this->form_validation->set_rules("l_name", "Last Name", "trim|required");
            if ($this->form_validation->run()) {
                $this->load->model("user_model");
                $this->user_model->update_profile($this->user);
                $json["msg_type"] = "OK";
                $json["msg"] = "Profile Updated Successfully";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "ERR";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function save_profile_avatar()
    {
        $json = array();
        if ($this->logged_in()) {
            $img_path = $this->input->post("img_path");
            $this->load->model("user_model");
            $this->user_model->update($this->user->id, array("avatar" => $img_path));
            $json["msg_type"] = "OK";
            $json["msg"] = "Profile Image Updated";
        } else {
            $json["msg_type"] = "ERR";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page";
        }
        echo json_encode($json);
    }

    public function change_password()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data["user"] = $this->user;
            $this->load_view(array("users/c_pass"));
        } else {
            redirect("login");
        }
    }

    public function update_pass()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("uname", "Username", "trim|required|min_length[3]|max_length[12]|callback_edit_unique[users.username." . $this->user->id . "]");
            $this->form_validation->set_rules("c_pass", "Current password", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("n_pass", "New password", "trim|required|matches[con_pass]|min_length[3]|max_length[200]");
                $this->form_validation->set_rules("con_pass", "Confirm password", "trim|required");
                if ($this->form_validation->run()) {
                    $old_password = $this->input->post('c_pass');
                    $password_matches = $this->ion_auth->hash_password_db($this->user->id, $old_password);
                    if ($password_matches) {
                        $data = array(
                            "username" => $this->input->post("uname"),
                            "password" => $this->input->post("con_pass"),
                        );
                        $this->ion_auth->update($this->user->id, $data);
                        $this->ion_auth->logout();
                        $json["msg_type"] = "OK";
                        $json["msg"] = "User account Updated Successfull.";
                    } else {
                        $json["msg_type"] = "ERR";
                        $json["msg"] = "Current Password does not match.";
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
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function find_users()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("user_model");
            $this->load->model("interface_model");
            $this->load->model("user_interface_model");
            $un = $this->input->post("uname");
            $user = $this->user_model->find_user_by_username($un);
            $interfaces = array();
            $user_interfaces = array();
            $new_interface = array();
            $diff_array = array();
            if (!empty($user)) {

                $interfaces = $this->interface_model->get_list($user);
                $user_interfaces = $this->user_interface_model->get_interfaces($user);

                foreach ($interfaces as $interface) {
                    $boo = TRUE;
                    foreach ($user_interfaces as $ui) {
                        if ($ui->face_name == $interface->name) {
                            $boo = FALSE;
                        }
                    }
                    if ($boo) {
                        $diff_array[] = $interface;
                    }
                }
//                $diff_array = array_diff_key($interfaces, $new_interface);
                $html = $this->load->view("dashboard/dash_user_inter_ajax", array("uis" => $user_interfaces, "user" => $user, "interfaces" => $diff_array, "infs" => $interfaces, "newIn" => $new_interface), TRUE);
//            $html = $this->load->view("dashboard/dash_user_inter_ajax", array("uis" => $user_interfaces, "user" => $user, "interfaces" => $new_interface), TRUE);
                $json["msg_type"] = "OK";
                $json["user"] = $user;
                $json["html"] = $html;
            } else {
                $json["html"] = $this->load->view("nothing", NULL, TRUE);
                $json["msg_type"] = "OK";
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function user_interfaces()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model('user_interface_model');
            $ids = $this->input->post('id');
            $user_id = $this->input->post('user_id');
            $this->user_interface_model->del_user_data($user_id);
            foreach ($ids as $id):
                $this->user_interface_model->save_user_interfaces($user_id, $id);
            endforeach;
            $json["msg_type"] = "OK";
            $json["msg"] = "Save changes successfully";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function find_users_company()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model("user_model");
            $this->load->model('company_model');
            $this->load->model('user_company_model');
            $un = $this->input->post("uname");
            $user = $this->user_model->find_user_by_username($un);
            if (!empty($user)) {
                $user_coms = $this->user_company_model->get_user_companies($user->id);
                $coms = $this->company_model->get_all();
                $html = $this->load->view('dashboard/dash_company_inter_ajax', array('user' => $user, 'coms' => $coms, 'user_coms' => $user_coms), TRUE);
                $json["msg_type"] = "OK";
                $json["html"] = $html;
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function user_companies()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->load->model('user_company_model');
            $ids = $this->input->post('id');
            $user_id = $this->input->post('user_id');
            $this->user_company_model->del_user_data($user_id);
            if (isset($ids) && !empty($ids)) {
                foreach ($ids as $id):
                    $this->user_company_model->save_user_company($user_id, $id);
                endforeach;
            }
            $json["msg_type"] = "OK";
            $json["msg"] = "Save changes successfully";
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired. Try Again.";
        }
        echo json_encode($json);
    }

    public function send_message()
    {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $body = $this->input->post("msg");

            $this->load->model("wl_employee_model");
            $emp = $this->wl_employee_model->get($id);
            if (!empty($emp)) {
                $tp = $emp->tp1;
                if (empty($tp)) {
                    $tp = $emp->tp2;
                }
                if (!empty($tp)) {

                    $data = $this->send_sms_dialog($emp->tp1, $body, $id);
                    $json["msg_type"] = "OK";
                    $json["data"] = json_decode($data);
                    $json["msg"] = "SMS Sent Successfully.";
                }
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