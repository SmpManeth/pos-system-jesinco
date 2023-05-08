<?php

use Netcarver\Textile;

class MY_Controller extends CI_Controller
{

    public $data = array();
    public $branch;
    public $user;
    public $prefixes;
    public $user_prefixes = array("Master", "Mr", "Miss", "Mrs", "Ms", "Mx");

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->ion_auth->user()->row();
        $this->data["user"] = $this->user;
        $this->load->library('session');
        if (isset($_REQUEST['is_ajax_request'])) {
            $this->output->enable_profiler(false);
        } else {
//            $this->output->enable_profiler(ENVIRONMENT == 'development');
        }

//        $this->load->model("wl_menu_sub_menu_model");
//        $menus = $this->wl_menu_sub_menu_model->get_al_menus();
//        $urls = array();
//        foreach ($menus as $menu) {
//            $urls[$menu->main_id]["subs"][$menu->sub_id]["menus"][] = $menu;
//            $urls[$menu->main_id]["name"] = $menu->main_directory;
//            $urls[$menu->main_id]["class"] = $menu->main_icon_class;
//            $urls[$menu->main_id]["subs"][$menu->sub_id]["name"] = $menu->sub_menu;
//            $urls[$menu->main_id]["subs"][$menu->sub_id]["class"] = $menu->sub_icon_class;
//        }
//        $this->data["directories"] = $urls;
        $this->data["user_prefixes"] = $this->user_prefixes;
        $this->load->helper("string");

        if ($this->logged_in()) {
            $branch_id = $this->session->userdata("branch");
            $this->load->model("branch_model");
            $this->load->model("company_model");
            $company = $this->company_model->get(1);
            $branch = $this->branch_model->get($branch_id);
            $this->branch = $branch;
            $this->data["company"] = $company;
            $this->data["branch"] = $branch;

            $this->load->model("wl_doc_code_model");
            $prefix = $this->wl_doc_code_model->get_prefixes($this->branch->id);
            $this->prefixes = $prefix;
            $this->data["directories"] = $this->get_interfaces_v2();
        }
    }

    public function get_interfaces_v2()
    {
        $this->load->model("wl_menu_sub_menu_model");

        if ($this->is_superadmin()) {
            $menus = $this->wl_menu_sub_menu_model->get_al_menus(1);
        } else if ($this->is_admin()) {
            $menus = $this->wl_menu_sub_menu_model->get_al_menus(1);
        } else {
            $this->load->model("wl_user_interface_model");
            $_ids = $this->wl_user_interface_model->get_by("user_id", $this->user->id);
            $ids = json_decode($_ids->interface);
            $menus = $this->wl_menu_sub_menu_model->get_user_menus($ids);
        }
        $urls = array();
        foreach ($menus as $menu) {
            $urls[$menu->main_id]["subs"][$menu->sub_id]["menus"][] = $menu;
            $urls[$menu->main_id]["name"] = $menu->main_directory;
            $urls[$menu->main_id]["class"] = $menu->main_icon_class;
            $urls[$menu->main_id]["subs"][$menu->sub_id]["name"] = $menu->sub_menu;
            $urls[$menu->main_id]["subs"][$menu->sub_id]["class"] = $menu->sub_icon_class;
        }
        return $urls;
    }

    public function get_user_interfaces()
    {
        $this->load->model("wl_menu_sub_menu_model");
        if ($this->is_admin()) {
            $menus = $this->wl_menu_sub_menu_model->get_al_menus();
        } else {
            $this->load->model("wl_user_interface_model");
            $raw_array = $this->wl_user_interface_model->find_interface_list_alt($this->user->id);
            $array = !empty($raw_array) ? json_decode($this->encode_interface_array($raw_array->interface)) : array();
            $menus = $this->wl_menu_sub_menu_model->get_user_interfaces($array);
        }
        $urls = array();
        foreach ($menus as $menu) {
            $this->user_urls[] = $menu->url;
            $urls[$menu->main_id]["subs"][$menu->sub_id]["menus"][] = $menu;
            $urls[$menu->main_id]["name"] = $menu->main_directory;
            $urls[$menu->main_id]["class"] = $menu->main_icon_class;
            $urls[$menu->main_id]["subs"][$menu->sub_id]["name"] = $menu->sub_menu;
            $urls[$menu->main_id]["subs"][$menu->sub_id]["class"] = $menu->sub_icon_class;
        }
        return $urls;
    }

    /**
     * Set Subview and Load Layout
     * @param String $subview
     */
    public function load_view($subview)
    {
        $this->data['subviews'] = $subview;
//        $this->load->view('layouts/layoutdashboard', $this->data);
        $this->load->view('layouts/layout_sidebar', $this->data);
    }

    public function load_login($subview)
    {
        $this->data['subviews'] = $subview;
        $this->load->view('layouts/layout_login', $this->data);
    }

    public function redirector($subview)
    {
        if ($subview == "dashboard") {
            redirect('users/dashboard');
        }
    }

    public function sendMail($to, $subject, $message)
    {
        $config = array(
            'protocol' => 'sendmail',
            'mailpath' => '/usr/sbin/sendmail',
            'smtp_host' => 'mail.slfind.com',
            'smtp_port' => 25,
            'smtp_user' => 'admin@slfind.com', // change it to yours
            'smtp_pass' => '1988Jun29', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'priority' => '1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('admin@slfind.com', "Aadaraya - Admin"); // change it to yours
        $this->email->to($to); // change it to yours
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function log_login($type, $action)
    {
        $ip = $this->input->ip_address();
        $browser = $this->input->user_agent();
        $data = array(
            "ip" => $ip,
            "browser" => $browser,
            "branch" => $this->branch->id,
            "section" => $type,
            "action" => $action,
            'by' => $this->user->id
        );
        $this->load->model("log_model");
        $this->log_model->insert($data);
    }

    public function send_mail($to, $subject, $message)
    {
        $this->load->library('email');

        // Get full html:
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
    <style type="text/css">body {font-family: Arial, Verdana, Helvetica, sans-serif;font-size: 16px;}</style>
</head>
<body>' . $message . '</body>
</html>';
        // Also, for getting full html you may use the following internal method:
        //$body = $this->email->full_html($subject, $message);

        $result = $this->email
            ->from("admin@slfind.com", "Aadaraya - Admin1")
            ->reply_to('')    // Optional, an account where a human being reads.
            ->to($to)
            ->subject($subject)
            ->message($body)
            ->send();

        var_dump($result);
        echo $this->email->print_debugger();
    }

    function edit_unique($value, $params)
    {
        $this->form_validation->set_message('edit_unique', "Sorry, that %s is already being used.");
        list($table, $field, $current_id) = explode(".", $params);
        $query = $this->db->select()->from($table)->where($field, $value)->limit(1)->get();
        if ($query->row() && $query->row()->id != $current_id) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function combo($element)
    {
        if ($element == '-1') {
            return FALSE;
        }
        return TRUE;
    }

    public function get_Letter($idx)
    {
        if ($idx < 26) {
            return chr(64 + $idx + 1);
        } else {
            return chr(64 + ($idx - 26) + 1);
        }
    }

    public function logged_in()
    {
        if ($this->ion_auth->logged_in()) {
            $company = $this->session->userdata("branch");
            if (empty($company)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function is_admin()
    {
        $boo = FALSE;
        if ($this->ion_auth->logged_in()) {
            if (empty($this->user)) {
                $this->user = $this->ion_auth->get_user();
            }
            if ($this->user->user_type == "admin" || $this->user->user_type == "super" || $this->user->user_type == "superadmin") {
                $boo = TRUE;
            }
        }
        return $boo;
    }

    public function is_superadmin()
    {
        $boo = FALSE;
        if ($this->ion_auth->logged_in()) {
            if (empty($this->user)) {
                $this->user = $this->ion_auth->get_user();
            }
            if ($this->user->user_type == "superadmin") {
                $boo = TRUE;
            }
        }
        return $boo;
    }

    public function encode_interface_array($array)
    {
        $str = strip_quotes($array);
        $str = str_replace("[", "", $str);
        $str = str_replace("]", "", $str);
        return $str;
    }

    public function send_sms_dialog($number, $body, $inv_id)
    {

        if (ENVIRONMENT == "production") {

            if (substr($number, 0, 2) === "94") {
                $_number = $number;
            } else {
                if (substr($number, 0, 1) === "0") {
                    $_number = "94" . substr($number, 1);
                } else {
                    $_number = "94" . $number;
                }
            }

            $username = "jajdpluser";
            $digest = md5("@q123456");
            $now = date("Y-m-d\TH:i:s");
            $url = "https://richcommunication.dialog.lk/api/sms/send";
            $payload = array(
                "messages" => [
                    array(
                        "clientRef" => $inv_id,
                        "number" => $_number,
                        "mask" => "JESINCO",
                        "text" => $body,
                        "campaignName" => "invoice_pay",
                    )
                ]
            );

            $headers = [
                'Content-Type: application/json',
                'USER: ' . $username,
                'DIGEST: ' . $digest,
                'CREATED: ' . $now
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $head = curl_exec($ch);
            curl_close($ch);

            if (!$head) {
                return FALSE;
            }

            return ($head);
//        }
        }
    }

    public function send_sms_dialog_bulk($_numbers, $body, $inv_id)
    {

        if (ENVIRONMENT == "production") {

            $numbers = explode(',', $_numbers);
            $__numbers = [];
            foreach ($numbers as $_n) {
                if (substr($_n, 0, 2) === "94") {
                    $__numbers[] = $_n;
                } else {
                    if (substr($_n, 0, 1) === "0") {
                        $__numbers[] = "94" . substr($_n, 1);
                    } else {
                        $__numbers[] = "94" . $_n;
                    }
                }
            }
            if (count($__numbers) > 0) {
		date_default_timezone_set('Asia/Colombo');
		log_message('debug', 'SMS Bulk numbers: '.implode(',', $__numbers)) ;
                $username = "jajdpluser";
                $digest = md5("@q123456");
                $now = date("Y-m-d\TH:i:s");
                $url = "https://richcommunication.dialog.lk/api/sms/send";
                $payload = array(
                    "messages" => [
                        array(
                            "clientRef" => $inv_id,
                            "number" => implode(',', $__numbers),
                            "mask" => "JESINCO",
                            "text" => $body,
                            "campaignName" => "inv_promo",
                        )
                    ]
                );

                $headers = [
                    'Content-Type: application/json',
                    'USER: ' . $username,
                    'DIGEST: ' . $digest,
                    'CREATED: ' . $now
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                $head = curl_exec($ch);
                curl_close($ch);

                if (!$head) {
                    return FALSE;
                }

                return ($head);
            } else {
                return false;
            }


//        }
        }
    }

}
