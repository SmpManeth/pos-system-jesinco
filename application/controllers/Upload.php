<?php

/**
 * Description of upload
 *
 * @author dilshan
 */
class Upload extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function upload_file() {
        $year = date("Y");
        $month = date("m");
        $status = "";
        $msg = "";
        $img_path = "";
        $img_name = "";
        $file_element_name = 'userfile';
        $folder = $this->input->post("folder");
        $accept = $this->input->post("accept");
        $resize = $this->input->post("resize");
        $resize_size = $this->input->post("resize-size");
        $data_file = FALSE;
        if (empty($folder)) {
            $folder = "uploads";
        }
        if (empty($accept)) {
            $accept = "jpg|png|jpeg";
        }


        if (!file_exists("./public/$folder/$year/$month/")) {
            mkdir("./public/$folder/$year/$month/", 0777, TRUE);
        }
        $mimetype = mime_content_type($_FILES['userfile']['tmp_name']);
        if ($status != "error") {
            $config['upload_path'] = "./public/$folder/$year/$month/";
            if (in_array($mimetype, array('image/jpeg', 'image/gif', 'image/png'))) {

                $config['max_size'] = 1024 * 2;
                $config['encrypt_name'] = TRUE;
                $config['allowed_types'] = "*";
                $this->load->library('upload', $config);

                
                if (!$this->upload->do_upload($file_element_name)) {
                    $status = 'ERR';
                    $msg = $this->upload->display_errors('', '');
                } else {
                    $data_file = $this->upload->data();
                    $status = "OK";
                    $msg = "File uploaded";
                    $img_path = base_url("./public/$folder/$year/$month/" . $data_file['file_name']);
                    $imagepath = "./public/$folder/$year/$month/" . $data_file['file_name'];
                    $img_name = "$year/$month/" . $data_file['file_name'];
                    if (isset($resize) && $resize == "1") {
                        if (isset($resize_size) && !empty($resize_size)) {
                            $width = intval($resize_size);
                        } else {
                            $width = 300;
                        }
                        $scale = $width / $this->getWidth($imagepath);
                        $this->resizeImage($imagepath, $this->getWidth($imagepath), $this->getHeight($imagepath), $scale, $imagepath);
                    }
                }
                @unlink($_FILES[$file_element_name]);
            } else {
                $msg = "The filetype you are attempting to upload is not allowed";
                $status = "error";
            }
        }
        echo json_encode(array('msg_type' => $status, 'msg' => $msg, 'data' => $data_file, "url" => $img_path, "name" => $img_name));
    }

    public function upload_cover() {
        $status = "";
        $msg = "";
        $img_path = "";
        $file_element_name = 'userfile';

        if ($status != "error") {
            $config['upload_path'] = './public/images/profile/ori/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size'] = 1024 * 4;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            $data_file = FALSE;
            if (!$this->upload->do_upload($file_element_name)) {
                $status = 'ERR';
                $msg = $this->upload->display_errors('', '');
            } else {
                $data_file = $this->upload->data();
                $imagepath = './public/images/profile/ori/' . $data_file['file_name'];
                $img_path = site_url("./public/images/profile/ori/" . $data_file['file_name']);
                $scale = 1000 / $this->getWidth($imagepath);
                $this->resizeImage($imagepath, $this->getWidth($imagepath), $this->getHeight($imagepath), $scale, $imagepath);
                $status = "OK";
                $msg = "File successfully uploaded";
            }
            @unlink($_FILES[$file_element_name]);
        }
        echo json_encode(array('msg_type' => $status, 'msg' => $msg, 'data' => $data_file, "img" => $img_path));
    }

    public function jcrop_image() {
        $x1 = $this->input->post("x1");
        $y1 = $this->input->post("y1");
        $w = $this->input->post("w");
        $h = $this->input->post("h");
        $file = $this->input->post("file");

//        $data_file = $this->upload->data();
//                dump($data_file);
        $imagepath = './public/images/profile/ori/' . $file;
        $thumb_image_location = './public/images/profile/thumb/' . $file;
        //Scale the image to the thumb_width set above
        $thumb_width = 300;

        $scale = $thumb_width / $w;
        $cropped = $this->resizeThumbnailImage($thumb_image_location, $imagepath, $w, $h, $x1, $y1, $scale);

        $json = array();
        $json["file"] = $file;
        $json["file_thumb"] = site_url("public/images/profile/thumb/" . $file . "?s" . time());
        $json["path_ori"] = site_url("public/images/profile/ori/" . $file);
        echo json_encode($json);
    }

################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
################################################################################

    function resizeImage($image, $width, $height, $scale, $savepath) {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newImageWidth, $newImageHeight, $transparent);
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);

        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $savepath);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $savepath, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $savepath);
                break;
        }
        chmod($savepath, 0777);
        return $savepath;
    }

    function getHeight($image) {
        $size = getimagesize($image);
        $height = $size[1];
        return $height;
    }

//You do not need to alter these functions
    function getWidth($image) {
        $size = getimagesize($image);
        $width = $size[0];
        return $width;
    }

    private function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case "image/gif":
                $source = imagecreatefromgif($image);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                $source = imagecreatefromjpeg($image);
                break;
            case "image/png":
            case "image/x-png":
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case "image/gif":
                imagegif($newImage, $thumb_image_name);
                break;
            case "image/pjpeg":
            case "image/jpeg":
            case "image/jpg":
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case "image/png":
            case "image/x-png":
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

}
