<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');

        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        if (isset($_GET['i']) && !empty($_GET['i'])) {
            $this->payment_model->checkLogin($_GET['i']);
        }

        if (!$this->session->userdata('payment_details') || !$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error_message', site_phrase('payment_not_configured_yet'));
            redirect(site_url(), 'refresh');
        }
    }

    function index()
    {
        $page_data['page_title'] = get_phrase('payment');
        $this->load->view('payment-global/index.php', $page_data);
    }


    function success_course_payment($payment_method = "")
    {
        //STARTED payment model and functions are dynamic here
        $response = false;
        $payer_user_id = $this->session->userdata('user_id');
        $enrol_user_id = $payer_user_id;
        $payment_details = $this->session->userdata('payment_details');
        $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $payment_method])->row_array();
        $model_name = strtolower($payment_gateway['model_name']);
        if ($payment_gateway['is_addon'] == 1 && $model_name != null) {
            $this->load->model('addons/' . strtolower($payment_gateway['model_name']));
        } elseif ($model_name == 'cmi_model') {
            $this->load->model(strtolower($payment_gateway['model_name']));
        }

        if ($model_name != null) {
            $payment_check_function = 'check_' . $payment_method . '_payment';
            $response = $this->$model_name->$payment_check_function($payment_method, 'course');
        }
        //ENDED payment model and functions are dynamic here
        if ($response === true) {
            //if course is a gift purchase
            if ($payment_details['gift_to_user_id'] > 0) {
                $enrol_user_id = $payment_details['gift_to_user_id'];
                $this->crud_model->enrol_student($enrol_user_id, $payer_user_id);
                $this->email_model->course_gift_notification($enrol_user_id, $payer_user_id, $payment_method, $payment_details['total_payable_amount']);
            } else {

                $this->crud_model->enrol_student($enrol_user_id);
                $this->email_model->course_purchase_notification($enrol_user_id, $payment_method, $payment_details['total_payable_amount']);
            }
            $this->crud_model->course_purchase($payer_user_id, $payment_method, $payment_details['total_payable_amount']);

            $this->session->unset_userdata('gift_to_user_id');
            $this->session->set_userdata('cart_items', array());
            $this->session->set_userdata('payment_details', '');
            $this->session->set_userdata('applied_coupon', '');

            $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));

            //special treatment for cmi
            if ($payment_method == 'cmi') {
                $page_data['page_name'] = "cmi-okFail";
                $page_data['page_title'] = site_phrase('cmi-okFail');
                $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
            } else {
                redirect('home/my_courses', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));

            //special treatment for cmi
            if ($payment_method == 'cmi') {
                $page_data['page_name'] = "cmi-okFail";
                $page_data['page_title'] = site_phrase('cmi-okFail');
                $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
            } else {
                redirect('home/shopping_cart', 'refresh');
            }
        }
    }

    function success_instructor_payment($payment_method = "")
    {
        //STARTED payment model and functions are dynamic here
        $user_id = $this->session->userdata('user_id');
        $payment_details = $this->session->userdata('payment_details');
        if ($payment_method == 'offline') {
            $response = true;
        } else {
            $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $payment_method])->row_array();
            $model_name = strtolower($payment_gateway['model_name']);
            if ($payment_gateway['is_addon'] == 1 && $model_name != null) {
                $this->load->model('addons/' . strtolower($payment_gateway['model_name']));
            }
            if ($model_name != null) {
                $payment_check_function = 'check_' . $payment_method . '_payment';
                $response = $this->$model_name->$payment_check_function($payment_method, 'instructor');
            } else {
                $response = true;
            }
        }
        //ENDED payment model and functions are dynamic here

        if ($response) {
            //$this->crud_model->update_payout_status($payment_details['payout_id'], $payment_method);
            $this->crud_model->update_payout_status($payment_details['items'][0]['payout_id'], $payment_method);
            //$method = ($payment_method == 'offline' ? 'virement bancaire' : $payment_method);
            //$message = "<p>Bonjour " . $payment_details['items'][0]['full_name_instructor'] . ", Nous vous informons que votre demande de retrait lancée le " . date('D, d-M-Y') . " est bien accordée et le paiement par " . $method . " a été émis vers votre compte. <br> cordialement. </p>";
            //$this->email_model->send_email_with_text($payment_details['is_instructor_payout_user_id'],  "Paiement effectué : " . $method . " (" . $payment_details['items'][0]['price'] . " MAD) ", $message);
            $this->session->set_flashdata('flash_message', get_phrase('payout_updated_successfully'));
        } else {
            $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
        }

        redirect(site_url('admin/instructor_payout'), 'refresh');
    }
















    function create_stripe_payment($success_url = "", $cancel_url = "", $public_key = "", $secret_key = "")
    {
        $identifier = 'stripe';
        $payment_details = $this->session->userdata('payment_details');
        $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $identifier])->row_array();



        //start common code of all payment gateway
        if ($payment_details['is_instructor_payout_user_id'] > 0) {
            $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
            $keys = json_decode($instructor_details['payment_keys'], true);
            $keys = $keys[$payment_gateway['identifier']];
        } else {
            $keys = json_decode($payment_gateway['keys'], true);
        }
        $test_mode = $payment_gateway['enabled_test_mode'];

        if ($test_mode == 1) {
            $public_key = $keys['public_key'];
            $secret_key = $keys['secret_key'];
        } else {
            $public_key = $keys['public_live_key'];
            $secret_key = $keys['secret_live_key'];
        }
        //ended common code of all payment gateway

        // Convert product price to cent
        $stripeAmount = round($payment_details['total_payable_amount'] * 100, 2);

        define('STRIPE_API_KEY', $secret_key);
        define('STRIPE_PUBLISHABLE_KEY', $public_key);
        define('STRIPE_SUCCESS_URL', $payment_details['success_url']);
        define('STRIPE_CANCEL_URL', $payment_details['cancel_url']);

        // Include Stripe PHP library
        require_once APPPATH . 'libraries/Stripe/init.php';

        // Set API key
        \Stripe\Stripe::setApiKey(STRIPE_API_KEY);

        $response = array(
            'status' => 0,
            'error' => array(
                'message' => 'Invalid Request!'
            )
        );

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = file_get_contents('php://input');
            $request = json_decode($input);
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode($response);
            exit;
        }

        // ['name' => 'Course payment']

        if (!empty($request->checkoutSession)) {
            // Create new Checkout Session for the order
            try {
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'product_data' => ['name' => $payment_details['payment_title']],
                            'unit_amount' => $stripeAmount,
                            'currency' => $payment_gateway['currency'],
                        ],
                        'quantity' => 1
                    ]],
                    'mode' => 'payment',
                    'success_url' => STRIPE_SUCCESS_URL . '/' . $identifier . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => STRIPE_CANCEL_URL,
                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $session) {
                $response = array(
                    'status' => 1,
                    'message' => 'Checkout Session created successfully!',
                    'sessionId' => $session['id']
                );
            } else {
                $response = array(
                    'status' => 0,
                    'error' => array(
                        'message' => 'Checkout Session creation failed! ' . $api_error
                    )
                );
            }
        }

        // Return response
        echo json_encode($response);
    }


    function cmi_payment()
    {
        $identifier = 'cmi';
        $user_id = $this->session->userdata('user_id');
        $payment_details = $this->session->userdata('payment_details');
        $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $identifier])->row_array();
        $user_details = $this->db->where('id', $user_id)->get('users')->row();
        $orderId = strval(time()) . "CRSP" . strval($payment_details['total_payable_amount']);

        $test_mode = 0;


        //checking payment type and getting keys
        if ($payment_details['is_instructor_payout_user_id'] > 0) {
            $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
            $keys = json_decode($instructor_details['payment_keys'], true);
            $keys = $keys[$payment_gateway['identifier']];

            $orderId = strval(time()) . "INSP" . strval($payment_details['total_payable_amount']);
        } else {

            $keys = json_decode($payment_gateway['keys'], true);
            $test_mode = $payment_gateway['enabled_test_mode'];
        }


        if ($test_mode == 1) {
            $storekey = $keys['test_storekey'];
            $clientid = $keys['test_clientid'];
        } else {
            $storekey = $keys['storekey'];
            $clientid = $keys['clientid'];
        }



        //callback-url for cmi
        $CallbackURL = site_url('payment/cmiCallbackURL');

        $coupon_code = $this->session->userdata('applied_coupon');
        $cmiSessionData = array($user_id, $payment_details, $coupon_code);
        $cmiSessionData = json_encode($cmiSessionData);
        $cmiSessionData = base64_encode($cmiSessionData);
        /* code mouaad */
        $this->db->insert('cmi_payment', [
            'oid' => $orderId,
            'user_id' => $user_details->id,
            'amount' => (string)$payment_details['total_payable_amount'],
            'created_at' => time(),
            'cmiSessionData' => $cmiSessionData,
            'course_referee' => $this->session->userdata('course_referee'),
            'course_reffer_id' => $this->session->userdata('course_reffer_id'),
        ]);

        /*  $array = array($user_id, $payment_details, $coupon_code);

        $queryString = http_build_query(array('i' => $array));  */

        require_once(APPPATH . "libraries/cmi-payment-php-1.0.0/init.php");
        $client = new \CMI\CmiClient([
            'storekey' => $storekey, // STOREKEY
            'clientid' => $clientid, // CLIENTID
            'oid' => $orderId, // COMMAND ID IT MUST BE UNIQUE
            'shopurl' => $payment_details['cancel_url'], // SHOP URL FOR REDIRECTION
            'okUrl' => $payment_details['success_url'] . '/' . $payment_gateway['identifier'] . '?i=' . $orderId, // REDIRECTION AFTER SUCCEFFUL PAYMENT
            'failUrl' => $payment_details['success_url'] . '/' . $payment_gateway['identifier'] . '?i=' . $orderId, // REDIRECTION AFTER FAILED PAYMENT
            'email' => $user_details->email, // YOUR EMAIL APPEAR IN CMI PLATEFORM
            'BillToName' => $user_details->first_name . ' ' . $user_details->last_name, // YOUR NAME APPEAR IN CMI PLATEFORM
            // 'BillToCompany' => 'company name', // YOUR COMPANY NAME APPEAR IN CMI PLATEFORM
            // 'BillToStreet12' => '100 rue adress', // YOUR ADDRESS APPEAR IN CMI PLATEFORM NOT REQUIRED
            // 'BillToCity' => 'casablanca', // YOUR CITY APPEAR IN CMI PLATEFORM NOT REQUIRED
            // 'BillToStateProv' => 'Maarif Casablanca', // YOUR STATE APPEAR IN CMI PLATEFORM NOT REQUIRED
            // 'BillToPostalCode' => '20230', // YOUR POSTAL CODE APPEAR IN CMI PLATEFORM NOT REQUIRED
            // 'BillToCountry' => '504', // YOUR COUNTRY APPEAR IN CMI PLATEFORM NOT REQUIRED (504=MA)
            // 'tel' => '0021201020304', // YOUR PHONE APPEAR IN CMI PLATEFORM NOT REQUIRED
            'amount' => (string)$payment_details['total_payable_amount'], // RETRIEVE AMOUNT WITH METHOD POST
            'currency' => '504',
            'CallbackURL' => $CallbackURL . '?i=' . $orderId,
            'AutoRedirect' => "true",
        ]);

        // $client->AutoRedirect = 'true';
        $client->redirect_post();
    }

    function cmiCallbackURL()
    {
        // please check this !!!!!!!!!!!!!!!!!!!!!
        //$payment_details = $this->session->userdata('payment_details');
        $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => 'cmi'])->row_array();

        $test_mode = 0;

        //checking payment type and getting keys
        /*  if ($payment_details['is_instructor_payout_user_id'] > 0) {
            $instructor_details = $this->user_model->get_all_user($payment_details['is_instructor_payout_user_id'])->row_array();
            $keys = json_decode($instructor_details['payment_keys'], true);
            $keys = $keys[$payment_gateway['cmi']];
        } else { */
        $keys = json_decode($payment_gateway['keys'], true);
        $test_mode = $payment_gateway['enabled_test_mode'];
        /* } */


        if ($test_mode == 1) {
            $storekey = $keys['test_storekey'];
        } else {
            $storekey = $keys['storekey'];
        }





        //cmi code            
        $postParams = array();
        foreach ($_POST as $key => $value) {
            array_push($postParams, $key);
        }


        natcasesort($postParams);
        $hach = "";
        $hashval = "";
        foreach ($postParams as $param) {
            $paramValue = html_entity_decode(preg_replace("/\n$/", "", $_POST[$param]), ENT_QUOTES, 'UTF-8');

            $hach = $hach . "(!" . $param . "!:!" . $_POST[$param] . "!)";
            $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

            $lowerParam = strtolower($param);
            if ($lowerParam != "hash" && $lowerParam != "encoding") {
                $hashval = $hashval . $escapedParamValue . "|";
            }
        }


        $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storekey));
        $hashval = $hashval . $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);
        $actualHash = base64_encode(pack('H*', $calculatedHashValue));

        $retrievedHash = $_POST["HASH"];
        /*
        During the callback request, the merchant’s web site is supposed to do the following:
            •   Generate a hash code with the same data posted by the CMI platform in the callback request. Then compare this calculated hash with the hash sent by the CMI platform in the callback request. If they are identical, proceed to the next check.
            •   Look, in the orders’ DB of the merchant’s web site, for the record identified by the value of the "oid" parameter sent by the CMI platform in the callback request.
            •   Check if the amount of the order recorded in the orders’ DB of the merchant’s web site is equal to the amount sent by the CMI in the callback request via the "amount" parameter.
            •   Check the "ProcReturnCode" parameter value sent by the CMI in the callback request:
                o   If ProcReturnCode = 00, this is an accepted transaction.
                       So it is necessary to update the status of the order in the orders’ DB of the merchant’s web site (status = Paid).
                       Answer the CMI callback request with: 
                        •   "ACTION=POSTAUTH": in order to debit the client automatically.
                        •   "APPROVED": in order to not debit the client automatically. In this case, the merchant needs to manage capture or void manually via CMI Merchant Center interface.
                o   If the ProcReturnCode <> 00 or if ProcReturnCode parameter does not exist in the callback request, it is a payment authorization failure.
                       In this case, do not change the status of the order in the BDD orders of the merchant’s web site.
                       The response to return to the CMI callback request is "APPROVED" (acknowledgment).
            •   If a technical problem occurs in one of the previous steps, answer the CMI callback request with "FAILURE". In this case, the merchant needs to manage capture or void manually via CMI Merchant Center interface.
        */
        if ($retrievedHash == $actualHash) {
            if ($_POST["ProcReturnCode"] == "00") {
                echo "ACTION=POSTAUTH";
            } else {
                echo "APPROVED";
            }
        } else {
            echo "FAILURE";
        }
        /*
        The callback request sent by CMI platform to the merchant’s web site in server-to-server mode, reminds as well of the cases of successful transactions as cases of rejections. 
        So it may well be that the merchant’s web site receives, for the same transaction (same order number), rejection returns that will be followed by a return of transaction acceptance. 
        This means that the client has failed attempts before it succeeds. 
        It is the parameter ProcReturnCode which makes possible to distinguish between the callback request of a successful payment authorization (ProcReturnCode = 00) and the callback request of a failed payment (ProcReturnCode! = 00 or nonexistent).
        */
    }
}
