<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date    : 20 April, 2020
*  Academy
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Liveclass extends CI_Controller{

    protected $unique_identifier = "live-class";
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        /*ADDON SPECIFIC MODELS*/
        $this->load->model('addons/Liveclass_model','liveclass_model');

        // CHECK IF THE ADDON IS ACTIVE OR NOT
        $this->check_addon_status();
    }

    // JOIN TO LIVE CLASS
    public function join($course_id = "") {
        $user_id = $this->session->userdata('user_id');
        // CHECK USER OR ADMIN LOGIN STATUS
        $this->is_logged_in();

        // check if course id is empty
        if (empty($course_id) || $this->crud_model->get_course_by_id($course_id)->num_rows() == 0) {
            $this->session->set_flashdata('error_message', get_phrase('invalid_course'));
            redirect(site_url('home/my_courses'), 'refresh');
        }

        // LOAD LIVE CLASS VIEW
        $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
        $page_data['course_details']      = $course_details;
        
        $page_data['live_class_details']  = $this->liveclass_model->get_live_class_details($course_id);
        $page_data['logged_user_details'] = $this->user_model->get_all_user($user_id)->row_array();


        //leave_url start
        if($this->crud_model->is_course_instructor($course_id, $user_id) || $this->session->userdata('admin_login')){
            if($this->session->userdata('admin_login')){
                $page_data['leave_url'] = site_url('admin/course_form/course_edit/'.$course_id.'?tab=live-class');
            }else{
                $page_data['leave_url'] = site_url('user/course_form/course_edit/'.$course_id.'?tab=live-class');
            }
            $page_data['is_host'] = true;
        }else{
            $page_data['leave_url'] = site_url('home/lesson/'.slugify($course_details['title']).'/'.$course_id.'?tab=live-class-content');
            $page_data['is_host'] = false;
        }
        //leave_url end



        $this->load->view('lessons/zoom_live_class', $page_data);
    }

    // CHECK USER LOGGID IN OR NOT
    public function is_logged_in() {
        if ($this->session->userdata('user_login') != 1 && $this->session->userdata('admin_login') != 1){
            redirect('home', 'refresh');
        }
    }
    // CHECK WHETHER USER BELONGS TO THIS COURSE
    public function check_purchase($course_id = "") {
        $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
        if ($this->session->userdata('role_id') != 1 && $course_details['user_id'] != $this->session->userdata('user_id')) {
            if (!is_purchased($course_id)) {
                redirect(site_url('home/course/'.slugify($course_details['title']).'/'.$course_details['id']), 'refresh');
            }else{
                return true;
            }
        }else {
            return true;
        }
    }



    // CHECK IF THE ADDON IS ACTIVE OR NOT. IF NOT REDIRECT TO DASHBOARD
    public function check_addon_status() {
        $checker = array('unique_identifier' => $this->unique_identifier);
        $this->db->where($checker);
        $addon_details = $this->db->get('addons')->row_array();
        if ($addon_details['status']) {
            return true;
        }else{
            redirect(site_url(), 'refresh');
        }
    }

    function settings($param1 = ''){
        $user_id = $this->session->userdata('user_id');
        $user_details = $this->user_model->get_all_user($user_id)->row_array();

        if($user_details['is_instructor'] == 1 || $user_details['role_id'] == 1){
            if($param1 == 'update'){
                $this->liveclass_model->update_live_class_settings($user_id);
                $this->session->set_flashdata('flash_message', get_phrase('Zoom settings has been successfully configured'));
                redirect(site_url('addons/liveclass/settings'), 'refresh');
            }


            $page_data['zoom_live_class_settings'] = $this->db->where('user_id', $user_id)->get('zoom_live_class_settings');
            $page_data['page_title'] = get_phrase('Zoom live class settings');
            $page_data['page_name'] = 'zoom_live_class_settings';
            $this->load->view('backend/index', $page_data);
        }else{
            $this->session->set_flashdata('flash_message', get_phrase('Access denied. Please login as an instructor'));
            redirect(site_url('login'), 'refresh');
        }
    }
}
