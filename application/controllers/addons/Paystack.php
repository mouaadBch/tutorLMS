<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paystack extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      // Your own constructor code
      $this->load->database();
      $this->load->library('session');
      $this->load->model('addons/paystack_model');

      if(!$this->session->userdata('payment_details') || !$this->session->userdata('user_id')){
          $this->session->set_flashdata('error_message', site_phrase('payment_not_configured_yet'));
          redirect(site_url(), 'refresh');
      }
  }

}