<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define("CAN_SEND_SMS_TO_CUSTOMERS", "P1");
define("CAN_EDIT_UN_APPROVED_CUSTOMERS", "P2");
define("CAN_APPROVE_CUSTOMER", "P3");
define("CAN_CUSTOMER_INSERT", "P4");
define("CAN_CUSTOMER_EDIT", "P5");
define("CAN_ADJUST_STOCK", "P6");
define("CAN_ADD_RETURNS", "P7");
define("CAN_EDIT_DO", "P8");
define("CAN_APPROVE_DO", "P9");
define("CAN_CANCEL_DO", "P10");
define("CAN_CREATE_INVOICE", "P11");
define("CAN_CANCEL_INVOICE", "P12");
define("CAN_CANCEL_INVOCE_APPROVE", "P13");
define("CAN_CANCEL_DO_APPROVE", "P14");
define("CAN_EDIT_INVOICE_INSTALLMENTS", "P15");
define("CAN_REMOVE_FINE", "P16");
define("CAN_FINISH_INVOICE", "P17");
define("CAN_CANCEL_PAYMENT", "P18");
define("CAN_PRINT_RECEIPT", "P19");

/* End of file constants.php */
/* Location: ./application/config/constants.php */
