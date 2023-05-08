<?php

/**
 * Description of wl_prefix_model
 *
 * @author dilshan
 */
class Wl_doc_code_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_table = "wl_doc_codes";
    }

    public function get_prefixes($branch) {
        $this->db->where("branch", $branch);
        $prefixes = $this->get_all();
        $new_prefix = array();
        foreach ($prefixes as $prefix) {
            $new_prefix[$prefix->doc] = $prefix;
        }
        return $new_prefix;
    }

    public function save_prefixes($doc, $prefix, $branch) {
        $this->db->where("branch", $branch->id);
        $pref = $this->get_by("doc", $doc);
        if (empty($pref)) {
            $data = array(
                "doc" => $doc,
                "prefix" => $prefix,
                "length" => 5,
                "branch" => $branch->id
            );
            $this->insert($data);
        } else {
            $data = array(
                "doc" => $doc,
                "prefix" => $prefix,
            );
            $this->update($pref->id, $data);
        }
    }

}
