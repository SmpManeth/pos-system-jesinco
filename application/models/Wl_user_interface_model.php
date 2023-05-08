<?php

/**
 * Description of wl_user_interface_model
 *
 * @author dilshan
 */
class Wl_user_interface_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_user_interfaces";
    }

    public function find_interface_list($user) {
        $this->db->select("wl_user_interfaces.id,wl_user_interfaces.user_id,wl_user_interfaces.interface,wl_menu_sub_menus.menu_name,wl_menu_sub_menus.sub_id,wl_menu_sub_menus.main_id,wl_menu_sub_menus.url,wl_menu_sub_menus.`level`");
        $this->db->join("wl_user_interfaces", "wl_user_interfaces.interface = wl_menu_sub_menus.id", "LEFT");
        $this->db->where("user_id", $user->id);
        return $this->get_all();
    }
    public function find_interface_list_alt($user_id) {
        return $this->get_by("user_id", $user_id);
    }

}
