<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Affiliate_course extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('addons/affiliate_course_model');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index()
    {
        echo 'Hello World!';
    }


    public function  affiliate_course_history()
    {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }
        $is_affiliator = $this->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
        if (addon_status('affiliate_course') && $is_affiliator == 1) {

            $page_data['page_name']  = "affiliate_course_history";
            $page_data['page_title'] = site_phrase('affiliate_course_history');
            $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
        } else {
            redirect(site_url('home'), 'refresh');
        }
    }

    public function cancel_user_pending_course()
    {
        $is_affiliator = $this->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
        if (addon_status('affiliate_course') && $is_affiliator == 1) {
            $this->affiliate_course_model->delete_course_withdrawl_pending_request($_GET['userid']);
            $get_user = $this->affiliate_course_model->get_userby_id($this->session->userdata('user_id'));
            $admin_details = $this->affiliate_course_model->get_admin_details();
            $this->email_model->send_email_to_admin_when_withdrawl_pending_request_cancle($admin_details['email'], $admin_details['first_name'], $get_user['first_name']);

            redirect(site_url('addons/affiliate_course/affiliate_course_history'), 'refresh');
        } else {
            redirect(site_url('home'), 'refresh');
        }
    }


    public function withdrawl_request_for_course_amount()
    {
        $is_affiliator = $this->affiliate_course_model->is_affilator($this->session->userdata('user_id'));
        if (addon_status('affiliate_course')  && $is_affiliator == 1) {

            // total earned amount 
            $user_id = $this->session->userdata('user_id');
            $course_affiliation_tableinfo = $this->affiliate_course_model->get_affiliate_course_table_info_by_user($user_id);
            $count = 0;


            if ($course_affiliation_tableinfo->num_rows() > 0) {
                foreach ($course_affiliation_tableinfo->result_array() as $each_history) {
                    $count = $count + $each_history['amount'];
                }
            }
            // total withdrawl amount

            $amount = html_escape($this->input->post('withdrawl_reff'));
            $w = $this->affiliate_course_model->get_withdrawl_request_info_for_referral_course_amount($user_id);
            $total_withdraw_amount = 0;

            if ($w->num_rows() > 0) {
                foreach ($w->result_array() as $withdrale_history) {
                    $total_withdraw_amount = $total_withdraw_amount + $withdrale_history['amount'];
                }
            }



            // calculation 
            $valid_money = $count - $total_withdraw_amount;


            if ($amount <= $valid_money) {

                $type = "course";
                $data['user_id']  = $user_id;
                $data['amount'] = $amount;
                $data['type'] = $type;
                $data['date']  = strtotime(date("Y-m-d H:i:s"));

                $check_pending = $this->affiliate_course_model->get_withdrawl_pending_request_info_for_course($data['user_id']);

                if ($check_pending->num_rows() > 0) {
                    $this->session->set_flashdata('error_message', get_phrase('You already have a pending request'));
                    redirect(site_url('addons/affiliate_course/affiliate_course_history'), 'refresh');
                } else {
                    $this->db->insert('course_affiliation_payment', $data);
                    $this->session->set_flashdata('flash_message', get_phrase('Withdral request has been sent to admin'));
                    $get_user = $this->affiliate_course_model->get_userby_id($this->session->userdata('user_id'));
                    $this->email_model->send_email_when_make_withdrawl_request($get_user['email'], $get_user['first_name'], $data['amount']);
                    $admin_details = $this->affiliate_course_model->get_admin_details();
                    $this->email_model->send_email_to_admin_when_withdrawl_request_made_by_affiliator($admin_details['email'], $admin_details['first_name'], $get_user['first_name'], $data['amount']);

                    redirect(site_url('addons/affiliate_course/affiliate_course_history'), 'refresh');
                }
            } else {
                $this->session->set_flashdata('erroe_message', get_phrase('write a valid number'));
                redirect(site_url('addons/affiliate_course/affiliate_course_history'), 'refresh');
            }
        } else {
            redirect(site_url('home'), 'refresh');
        }
    }



    public function become_an_affiliator($param1 = "", $param2 = "")
    {

        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('login'), 'refresh');
        }

        if ($param1 == "download") {
            $this->load->helper('download');
            $fileinfo = $this->affiliate_course_model->get__affiliator_status_table_info_by_user_id($param2);

            $file = 'uploads/document/' . $fileinfo['document'];
            force_download($file, NULL);
        }


        // CHEKING IF A FORM HAS BEEN SUBMITTED FOR REGISTERING AN INSTRUCTOR
        if (isset($_POST) && !empty($_POST)) {
            $this->affiliate_course_model->post_affiliator_application();
        }

        // CHECK USER AVAILABILITY
        $user_details = $this->user_model->get_all_user($this->session->userdata('user_id'));
        $page_data['page_name'] = 'become_an_affiliator';
        $page_data['page_title'] = get_phrase('become_an_affiliator');
        if ($user_details->num_rows() > 0) {
            $page_data['user_details'] = $user_details->row_array();
        } else {
            $this->session->set_flashdata('error_message', get_phrase('user_not_found'));
            $this->load->view('backend/index', $page_data);
        }
        $this->load->view('backend/index', $page_data);
    }


    public function active_affiliator($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != true) {
            redirect(site_url('login'), 'refresh');
        }
        if ($param1 == "download") {
            $this->load->helper('download');
            $fileinfo = $this->affiliate_course_model->get__affiliator_status_table_info_by_user_id($param2);

            $file = 'uploads/document/' . $fileinfo['document'];
            force_download($file, NULL);
        }

        if ($param1 == "suspended") {
            $data['status'] = 2;
            $this->db->where('user_id', $param2);
            $this->db->update('affiliator_status', $data);
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_suspend_an_affiliator_request($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        } elseif ($param1 == "delete") {
            $this->db->where('user_id', $param2);
            $this->db->delete('affiliator_status');
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_delete_an_affiliator_request($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        }

        $page_data['page_name'] = 'active_affiliator';
        $page_data['page_title'] = get_phrase('Active_affiliators');
        $page_data['active_affiliator'] = $this->affiliate_course_model->get_all_data_of_affiliator_status_table();
        $page_data['pending_affiliator'] = $this->affiliate_course_model->get_pending_affiliator_application();
        $page_data['suspend_affiliator'] = $this->affiliate_course_model->get_suspend_affiliator_application();
        $this->load->view('backend/index', $page_data);
    }

    public function suspend_affiliator($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != true) {
            redirect(site_url('login'), 'refresh');
        }

        if ($param1 == "download") {
            $this->load->helper('download');
            $fileinfo = $this->affiliate_course_model->get__affiliator_status_table_info_by_user_id($param2);

            $file = 'uploads/document/' . $fileinfo['document'];
            force_download($file, NULL);
        }

        if ($param1 == "active") {
            $data['status'] = 1;
            $this->db->where('user_id', $param2);
            $this->db->update('affiliator_status', $data);
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_reactove_an_affiliator_request($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        } elseif ($param1 == "delete") {
            $this->db->where('user_id', $param2);
            $this->db->delete('affiliator_status');
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_delete_an_affiliator_request($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        }

        $page_data['page_name'] = 'suspend_affiliator';
        $page_data['page_title'] = get_phrase('suspended_affiliators');
        $page_data['active_affiliator'] = $this->affiliate_course_model->get_all_data_of_affiliator_status_table();
        $page_data['pending_affiliator'] = $this->affiliate_course_model->get_pending_affiliator_application();
        $page_data['suspend_affiliator'] = $this->affiliate_course_model->get_suspend_affiliator_application();
        $this->load->view('backend/index', $page_data);
    }

    function download_csv()
    {
        $file = "affiliators-course-history.csv";
        $histories = $this->db->get_where('course_affiliation')->result_array();

        $csv_content = get_phrase('date') . ', ' . get_phrase('affiliators') . ', ' . get_phrase('course') . ', ' . get_phrase('amount') . ', ' . get_phrase('buyer');

        foreach ($histories as $history) {
            $csv_content .= "\n";

            $affiliator = $this->user_model->get_all_user($history['referee_id'])->row_array();
            $buyer = $this->user_model->get_all_user($history['buyer_id'])->row_array();
            $course_title = $this->crud_model->get_courses($history['course_id'])->row('title');
            $csv_content .= date('d M Y', $history['date_added']) . ', ' . $affiliator['first_name'] . ' ' . $affiliator['last_name'] . ', ' . $course_title . ', ' . currency($history['amount']) . ', ' . $buyer['first_name'] . ' ' . $buyer['last_name'];
        }
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $csv_content);
        fclose($txt);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $file);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header("Content-type: text/csv");
        readfile($file);
    }


    public function course_affiliation_history($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }
        $selected_user = "";
        if ($param1 != '') {
            if (isset($_GET['dropdown_user'])) {
                $selected_user = $_GET['dropdown_user'];
            }
            $date_range = $this->input->get('date_range');
            $date_range = explode(' - ', $date_range);
            $page_data['timestamp_start'] = strtotime($date_range[0]);
            $page_data['timestamp_end'] = strtotime($date_range[1]) + 86400;
        } else {
            if (isset($_GET['dropdown_user'])) {
                $selected_user = $_GET['dropdown_user'];
            }
            $page_data['timestamp_start'] = strtotime(date('m/01/Y'));
            $page_data['timestamp_end'] = strtotime(date('m/t/Y')) + 86400;
        }

        $all_affiliator_from_affliate_status_table = $this->affiliate_course_model->get_all_active_and_suspend_affiliators();

        $page_data['all_affiliator_id'] = $all_affiliator_from_affliate_status_table;


        $page_data['page_name'] = 'course_affiliation_history';
        $page_data['page_title'] = get_phrase('course_affiliation_history');

        $page_data['course_affiliation_table'] = $this->affiliate_course_model->get_course_completed_affiliate_history_by_date_range($page_data['timestamp_start'], $page_data['timestamp_end'], $selected_user);


        $this->load->view('backend/index', $page_data);
    }

    public function pending_affiliator($param1 = '', $param2 = '')
    {
        // param1 is the status and param2 is the application id
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }

        if ($param1 == "download") {
            $this->load->helper('download');
            $fileinfo = $this->affiliate_course_model->get__affiliator_status_table_info_by_user_id($param2);

            $file = 'uploads/document/' . $fileinfo['document'];
            force_download($file, NULL);
        }

        if ($param1 == "approve") {
            $data['status'] = 1;
            $this->db->where('user_id', $param2);
            $this->db->update('affiliator_status', $data);
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_approed_an_affiliator($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        } elseif ($param1 == "delete") {
            $this->db->where('user_id', $param2);
            $this->db->delete('affiliator_status');
            $get_user = $this->affiliate_course_model->get_userby_id($param2);
            $this->email_model->send_email_when_delete_an_affiliator_request($get_user['email'], $get_user['first_name']);
            $this->session->set_flashdata('flash_message', get_phrase('User has been notified'));
        }


        $page_data['page_name'] = 'pending_affiliator';
        $page_data['page_title'] = get_phrase('pending_affiliators');

        $page_data['pending_affiliator'] = $this->affiliate_course_model->get_pending_affiliator_application();
        $page_data['active_affiliator'] = $this->affiliate_course_model->get_all_data_of_affiliator_status_table();
        $page_data['suspend_affiliator'] = $this->affiliate_course_model->get_suspend_affiliator_application();
        $this->load->view('backend/index', $page_data);
    }

    public function affiliation_course_payouts($param1 = "")
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }
        /*   if ($param1 != '') {
            $date_range = $this->input->get('date_range');
            $date_range = explode(' - ', $date_range);
            $page_data['timestamp_start'] = strtotime($date_range[0]);
            $page_data['timestamp_end'] = strtotime($date_range[1]) + 86400;
        } else {
            $page_data['timestamp_start'] = strtotime(date('m/01/Y'));
            $page_data['timestamp_end'] = strtotime(date('m/t/Y')) + 86400;
        }*/

        $page_data['page_name'] = 'affiliation_course_payouts';
        $page_data['page_title'] = get_phrase('affiliation_course_payouts');


        $page_data['pending_course_payouts'] = $this->affiliate_course_model->get_table_pending_course_amount_info_from_course_affiliation_payouts();
        $page_data['completed_payouts'] = $this->affiliate_course_model->get_table_complete_course_amount_info_from_course_affiliation_payouts();

        $this->load->view('backend/index', $page_data);
    }



    public function paypal_checkout_for_affiliate_course_addon()
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }

        $page_data['amount_to_pay'] = $this->input->post('amount_to_pay');
        $page_data['payout_id'] = $this->input->post('payout_id');
        $page_data['instructor_name'] = $this->input->post('instructor_name');
        $page_data['production_client_id'] = $this->input->post('production_client_id');


        // BEFORE, CHECK PAYOUT AMOUNTS ARE VALID
        $payout_details = $this->affiliate_course_model
            ->get_affiliate_course_payouts($page_data['payout_id'])
            ->row_array();



        if (($payout_details['amount'] == $page_data['amount_to_pay'] && $payout_details['status'] == "pending")) {
            $this->load->view(
                'backend/admin/paypal_checkout_for_affiliate_course_addon',
                $page_data
            );
        } else {
            $this->session->set_flashdata(
                'error_message',
                get_phrase('invalid_payout_data')
            );
            redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
        }
    }

    public function paypal_payment(
        $payout_id = '',
        $paypalPaymentID = '',
        $paypalPaymentToken = '',
        $paypalPayerID = ''
    ) {




        $payout_details = $this->affiliate_course_model
            ->get_affiliate_course_payouts($payout_id)
            ->row_array();

        $instructor_id = $payout_details['user_id'];
        $instructor_data = $this->db
            ->get_where('users', ['id' => $instructor_id])
            ->row_array();

        $payment_keys = json_decode($instructor_data['payment_keys'], true);
        $paypal_keys = $payment_keys['paypal'];
        $production_client_id = $paypal_keys['production_client_id'];
        $production_secret_key = $paypal_keys['production_secret_key'];

        //THIS IS HOW I CHECKED THE PAYPAL PAYMENT STATUS
        $status = $this->payment_model->paypal_payment(
            $paypalPaymentID,
            $paypalPaymentToken,
            $paypalPayerID,
            $production_client_id,
            $production_secret_key
        );
        if (!$status) {
            $this->session->set_flashdata(
                'error_message',
                get_phrase('an_error_occurred_during_payment')
            );
            redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
        }

        $this->affiliate_course_model->update_payout_status(
            $payout_id,
            $method = 'paypal'
        );
        $this->session->set_flashdata(
            'flash_message',
            get_phrase('payout_updated_successfully')
        );

        redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
    }



    public function stripe_checkout_for_affiliate_course_addon($payout_id)
    {
        if ($this->session->userdata('admin_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }
        $payout_details = $this->affiliate_course_model->get_affiliate_course_payouts($payout_id)->row_array();



        if ($payout_details['amount'] > 0 && $payout_details['status'] == "pending") {

            $payout_details = $this->affiliate_course_model->get_affiliate_course_payouts($payout_id)->row_array();

            $page_data['user_details'] = $this->user_model->get_user($payout_details['user_id'])->row_array();
            $page_data['amount_to_pay'] = $payout_details['amount'];
            $page_data['payout_id'] = $payout_details['id'];

            $this->load->view('backend/admin/stripe_checkout_for_affiliate_course_addon', $page_data);
        } else {

            redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
        }
    }

    // STRIPE CHECKOUT ACTIONS
    public function stripe_payment($payout_id = '', $session_id = '')
    {

        $payout_details = $this->affiliate_course_model->get_affiliate_course_payouts($payout_id)->row_array();
        $instructor_id = $payout_details['user_id'];
        //THIS IS HOW I CHECKED THE STRIPE PAYMENT STATUS
        $response = $this->payment_model->stripe_payment($instructor_id, $session_id, true);

        if ($response['payment_status'] === 'succeeded') {
            $this->affiliate_course_model->update_payout_status($payout_id, $method = 'stripe');
            $this->session->set_flashdata('flash_message', get_phrase('payout_updated_successfully'));
            redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
        } else {
            $this->session->set_flashdata('error_message', $response['status_msg']);
        }
        redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
    }


    public function razorpay_checkout_for_affiliate_course_addon($user_id = '', $payout_id = '', $param1 = '', $razorpay_order_id = '', $payment_id = '', $amount = '', $signature = '')
    {
        if ($param1 == 'paid') {
            $status = $this->payment_model->razorpay_payment($razorpay_order_id, $payment_id, $amount, $signature);
            if ($status == true) {
                $this->affiliate_course_model->update_payout_status($payout_id, $method = 'razorpay');
                $this->session->set_flashdata('flash_message', get_phrase('payout_updated_successfully'));
                redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
            } else {
                $this->session->set_flashdata('error_message', get_phrase('status_msg'));
                redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
            }
        }




        $page_data['payout_id'] = $payout_id;
        $page_data['user_details'] = $this->user_model->get_user($user_id)->row_array();
        $page_data['amount_to_pay'] = $this->input->post('total_price_of_checking_out');
        $this->load->view('backend/admin/razorpay_checkout_for_affiliate_course_addon', $page_data);
    }

    public function affiliate_addon_settings($param1 = '')
    {
        if ($this->session->userdata('admin_login') != true) {
            redirect(site_url('login'), 'refresh');
        }


        if ($param1 == 'update') {
            if (isset($_POST['affiliate_addon_active_status'])) {
                $data['value'] = html_escape($this->input->post('affiliate_addon_active_status'));
                $this->db->where('key', 'affiliate_addon_active_status');
                $this->db->update('settings', $data);
            }

            if (isset($_POST['affiliate_addon_percentage'])) {
                $data['value'] = html_escape($this->input->post('affiliate_addon_percentage'));
                $this->db->where('key', 'affiliate_addon_percentage');
                $this->db->update('settings', $data);
            }


            $this->session->set_flashdata('flash_message', get_phrase('Affiliate_settings_updated'));
            redirect(site_url('addons/affiliate_course/affiliate_addon_settings'), 'refresh');
        }

        $page_data['page_name'] = 'affiliate_addon_settings';
        $page_data['page_title'] = get_phrase('affiliate_addon_settings');
        $this->load->view('backend/index', $page_data);
    }

    public function affiliator_form($param1 = '')
    {
        if ($this->session->userdata('admin_login') != true) {
            redirect(site_url('login'), 'refresh');
        }

        if ($param1 == 'add') {
            $this->affiliate_course_model->add_affiliator_by_admin();
            redirect(site_url('addons/affiliate_course/active_affiliator'), 'refresh');
        }

        $page_data['page_name'] = 'affiliator_add';
        $page_data['page_title'] = get_phrase('add_an_affiliator');
        $this->load->view('backend/index', $page_data);
    }

    public function check_affiliator_email_exists()
    {

        $email = $this->input->get('email');
        $exists = $this->affiliate_course_model->get_duplicate_affiliator_exiistence($email);



        if (!empty($exists)) {
            echo json_encode(
                [
                    'status' => true,
                    'value' => 'exits',
                ]
            );
        } else {

            echo json_encode(
                [
                    'status' => false,
                    'value' => 'not exits',
                ]

            );
        }
    }

    function configure_affiliator_payment($payout_id = "",$methode = null){

        if($this->session->userdata('admin_login')){
            $this->affiliate_course_model->configure_affiliator_payment($payout_id);
            if ($methode == 'force') {
                $this->payment_success('virement');
            }
            redirect(site_url('payment'), 'refresh');
        }
    }

    function payment_success($identifier = ''){

        $payment_details = $this->session->userdata('payment_details');

        $payout_details = $this->affiliate_course_model
            ->get_affiliate_course_payouts($payment_details['items'][0]['payout_id'])
            ->row_array();

        $instructor_id = $payout_details['user_id'];
        $instructor_data = $this->db
            ->get_where('users', ['id' => $instructor_id])
            ->row_array();
        
        $this->affiliate_course_model->update_payout_status($payment_details['items'][0]['payout_id'], $identifier);

        $this->session->set_userdata('payment_details', []);

        $this->session->set_flashdata(
            'flash_message',
            get_phrase('payout_updated_successfully')
        );

        redirect(site_url('addons/affiliate_course/affiliation_course_payouts'), 'refresh');
    }


     
 

}
