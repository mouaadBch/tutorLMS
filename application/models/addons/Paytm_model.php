<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paytm_model extends CI_Model {

	function __construct()
	{
	    parent::__construct();

	    $this->load->database();
        $this->load->library('session');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
 	}

 	public function check_paytm_payment()
    {
    	require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
        $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

        if ($isValidChecksum == "TRUE") {
            if ($_POST["STATUS"] == "TXN_SUCCESS") {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }


 	function checkLogin($payment_info = ""){
        if($this->session->userdata('user_id') > 0)
        {
            return $this->session->userdata('payment_details');
        }else{
            $cart_items = array();
            $payment_info = base64_decode($payment_info);
            $payment_info = json_decode($payment_info, true);
            $user_id = $payment_info[0];
            $payment_details = $payment_info[1];
            // Checking login credential for admin
            $query = $this->db->get_where('users', array('id' => $user_id));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->session->set_userdata('custom_session_limit', (time()+604800));
                $this->session->set_userdata('user_id', $row->id);
                $this->session->set_userdata('role_id', $row->role_id);
                $this->session->set_userdata('role', get_user_role('user_role', $row->id));
                $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
                $this->session->set_userdata('is_instructor', $row->is_instructor);
                $this->session->set_userdata('user_login', '1');
            }

            if($payment_details['is_instructor_payout_user_id'] == false){
                foreach($payment_details['items'] as $item){
                    if(isset($item['id']) && $item['id'] > 0){
                        $cart_items[] = $item['id'];
                    }
                }
            }
            $this->session->set_userdata('cart_items', $cart_items);
            $this->session->set_userdata('applied_coupon', $payment_info[2]);
            $this->session->set_userdata('payment_details', $payment_details);
            $this->session->set_userdata('total_price_of_checking_out', $payment_details['total_payable_amount']);

            return $payment_details;
        }
    }
	
}