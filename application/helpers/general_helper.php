<?php

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {

    function dump($var, $label = 'Dump', $echo = TRUE) {
        // Store dump in variable 
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        // Output
        if ($echo == TRUE) {
            echo $output;
        } else {
            return $output;
        }
    }

}


if (!function_exists('user_can_edit')) {

    function user_can_edit() {
        $CI = & get_instance();
        if ($CI->user->user_type == "admin" || $CI->user->user_type == "superadmin" || $CI->user->user_type == "admin_lvl2") {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}


if (!function_exists('dump_exit')) {

    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump($var, $label, $echo);
        exit;
    }

}
if (!function_exists('get_Letter')) {

    function get_Letter($idx) {
        if ($idx < 26) {
            return chr(64 + $idx + 1);
        } else {
            return chr(64 + ($idx - 26) + 1);
        }
    }

}

function get_nth($number) {
    if (intval($number) == 0) {
        return "";
    } else if (intval($number) == 1) {
        return "st";
    } else if (intval($number) == 2) {
        return "nd";
    } else if (intval($number) == 3) {
        return "rd";
    } else {
        if (intval($number > 3 && intval($number < 21))) {
            return "th";
        } else {
            $string_last = substr(strval($number), -1);
            if ($string_last == "1") {
                return "st";
            } else if ($string_last == "2") {
                return "nd";
            } else if ($string_last == "3") {
                return "rd";
            } else {
                return "th";
            }
        }
    }
}

/**
 * Filter input based on a whitelist. This filter strips out all characters that
 * are NOT: 
 * - letters
 * - numbers
 * - Textile Markup special characters.
 * 
 * Textile markup special characters are:
 * _-.*#;:|!"+%{}@
 * 
 * This filter will also pass cyrillic characters, and characters like é and ë.
 * 
 * Typical usage:
 * $string = '_ - . * # ; : | ! " + % { } @ abcdefgABCDEFG12345 éüртхцчшщъыэюьЁуфҐ ' . "\nAnd another line!";
 * echo textile_sanitize($string);
 * 
 * @param string $string
 * @return string The sanitized string
 * @author Joost van Veen
 */
function textile_sanitize($string) {
    $whitelist = '/[^a-zA-Z0-9а-яА-ЯéüртхцчшщъыэюьЁуфҐ \.\*\+\\n|#;:!"%@{} _-]/';
    return preg_replace($whitelist, '', $string);
}

function escape($string) {
    return textile_sanitize($string);
}

function time_ago($date) {
    if (empty($date)) {
        return "No date provided";
    }

    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();

    $unix_date = strtotime($date);

    // check validity of date

    if (empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date

    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    return "$difference $periods[$j] {$tense}";
}

function is_time_greater_than_last($time1, $time2) {
    if (strtotime($time2) > strtotime($time1)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function is_date_greater_than_last($date1, $date2) {
    if (strtotime($date1) > strtotime($date2)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function is_date_greater_eq_than_last($date1, $date2) {
    if (strtotime($date1) >= strtotime($date2)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function date_minus_time($date, $minutes) {
    $meeting_time = date('Y-m-d H:i:s', (strtotime($date) - intval($minutes)));
    return $meeting_time;
}

function date_plaus_days($date, $days, $direction = "+", $with_time = FALSE) {
    if ($with_time) {
        $conv_date = date('Y-m-d H:i:s', strtotime($date . "$direction  $days days"));
    } else {
        $conv_date = date('Y-m-d', strtotime($date . "$direction  $days days"));
    }
    return $conv_date;
}

function date_older_than($date, $days, $direction = ">") {
    $conv_date = date('Y-m-d', strtotime($date . "$direction  $days days"));
    if ($direction == ">") {
        if (date_different($date, date('Y-m-d')) >= 10) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        if (date_different($date, date('Y-m-d')) <= 10) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    return $conv_date;
}

function is_date_in_range($start_date, $end_date, $date_from_user) {
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);

    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

/*
 *  Date diff with miliseconds
 */

function time_ago_new($time_ago) {
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hrs ago";
        }
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday " . date("H:i", $time_ago);
        } else {
            return "$days days ago " . date("H:i", $time_ago);
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "a week ago " . date("H:i", $time_ago);
        } else {
            return "$weeks weeks ago " . date("H:i", $time_ago);
        }
    }
    //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

function is_zero($val, $return = "-") {
    if (doubleval($val) > 0) {
        return number_format($val, 2);
    } else {
        return $return;
    }
}

function is_zero_wf($val, $return = "-") {
    if (doubleval($val) > 0) {
        return $val;
    } else {
        return $return;
    }
}

//function decorate_code($id, $doc,$prefixes) {
//    $CI = & get_instance();
//    $CI->db->where("doc", $doc);
//    $dd = $CI->db->get("wl_doc_codes")->result_object();
//    
//    $dd = $prefixes[$doc];
//    if (isset($dd)) {
//        return $dd[0]->prefix . (str_pad($id, $dd[0]->length, "0", STR_PAD_LEFT));
//    } else {
//        return "0o0o0";
//    }
//}
function decorate_code($id, $doc, $prefixes) {
//    $CI = & get_instance();
//    $CI->db->where("doc", $doc);
//    $dd = $CI->db->get("wl_doc_codes")->result_object();

    $dd = $prefixes[strtolower($doc)];
    if (isset($dd)) {
        return $dd->prefix . (str_pad($id, $dd->length, "0", STR_PAD_LEFT));
    } else {
        return "0o0o0";
    }
}

function undecorate_code($string, $target = FALSE) {
    if ($target) {
        $CI = & get_instance();
        $CI->db->where("doc", $target);
        $dd = $CI->db->get("wl_doc_codes")->result_object();
        if (isset($dd[0])) {
            return intval(str_replace($dd[0]->prefix, "", $string));
        } else {
            return "0";
        }
    } else {
        $val = preg_replace('/[^0-9]/', '', $string);
        return intval($val);
    }
}

function add_notification($job_id, $user, $user_id, $status) {
    $CI = & get_instance();
    $data = array(
        "job_id" => $job_id,
        "user" => $user,
        "user_id" => $user_id,
        "n_date" => date("Y-m-d H:i:s"),
        "job_status" => $status
    );
    $CI->db->insert("wl_notifications", $data);
}

function date_different($from, $to) {
    $date1 = date_create($to);
    $date2 = date_create($from);
    $diff = date_diff($date1, $date2);
    return $diff->format("%a");
}

function date_different_without_weekends($from, $to, $saturdays = FALSE) {
    if ($from == $to) {
        return 0;
    }
    $start = new DateTime($to);
    $end = new DateTime($from);
// otherwise the  end date is excluded (bug?)
    $end->modify('+2 day');

    $interval = $end->diff($start);

// total days
    $days = $interval->days;
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    foreach ($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Sun') {
            $days--;
        }
        if ($saturdays) {
            if ($curr == 'Sat') {
                $days--;
            }
        }
    }
    return $days;
}

//            <tr><td align='center' bgcolor='#31d354' style='padding: 25px 0 25px 0;color: #fff;font-size: 24px;'><img src='data:image/png;base64,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' height='60' /></td></tr>


if (!function_exists('encryptor')) {

    function encryptor($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'wl_fr';
        $secret_iv = 'free_rld';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if ($action == 'enc') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'dec') {
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

}

function get_email_html_header() {
    return "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0'/></head>
    <body><table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: 1px #ccc solid;font-family: Consolas;'>
            <tr><td align='center' bgcolor='#31d354' style='padding: 25px 0 25px 0;color: #fff;font-size: 24px;'><img src='https://www.lernit.com.au/public/Logo.png' height='60' /></td></tr>
            <tr><td bgcolor='#ffffff' style='padding: 10px 0px 0px 0px;color: #153643; font-family: Trebuchet MS; font-size: 16px; line-height: 20px;'><table border='0' cellpadding='0' cellspacing='0' width='100%' style='padding: 10px 15px;font-family: Trebuchet MS'>";
}

function get_email_html_footer() {
    return "</table></td></tr><tr><td colspan='5'><table border='0' cellpadding='0' cellspacing='0' width='100%'>
                        <tr><td valign='top'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td style='font-family: Trebuchet MS;padding: 25px 0 0 0;text-align:center'>Please contact the LERN-IT Team for further Information</td></tr></table></td></tr><tr><td valign='top'></td></tr></table></td></tr>
            <tr><td colspan='5' bgcolor='#31d354' style='padding: 10px 0px 8px 5px;color: #fff'><table border='0' cellpadding='0' cellspacing='0' width='100%'>
                        <tr><td style='font-family: Trebuchet MS;text-align: center;color: #fff'>Thank you<br/><small>LERN-IT Team</small></td></tr></table></td></tr>
        </table></body></html>";
}

function get_months() {
    return array(
        "01" => "January",
        "02" => "February",
        "03" => "March",
        "04" => "April",
        "05" => "May",
        "06" => "June",
        "07" => "July",
        "08" => "August",
        "09" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December",
    );
}

function get_month($m) {
    $months = array(
        "01" => "January",
        "02" => "February",
        "03" => "March",
        "04" => "April",
        "05" => "May",
        "06" => "June",
        "07" => "July",
        "08" => "August",
        "09" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December",
    );
    return $months[$m];
}

function convert_date_to_db($date) {
    $_parts = explode("/", $date);
    if (count($_parts) > 0) {
        return $_parts[2] . "-" . $_parts[1] . "-" . $_parts[0];
    } else {
        return date("Y-m-d");
    }
}

function convert_date_from_db($date) {
    return date("d/m/Y", strtotime($date));
}

function current_url_with_query() {
    $CI = & get_instance();

    $url = $CI->config->base_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}

function current_url_without_bs_with_query() {
    $CI = & get_instance();

    $url = ($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}

function query_uri_string($array) {
    $string = "";
    if (count($array) > 0) {
        $i = 0;
        foreach ($array as $val) {
            $i++;
            $string .= $val['key'] . "=" . $val['value'] . (count($array) == $i ? "" : "&");
        }
    }
    return $string;
}

function array_to_query_uri_string($array) {
    $string = "";
    if (count($array) > 0) {
        $i = 0;
        foreach ($array as $key => $val) {
            $i++;
            $string .= $key . "=" . $val . (count($array) == $i ? "" : "&");
        }
    }
    return $string;
}
