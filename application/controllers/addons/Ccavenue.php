<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ccavenue extends CI_Controller {

  public function __construct()
  {
      parent::__construct();
      // Your own constructor code
      $this->load->database();
      $this->load->library('session');
      $this->load->model('addons/ccavenue_model');

      if(!$this->session->userdata('payment_details') || !$this->session->userdata('user_id')){
          $this->session->set_flashdata('error_message', site_phrase('payment_not_configured_yet'));
          redirect(site_url(), 'refresh');
      }
  }

  public function ccavenue_request($identifier = "")
  {
    $user_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
    $payment_details = $this->session->userdata('payment_details');
    $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $identifier])->row_array();
    if($payment_details['is_instructor_payout_user_id'] > 0){
      $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
      $keys = json_decode($instructor_details['payment_keys'], true);
      $keys = $keys[$payment_gateway['identifier']];
    }else{
      $keys = json_decode($payment_gateway['keys'], true);
    }
    $test_mode = $payment_gateway['enabled_test_mode'];

    if($test_mode == 1){
      $form_action = "https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
    }else{
      $form_action = "https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
    }

    $merchant_data = '';
    $data['merchant_id'] = $keys['ccavenue_merchant_id'];
    $data['language'] = $this->input->post('language');
    $data['amount'] = $payment_details['total_payable_amount'];
    $data['currency'] = $payment_gateway['currency'];
    $data['redirect_url'] = $payment_details['success_url'].'/'.$identifier;
    $data['cancel_url'] = $payment_details['cancel_url'];
    $data['billing_name'] = $user_details['first_name'].' '.$user_details['last_name'];
    $data['billing_address'] = $this->input->post('billing_address');
    $data['billing_country'] = $this->input->post('billing_country');
    $data['billing_city'] = $this->input->post('billing_city');
    $data['billing_state'] = $this->input->post('billing_state');
    $data['billing_zip'] = $this->input->post('billing_zip');
    $data['billing_tel'] = $this->input->post('billing_tel');
    $data['billing_email'] = $user_details['email'];
    $data['order_id'] = random(10);
    foreach ($data as $key => $value){
      $merchant_data.=$key.'='.$value.'&';
    }
    $encrypted_data=$this->ccavenue_model->encrypt($merchant_data,$keys['ccavenue_working_key']);

    $page_data['keys'] = $keys;
    $page_data['form_action'] = $form_action;
    $page_data['encrypted_data'] = $encrypted_data;
    $this->load->view('payment-global/ccavenue/ccavRequestHandler', $page_data);
  }

}