<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date    : 09 July, 2020
*  Academy
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Paytm extends CI_Controller
{

    protected $unique_identifier = "paytm";
    // constructor
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

        if(isset($_GET['i']) && !empty($_GET['i']) && !$this->session->userdata('user_id')){
            $this->payment_model->checkLogin($_GET['i']);
        }

        if(!$this->session->userdata('payment_details') || !$this->session->userdata('user_id')){
            $this->session->set_flashdata('error_message', site_phrase('payment_not_configured_yet'));
            redirect(site_url(), 'refresh');
        }
    }

    public function payThroughPaytm($identifier = "")
    {
        require_once(APPPATH . "/libraries/Paytm/config_paytm.php");
        require_once(APPPATH . "/libraries/Paytm/encdec_paytm.php");

        //start common code of all payment gateway
        $user_id = $this->session->userdata('user_id');
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


        //All payment data encoding to user if logged out the application after payment thogh Paytm
        $payment_info = array($user_id, $payment_details, $this->session->userdata('applied_coupon'));
        $payment_info = json_encode($payment_info);
        $payment_info = base64_encode($payment_info);
        $payment_info = str_replace("=","",$payment_info);

        // Create an array having all required parameters for creating checksum.
        $paramList = array();
        $paramList["MID"] = PAYTM_MERCHANT_MID;
        $paramList["ORDER_ID"] = "ORDS" . rand(10000, 99999999);
        $paramList["CUST_ID"] = "CUST" . $user_id;;
        $paramList["INDUSTRY_TYPE_ID"] = $keys["industry_type_id"];
        $paramList["CHANNEL_ID"] = $keys["channel_id"];
        $paramList["TXN_AMOUNT"] = $payment_details['total_payable_amount'];
        $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
        $paramList["CALLBACK_URL"] = $payment_details['success_url'].'/'.$identifier.'?i='.$payment_info;

        //Here checksum string will return by getChecksumFromArray() function.
        $checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

        $page_data['paramList'] = $paramList;
        $page_data['checkSum'] = $checkSum;
        $this->load->view('payment-global/paytm/paytm_merchant_checkout', $page_data);
    }

}
