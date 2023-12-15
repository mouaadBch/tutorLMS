<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paystack_model extends CI_Model {

	function __construct()
	{
	    parent::__construct();
 	}


 	function get_paystack_supported_currencies()
	  {
	    $this->db->where('paystack_supported', 1);
	    return $this->db->get('currency')->result_array();
	  }

	public function check_paystack_payment($identifier = "") {
	  //start common code of all payment gateway
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
      
      //ended common code of all payment gateway
	  if($test_mode == 1){
          $secret_key = $keys['secret_test_key'];
      } else {
          $secret_key = $keys['secret_live_key'];
      }
	  $curl = curl_init();
  
	  curl_setopt_array($curl, array(
	    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$_GET['reference'],
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => "GET",
	    CURLOPT_HTTPHEADER => array(
	      "Authorization: Bearer ".$secret_key,
	      "Cache-Control: no-cache",
	    ),
	  ));
	  
	  $response = curl_exec($curl);
	  $err = curl_error($curl);
	  curl_close($curl);
	  
	  if ($err) {
	    return false;
	  } else {
	    return true;
	  }
	}
}