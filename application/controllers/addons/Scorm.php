<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scorm extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('addons/scorm_model');
        $this->load->library('session');
        
        if (!$this->session->userdata('user_id')) {
            redirect(site_url('home/login'), 'refresh');
        }
    }
    function add_curriculum($course_id = ""){
        $user_id = $this->session->userdata('user_id');
        if (class_exists('ZipArchive')) {
            if($this->session->userdata('admin_login') == 1 || $this->crud_model->get_course_by_id($course_id)->row('user_id') == $user_id){
                echo $this->scorm_model->add_curriculum($course_id);
            }else{
                echo get_phrase('no_access');
            }
        }else{
            echo get_phrase('your_server_is_unable_to_extract_the_zip_file').'. '.get_phrase('please_enable_the_zip_extension_on_your_server').', '.get_phrase('then_try_again');
        }
    }

    function remove_curriculum($course_id = ""){
        $user_id = $this->session->userdata('user_id');
        if($this->session->userdata('admin_login') == 1 || $this->crud_model->get_course_by_id($course_id)->row('user_id') == $user_id){
             $this->scorm_model->remove_curriculum($course_id);
        }
        $this->session->set_flashdata('flash_message', get_phrase('scorm_course_removed_successfully'));
        redirect(site_url($this->session->userdata('role').'/course_form/course_edit/'.$course_id), 'refresh');
    }

}