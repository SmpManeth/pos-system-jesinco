<?php

/**
 * Description of User_manager
 *
 * @author DP4
 * Sep 19, 2018 8:20:15 AM
 */
class User_manager extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("user_model", "um");
    }

    public function index() {
        if ($this->ion_auth->logged_in()) {
            // if ($this->is_admin()) {
                $this->data["head"] = "Users Management";
                $this->data["breadcrums"] = array(array("home", "Home"), "User Management");
                $users = $this->um->get_uses();
                $this->data["users"] = $users;
                $this->load_view(array("admin/users/user_list"));
            // } else {
            //     redirect(base_url("home"));
            // }
        } else {
            redirect(base_url("login"));
        }
    }

    public function edit_user() {
        if ($this->ion_auth->logged_in()) {
            // if ($this->is_admin()) {
                $id = $this->uri->segment(4);
                if (!empty($id)) {
                    $this->data["head"] = "Edit User";
                    $this->data["breadcrums"] = array(array("home", "Home"), array("admin/user-manager", "User Management"), "Edit User");
                    $user = $this->um->get($id);
                    if (!empty($user)) {

//                        User settings Data
                        $this->load->model("common_model");
                        $this->load->model("branch_model");
                        $levels = $this->common_model->get_user_levels();
                        $branches = $this->branch_model->get_branches_all(1);
                        $this->data["user"] = $user;
                        $this->data["levels"] = $levels;
                        $this->data["branches"] = $branches;

//                        User Window Manager Data
                        if (isset($user) && !empty($user)) {
                            $this->load->model("wl_menu_sub_menu_model");
                            $this->load->model("wl_user_interface_model");
                            $menus = $this->wl_menu_sub_menu_model->get_al_menus();
                            $urls = array();
                            foreach ($menus as $menu) {
                                $urls[$menu->main_id]["subs"][$menu->sub_id]["menus"][] = $menu;
                                $urls[$menu->main_id]["name"] = $menu->main_directory;
                                $urls[$menu->main_id]["class"] = $menu->main_icon_class;
                                $urls[$menu->main_id]["subs"][$menu->sub_id]["name"] = $menu->sub_menu;
                                $urls[$menu->main_id]["subs"][$menu->sub_id]["class"] = $menu->sub_icon_class;
                            }
                            $this->data["directories"] = $urls;

                            $this->load->model("wl_user_interface_model");
                            $user_infs = $this->wl_user_interface_model->find_interface_list_alt($user->id);
//                    $dd = explode(",", $user_infs->interface);
                            $this->data["user_directories"] = !empty($user_infs->interface) ? $user_infs->interface : "''";
                        }
//                        $this->load_view(array("admin/user_interfaces"));

                        $this->load_view(array("admin/users/edit_user"));
                    } else {
                        $this->load_view("nothing");
                    }
                } else {
                    redirect("home");
                }
            // } else {
            //     redirect("home");
            // }
        } else {
            redirect(base_url("login"));
        }
    }

    public function save_user_meta() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $id = $this->input->post("id");
            $this->form_validation->set_rules("acc_type", "Account Type", "trim|required");
            if ($this->form_validation->run()) {
                $this->form_validation->set_rules("first_name", "First Name", "trim|required");
                if ($this->form_validation->run()) {
                    $this->form_validation->set_rules("username", "Username", "trim|required|callback_edit_unique[users.username.$id]");
                    if ($this->form_validation->run()) {
                        $_branches = $this->input->post("branches");

                        if (count($_branches) > 0) {
                            $branches = json_encode($_branches);
                            $this->um->update($id, array(
                                "user_type" => $this->input->post("acc_type"),
                                "first_name" => $this->input->post("first_name"),
                                "username" => $this->input->post("username"),
                                "active" => $this->input->post("active"),
                                "branches" => $branches
                            ));

                            $json["msg_type"] = "OK";
                            $json["msg"] = "User Data Update.";
                        } else {
                            $json["msg_type"] = "ERR";
                            $json["msg"] = "Please select atleast one Branch.";
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
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function save_interfaces() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            // if ($this->is_admin()) {
                $user = $this->input->post("user");
                $user_infs_post = $this->input->post("data");
                $this->load->model("wl_user_interface_model");
                $user_infs = $this->wl_user_interface_model->find_interface_list_alt($user);
                $data = array("user_id" => $user, "interface" => json_encode($user_infs_post));
                if (empty($user_infs)) {
                    $this->wl_user_interface_model->insert($data);
                } else {
                    $this->wl_user_interface_model->update($user_infs->id, $data);
                }
                $json["msg_type"] = "OK";
                $json["msg"] = "User access details Saved.";
            // } else {
            //     $json["msg_type"] = "OK";
            //     $json["msg"] = "You can't perform this action.<br/>Permision denied.";
            // }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

    public function change_pass() {
        $json = array();
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules("password", "Password", "trim|required|min_length[4]|matches[c_pass]");
            $this->form_validation->set_rules("c_pass", "Confirm Password", "trim|required|min_length[4]");
            if ($this->form_validation->run()) {
                $id = $this->input->post("id");
                $data = array(
                    "password" => $this->input->post("password")
                );
                $this->ion_auth->update($id, $data);

                $json["msg_type"] = "OK";
                $json["msg"] = "Password updated.";
            } else {
                $json["msg_type"] = "ERR";
                $json["msg"] = validation_errors();
            }
        } else {
            $json["msg_type"] = "LOG";
            $json["msg"] = "Login Session Expired.<br/>Please Refresh the page.";
        }
        echo json_encode($json);
    }

}
