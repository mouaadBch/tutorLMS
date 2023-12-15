<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* code mouaad ///////////////////////////////////////////////////////////////////////////////*/
if (!function_exists('max_nbr_tutor')) {
    function max_nbr_tutor()
    {
        $CI    = &get_instance();
        /* $CI->load->database(); */
        $max_tutor = $CI->super_admin_model->get_parametre('maximum_number_of_tutor');
        $max_user = $CI->super_admin_model->get_parametre('max_nombre_user');

        $nb_user =  $CI->super_admin_model->get_nbr_user_enroled();
        $nbr_tutor = $CI->super_admin_model->get_nbr_tutor();

        return ($max_tutor == 0) ? $max_user > ($nbr_tutor + $nb_user) :  $max_tutor > $nbr_tutor ;
    }
}
if (!function_exists('max_nbr_courses')) {
    function max_nbr_courses()
    {
        $CI    = &get_instance();

        $max_nombre_courses = $CI->super_admin_model->get_parametre('max_nombre_courses');
        $get_nb_courses = $CI->super_admin_model->get_nb_courses();

        return ($max_nombre_courses == 0)? true : $max_nombre_courses > $get_nb_courses ;
    }
}
if (!function_exists('max_nbr_user')) {
    function max_nbr_user()
    {
        $CI    = &get_instance();

        $max_nombre_user = $CI->super_admin_model->get_parametre('max_nombre_user');
        $nb_user =  $CI->super_admin_model->get_nbr_user_enroled();

        return ($max_nombre_user == 0)? true : $max_nombre_user > $nb_user ;
    }
}

/* code Mouaad ///////////////////////////////////////////////////////////////////////////////*/


function substrFuction($last, $first, $type = 1)
{
    if ($type == 1) {
        $last = preg_replace('/[^A-Za-z0-9\-]/', '', $last);
        $full_name = substr(ucfirst($last), 0, 1) . '.' . $first;
    } else {
        $full_name = $last . ' ' . $first;
    }
    return $full_name;
}

function remove_Quotes($string)
{
    return str_replace("'", "&apos;", $string);
}

/* function dirPhrase(){
    $CI    = &get_instance();
    $CI->load->database();
    return ($CI->session->userdata('language') == 'ar') ?  'rtl' : 'ltr'; 
} */

if (!function_exists('remove_js')) {
    function remove_js($description = '')
    {
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $description);
    }
}


if (!function_exists('htmlspecialchars_')) {
    function htmlspecialchars_($description = '')
    {
        return htmlspecialchars($description ?? "");
    }
}
if (!function_exists('htmlspecialchars_decode_')) {
    function htmlspecialchars_decode_($description = '')
    {
        return htmlspecialchars_decode($description ?? "");
    }
}

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('set_url_history')) {
    function set_url_history($url)
    {
        $CI    = &get_instance();
        $CI->session->set_userdata('url_history', $url);
    }
}

if (!function_exists('has_permission')) {
    function has_permission($permission_for = '', $admin_id = '')
    {
        $CI    = &get_instance();
        $CI->load->database();

        // GET THE LOGGEDIN IN ADMIN ID
        if (empty($admin_id)) {
            $admin_id = $CI->session->userdata('user_id');
        }

        $CI->db->where('admin_id', $admin_id);
        $get_admin_permissions = $CI->db->get('permissions');
        if ($get_admin_permissions->num_rows() == 0) {
            return true;
        } else {
            $get_admin_permissions = $get_admin_permissions->row_array();
            $permissions = json_decode($get_admin_permissions['permissions']);
            if (in_array($permission_for, $permissions)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('check_permission')) {
    function check_permission($permission_for)
    {
        $CI    = &get_instance();
        $CI->load->database();

        if (!has_permission($permission_for)) {
            $CI->session->set_flashdata('error_message', get_phrase('you_are_not_authorized_to_access_this_page'));
            redirect(site_url('admin/dashboard'), 'refresh');
        }
    }
}

if (!function_exists('is_root_admin')) {
    function is_root_admin($admin_id = '')
    {
        $CI    = &get_instance();
        $CI->load->database();

        // GET THE LOGGEDIN IN ADMIN ID
        if (empty($admin_id)) {
            $admin_id = $CI->session->userdata('user_id');
        }

        $CI->db->where('admin_id', $admin_id);
        $get_admin_permissions = $CI->db->get('permissions');
        if ($get_admin_permissions->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('custom_date')) {
    function custom_date($strtotime = "", $format = "")
    {
        if ($format == "") {
            return date('d', $strtotime) . ' ' . site_phrase(date('M', $strtotime)) . ' ' . date('Y', $strtotime);
        } elseif ($format == 1) {
            return site_phrase(date('D', $strtotime)) . ', ' . date('d', $strtotime) . ' ' . site_phrase(date('M', $strtotime)) . ' ' . date('Y', $strtotime);
        }
    }
}

if (!function_exists('nice_number')) {
    function nice_number($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) return false;

        // now filter it;
        if ($n > 1000000000000) return round(($n / 1000000000000), 1) . 'T';
        elseif ($n > 1000000000) return round(($n / 1000000000), 1) . 'M';
        elseif ($n > 1000000) return round(($n / 1000000), 1) . 'M';
        elseif ($n > 1000) return round(($n / 1000), 1) . 'k';

        return number_format($n);
    }
}

if (!function_exists('get_past_time')) {
    function get_past_time($time = "")
    {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return 'less than 1 second ago';
        }

        //864000 = 10 days
        if ($time_difference > 864000) {
            return custom_date($time, 1);
        }

        $condition = array(
            12 * 30 * 24 * 60 * 60 =>  site_phrase('year'),
            30 * 24 * 60 * 60       =>  site_phrase('month'),
            24 * 60 * 60            =>  site_phrase('day'),
            60 * 60                 =>  site_phrase('hour'),
            60                      =>  site_phrase('minute'),
            1                       =>  site_phrase('second')
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ' . site_phrase('ago');
            }
        }
    }
}

if (!function_exists('resizeImage')) {
    function resizeImage($filelocation = "", $target_path = "", $width = "", $height = "")
    {
        $CI = &get_instance();
        $CI->load->database();

        if ($width == "") {
            $width = 200;
        }

        if ($height == "") {
            $maintain_ratio = TRUE;
        } else {
            $maintain_ratio = FALSE;
        }

        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $filelocation,
            'new_image' => $target_path,
            'maintain_ratio' => $maintain_ratio,
            'create_thumb' => TRUE,
            'thumb_marker' => '',
            'width' => $width,
            'height' => $height
        );
        $CI->load->library('image_lib', $config_manip);

        if ($CI->image_lib->resize()) {
            return true;
        } else {
            $CI->image_lib->display_errors();
            return false;
        }
        $CI->image_lib->clear();
    }
}

if (!function_exists('get_settings')) {
    function get_settings($key = '', $type = "")
    {
        $CI    = &get_instance();
        $CI->load->database();

        $CI->db->where('key', $key);
        $result = $CI->db->get('settings')->row('value');

        if ($type) {
            return json_decode($result, true);
        } else {
            return $result;
        }
    }
}

if (!function_exists('currency')) {
    function currency($price = "")
    {
        $CI    = &get_instance();
        $CI->load->database();
        if ($price != "") {
            $CI->db->where('key', 'system_currency');
            $currency_code = $CI->db->get('settings')->row('value');

            $CI->db->where('code', $currency_code);
            $symbol = $CI->db->get('currency')->row('symbol');

            $CI->db->where('key', 'currency_position');
            $position = $CI->db->get('settings')->row('value');

            if ($position == 'right') {
                return $price . $symbol;
            } elseif ($position == 'right-space') {
                return $price . ' ' . $symbol;
            } elseif ($position == 'left') {
                return $symbol . $price;
            } elseif ($position == 'left-space') {
                return $symbol . ' ' . $price;
            }
        } else {
            $CI->db->where('key', 'system_currency');
            $currency_code = $CI->db->get('settings')->row('value');

            $CI->db->where('code', $currency_code);
            return $CI->db->get('currency')->row()->symbol;
        }
    }
}

if (!function_exists('currency_code_and_symbol')) {
    function currency_code_and_symbol($type = "")
    {
        $CI    = &get_instance();
        $CI->load->database();
        $CI->db->where('key', 'system_currency');
        $currency_code = $CI->db->get('settings')->row('value');

        $CI->db->where('code', $currency_code);
        $symbol = $CI->db->get('currency')->row()->symbol;
        if ($type == "") {
            return $symbol;
        } else {
            return $currency_code;
        }
    }
}

if (!function_exists('get_frontend_settings')) {
    function get_frontend_settings($key = '')
    {
        $CI    = &get_instance();
        $CI->load->database();

        $CI->db->where('key', $key);
        $result = $CI->db->get('frontend_settings')->row('value');



        if ($key == 'banner_image') {
            $banner_images = json_decode($result, true);
            return $banner_images[get_frontend_settings('home_page')];
        }
        return $result;
    }
}

if (!function_exists('get_current_banner')) {
    function get_current_banner($key = '')
    {
        $CI    = &get_instance();
        $CI->load->database();

        $CI->db->where('key', $key);
        $result = $CI->db->get('frontend_settings')->row('value');

        $banner_images = json_decode($result, true);
        return $banner_images[get_frontend_settings('home_page')];
    }
}

if (!function_exists('slugify')) {
    function slugify($text)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        ///$text = strtolower($text);
        mb_strtolower($text, 'UTF-8');
        //$text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
            return 'n-a';
        return $text;
    }
}

if (!function_exists('get_video_extension')) {
    // Checks if a video is youtube, vimeo or any other
    function get_video_extension($url)
    {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if (!function_exists('ellipsis')) {
    // Checks if a video is youtube, vimeo or any other
    function ellipsis($long_string, $max_character = 30)
    {
        $short_string = strlen($long_string) > $max_character ? mb_substr($long_string, 0, $max_character) . "..." : $long_string;
        return $short_string;
    }
}

// This function helps us to decode the theme configuration json file and return that array to us
if (!function_exists('themeConfiguration')) {
    function themeConfiguration($theme, $key = "")
    {
        $themeConfigs = [];
        if (file_exists('assets/frontend/' . $theme . '/config/theme-config.json')) {
            $themeConfigs = file_get_contents('assets/frontend/' . $theme . '/config/theme-config.json');
            $themeConfigs = json_decode($themeConfigs, true);
            if ($key != "") {
                if (array_key_exists($key, $themeConfigs)) {
                    return $themeConfigs[$key];
                } else {
                    return false;
                }
            } else {
                return $themeConfigs;
            }
        } else {
            return false;
        }
    }
}

// Human readable time
if (!function_exists('readable_time_for_humans')) {
    function readable_time_for_humans($duration)
    {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour   = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour . ' ' . get_phrase('hr') . ' ' . $minute . ' ' . get_phrase('min');
            } elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute + 1) . ' ' . get_phrase('min');
                } else {
                    $duration = $minute . ' ' . get_phrase('min');
                }
            } elseif ($second > 0) {
                $duration = $second . ' ' . get_phrase('sec');
            } else {
                $duration = '00:00';
            }
        } else {
            $duration = '00:00';
        }
        return $duration;
    }
}

// Human readable time
if (!function_exists('seconds_to_time_format')) {
    function seconds_to_time_format($seconds = "0")
    {
        if ($seconds) {
            $hours = floor($seconds / 3600); // Calculate the number of hours
            $minutes = floor(($seconds % 3600) / 60); // Calculate the number of minutes
            $totalSeconds = $seconds % 60; // Calculate the number of seconds

            return sprintf("%02d:%02d:%02d", $hours, $minutes, $totalSeconds); // Format the time as HH:MM:SS
        } else {
            $duration = '00:00:00';
        }
        return $duration;
    }
}

// Human readable time
if (!function_exists('time_to_seconds')) {
    function time_to_seconds($time)
    {
        $time = explode(':', $time);
        $seconds = $time[0] * 3600;
        $seconds = $seconds + ($time[1] * 60);
        return $seconds = $seconds + $time[2];
    }
}

if (!function_exists('trimmer')) {
    function trimmer($text)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
            return 'n-a';
        return $text;
    }
}

if (!function_exists('lesson_progress')) {
    function lesson_progress($lesson_id = "", $user_id = "", $course_id = "")
    {
        $CI    = &get_instance();
        $CI->load->database();
        if ($user_id == "") {
            $user_id = $CI->session->userdata('user_id');
        }
        if ($course_id == "") {
            $course_id = $CI->db->get_where('lesson', array('id' => $lesson_id))->row('course_id');
        }

        $query = $CI->db->get_where('watch_histories', array('course_id' => $course_id, 'student_id' => $user_id));

        if ($query->num_rows() > 0) {
            $lesson_ids = json_decode($query->row('completed_lesson'), true);
            if (is_array($lesson_ids) && in_array($lesson_id, $lesson_ids)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
}
if (!function_exists('course_progress')) {
    function course_progress($course_id = "", $user_id = "", $return_type = "")
    {
        $CI    = &get_instance();
        $CI->load->database();
        if ($user_id == "") {
            $user_id = $CI->session->userdata('user_id');
        }

        $watch_history = $CI->crud_model->get_watch_histories($user_id, $course_id)->row_array();

        if ($return_type == "completed_lesson_ids") {
            return json_decode($watch_history['completed_lesson']);
        }
        if (is_array($watch_history) && $watch_history['course_progress'] > 0) {
            return $watch_history['course_progress'];
        } else {
            return 0;
        }
    }
}

// RANDOM NUMBER GENERATOR FOR ELSEWHERE
if (!function_exists('random')) {
    function random($length_of_string)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
}

// RANDOM NUMBER GENERATOR FOR ELSEWHERE
if (!function_exists('phpFileUploadErrors')) {
    function phpFileUploadErrors($error_code)
    {
        $phpFileUploadErrorsArray = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        return $phpFileUploadErrorsArray[$error_code];
    }
}


// course bundle subscription data
if (!function_exists('get_bundle_validity')) {
    function get_bundle_validity($bundle_id = "", $user_id = "")
    {
        $CI = &get_instance();
        $CI->load->database();
        if ($user_id == "") {
            $user_id = $CI->session->userdata('user_id');
        }
        $today = strtotime(date('d M Y'));

        $course_bundle = $CI->db->get_where('course_bundle', array('id' => $bundle_id))->row_array();

        $CI->db->limit(1);
        $CI->db->order_by('id', 'desc');
        $bundle_payment = $CI->db->get_where('bundle_payment', array('bundle_id' => $bundle_id, 'user_id' => $user_id));

        //convert day to seconds
        $subscription_limit_timestamp = $course_bundle['subscription_limit'] * 86400;

        if ($bundle_payment->num_rows() > 0) {
            $max_valid_date = $bundle_payment->row_array()['date_added'] + $subscription_limit_timestamp;
            if ($today <= $max_valid_date) {
                //validate
                return 'valid';
            } else {
                //expire
                return 'expire';
            }
        } else {
            return 'invalid';
        }
    }
}


if (!function_exists('get_lesson_type')) {
    function get_lesson_type($lesson_id = "")
    {
        $CI = &get_instance();
        $CI->load->database();
        $lesson = $CI->db->get_where('lesson', ['id' => $lesson_id]);
        if ($lesson->num_rows() > 0) {
            $lesson = $lesson->row_array();
            if ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'youtube') {
                return 'youtube_video_url';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'google_drive') {
                return 'google_drive_video_url';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'vimeo') {
                return 'vimeo_video_url';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'bunny') {
                return 'bunny_video_url';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'amazon') {
                return 'amazon_video_url';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'system') {
                return 'video_file';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'academy_cloud') {
                return 'academy_cloud';
            } elseif ($lesson['lesson_type'] == 'video' && $lesson['video_type'] == 'html5') {
                return 'html5_video_url';
            } elseif ($lesson['lesson_type'] == 'quiz') {
                return 'quiz';
            } elseif ($lesson['lesson_type'] == 'text') {
                return 'text';
            } elseif ($lesson['lesson_type'] == 'other' && $lesson['attachment_type'] == 'txt') {
                return 'text_file';
            } elseif ($lesson['lesson_type'] == 'other' && $lesson['attachment_type'] == 'pdf') {
                return 'pdf_file';
            } elseif ($lesson['lesson_type'] == 'other' && $lesson['attachment_type'] == 'doc') {
                return 'doc_file';
            } elseif ($lesson['lesson_type'] == 'other' && $lesson['attachment_type'] == 'img') {
                return 'image_file';
            } else {
                return 'iframe';
            }

            //'image_file' || 'doc_file' || 'pdf_file' || 'text_file' || 
        }
    }
}


// ------------------------------------------------------------------------
/* End of file common_helper.php */
/* Location: ./system/helpers/common.php */
