<?php
/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
*/

// // GETTING PAYTM KEYS
// $paytm_keys = get_settings('paytm_keys');
// $paytm_keys = json_decode($paytm_keys, true);

// define('PAYTM_ENVIRONMENT'      , 'PROD'); // PROD
// define('PAYTM_MERCHANT_KEY'     , $paytm_keys[0]['PAYTM_MERCHANT_KEY']); //Change this constant's value with Merchant key received from Paytm.
// define('PAYTM_MERCHANT_MID'     , $paytm_keys[0]['PAYTM_MERCHANT_MID']); //Change this constant's value with MID (Merchant ID) received from Paytm.
// define('PAYTM_MERCHANT_WEBSITE' , $paytm_keys[0]['PAYTM_MERCHANT_WEBSITE']); //Change this constant's value with Website name received from Paytm.


// $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
// $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
// if (PAYTM_ENVIRONMENT == 'PROD') {
// 	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
// 	$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
// }

// define('PAYTM_REFUND_URL', '');
// define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
// define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
// define('PAYTM_TXN_URL', $PAYTM_TXN_URL);



$payment_details = $this->session->userdata('payment_details');
$payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => 'paytm'])->row_array();
if($payment_details['is_instructor_payout_user_id'] > 0){
    $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
    $keys = json_decode($instructor_details['payment_keys'], true);
    $keys = $keys[$payment_gateway['identifier']];
}else{
    $keys = json_decode($payment_gateway['keys'], true);
}
$test_mode = $payment_gateway['enabled_test_mode'];
if($test_mode == 1){
    define('PAYTM_ENVIRONMENT', 'TEST'); // PROD or TEST
    $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
    $PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
} else {
    define('PAYTM_ENVIRONMENT', 'PROD'); // PROD or TEST
    $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
    $PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
}

define('PAYTM_MERCHANT_KEY', $keys['paytm_merchant_key']);
define('PAYTM_MERCHANT_MID', $keys['paytm_merchant_mid']);
define('PAYTM_MERCHANT_WEBSITE', $keys['paytm_merchant_website']);
define('PAYTM_REFUND_URL', '');
define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
?>
