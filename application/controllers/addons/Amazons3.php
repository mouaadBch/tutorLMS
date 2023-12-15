<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date    : 13 July, 2020
*  Academy
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Amazons3 extends CI_Controller
{

    protected $unique_identifier = "amazon-s3";
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        /*ADDON SPECIFIC MODELS*/
        $this->load->model('addons/Amazons3_model','amazons3_model');

        // CHECK IF THE ADDON IS ACTIVE OR NOT
        $this->check_addon_status();
    }

    // AMAZON S3 SETTINGS
    public function settings($param1 = "") {
      if ($this->session->userdata('admin_login') != true) {
        redirect(site_url('login'), 'refresh');
      }
      if ($param1 == 'update') {
          $this->amazons3_model->update_amazons3_settings();
          $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
          redirect(site_url('addons/amazons3/settings'), 'refresh');
      }

      $page_data['page_name'] = 's3_settings';
      $page_data['page_title'] = get_phrase('s3_settings');
      $this->load->view('backend/index.php', $page_data);
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
}
