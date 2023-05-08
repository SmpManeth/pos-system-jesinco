<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$route['default_controller'] = "welcome";
$route['home'] = "company/home";
$route['login'] = "users/loginform";
$route['dashboard'] = "company/home";
$route['my-company'] = "company/view_company";
$route['my-profile'] = "users/my_profile";
$route['company_select'] = "company/company_select";
$route['change-company'] = "company/change_company";
$route['images/(:any)'] = "images/view/$1";
$route['wl-admin/(:any)'] = "wl_admin/$1/$2";
$route['po/new'] = "po/new_po";
$route['grn/new'] = "grn/new_grn";
$route['invoice/new'] = "invoice/new_invoice";
$route['404_override'] = 'Welcome/not_found';

$route['404_override'] = 'notfound';
$route['translate_uri_dashes'] = TRUE;

/* End of file routes.php */
/* Location: ./application/config/routes.php */