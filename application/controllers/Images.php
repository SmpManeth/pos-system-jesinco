<?php

/**
 * Description of images
 *
 * @author dilshan
 */
class Images extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $img = $this->uri->segment(2);
        $full_img_path = ("./public/attachments/images/$img");
        header('Content-Length: ' . filesize($full_img_path)); //<-- sends filesize header
        header('Content-Type: image/jpg'); //<-- send mime-type header
        header('Content-Disposition: inline; filename="' . $img . '";'); //<-- sends filename header
        readfile($full_img_path); //<--reads and outputs the file onto the output buffer
        die(); //<--cleanup
        exit; //and exit
    }

}
