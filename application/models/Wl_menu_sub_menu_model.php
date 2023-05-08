<?php

/**
 * Description of wl_menu_sub_menu_model
 *
 * @author dilshan
 */
class Wl_menu_sub_menu_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_menu_sub_menus";
    }

    public function get_al_menus($status=FALSE) {
        $this->db->select("wl_menu_sub_menus.id as menu_id,wl_menu_sub_menus.main_id,wl_menu_sub_menus.sub_id,wl_menu_sub_menus.menu_name,wl_menu_sub_menus.icon_class,wl_menu_sub_menus.url");
        $this->db->select("wl_menu_subs.sub_menu,wl_menu_subs.sub_icon_class,wl_menu_mains.main_directory,wl_menu_mains.main_icon_class");
        $this->db->join("wl_menu_subs", "wl_menu_sub_menus.sub_id = wl_menu_subs.id", "LEFT");
        $this->db->join("wl_menu_mains", "wl_menu_sub_menus.main_id = wl_menu_mains.id", "LEFT");
        if ($status) {
            $this->db->where("wl_menu_sub_menus.show",$status);
        }
        $this->order_by("wl_menu_sub_menus.order");
        return $this->get_all();
    }

    public function get_user_menus($ids) {
//        if (empty($ids)) {
//            return array();
//        }
        $this->db->select("wl_menu_sub_menus.id as menu_id,wl_menu_sub_menus.main_id,wl_menu_sub_menus.sub_id,wl_menu_sub_menus.menu_name,wl_menu_sub_menus.icon_class,wl_menu_sub_menus.url");
        $this->db->select("wl_menu_subs.sub_menu,wl_menu_subs.sub_icon_class,wl_menu_mains.main_directory,wl_menu_mains.main_icon_class");
        $this->db->join("wl_menu_subs", "wl_menu_sub_menus.sub_id = wl_menu_subs.id", "LEFT");
        $this->db->join("wl_menu_mains", "wl_menu_sub_menus.main_id = wl_menu_mains.id", "LEFT");
        $this->order_by("wl_menu_sub_menus.order");
        $this->db->where_in("wl_menu_sub_menus.id", $ids);
        return $this->get_all();
    }

    public function get_user_interfaces($array) {
        if (empty($array)) {
            return array();
        }
        $this->db->select("wl_menu_sub_menus.main_id,wl_menu_sub_menus.sub_id,wl_menu_sub_menus.menu_name,wl_menu_sub_menus.icon_class,wl_menu_sub_menus.url");
        $this->db->select("wl_menu_subs.sub_menu,wl_menu_subs.sub_icon_class,wl_menu_mains.main_directory,wl_menu_mains.main_icon_class,wl_menu_sub_menus.id as menu_id");
        $this->db->join("wl_menu_subs", "wl_menu_sub_menus.sub_id = wl_menu_subs.id", "LEFT");
        $this->db->join("wl_menu_mains", "wl_menu_sub_menus.main_id = wl_menu_mains.id", "LEFT");
        $this->order_by("wl_menu_sub_menus.order");
        return $this->get_many_by("wl_menu_sub_menus.id", $array);
    }

}
