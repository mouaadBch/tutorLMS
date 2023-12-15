<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tutor_booking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('addons/tutor_booking_model');
        $this->load->library('pagination');
    }

    public function admin_access()
    {
        if ($this->session->userdata('admin_login') == false) {
            redirect(site_url('login'), 'refresh');
        }
    }

    public function both_admin_instructor_access()
    {
        if ($this->session->userdata('role_id') == 1) {
            if ($this->session->userdata('admin_login') == false) {
                redirect(site_url('login'), 'refresh');
            }
        } else {
            if ($this->session->userdata('user_login') == false || $this->session->userdata('is_instructor') != 1) {
                redirect(site_url('login'), 'refresh');
            }
        }
    }

    public function tutor_categories($param1 = "", $param2 = "")
    {


        $this->admin_access();


        if ($param1 == 'add') {

            $response = $this->tutor_booking_model->add_tuitor_category();
            if ($response) {
                $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            } else {
                $this->session->set_flashdata('error_message', get_phrase('category_name_already_exists'));
            }
            redirect(site_url('addons/tutor_booking/tutor_categories'), 'refresh');
        } elseif ($param1 == "edit") {

            $response = $this->tutor_booking_model->edit_tutor_category($param2);
            if ($response) {
                $this->session->set_flashdata('flash_message', get_phrase('data_edited_successfully'));
            } else {
                $this->session->set_flashdata('error_message', get_phrase('category_name_already_exists'));
            }
            redirect(site_url('addons/tutor_booking/tutor_categories'), 'refresh');
        } elseif ($param1 == "delete") {

            $this->tutor_booking_model->delete_tutor_category($param2);
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
            redirect(site_url('addons/tutor_booking/tutor_categories'), 'refresh');
        }
        $page_data['page_name'] = 'tutor_categories';
        $page_data['page_title'] = get_phrase('tutor_categories');
        $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories($param2);
        $this->load->view('backend/index', $page_data);
    }

    public function tutor_category_form($param1 = "", $param2 = "")
    {
        $this->admin_access();

        if ($param1 == "add_category") {

            $page_data['page_name'] = 'tutor_category_add';
            $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories()->result_array();
            $page_data['page_title'] = get_phrase('add_category');
        }
        if ($param1 == "edit_category") {

            $page_data['page_name'] = 'tutor_category_edit';
            $page_data['page_title'] = get_phrase('edit_category');
            $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories()->result_array();
            $page_data['category_id'] = $param2;
        }

        $this->load->view('backend/index', $page_data);
    }

    public function schedule($param1 = "")
    {

        $this->both_admin_instructor_access();

        if ($param1 == 'add') {
            $this->tutor_booking_model->add_schedule();
            $this->session->set_flashdata('flash_message', get_phrase('schedule_added_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
        }


        if ($param1 == 'edit') {
            $this->tutor_booking_model->edit_schedule();
            $this->session->set_flashdata('flash_message', get_phrase('schedule_updated_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
        }

        $page_data['page_name'] = 'add_schedule';
        $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories()->result_array();
        $page_data['page_title'] = get_phrase('add_schedule');
        $this->load->view('backend/index', $page_data);
    }

    public function categoryWiseSubcategory($parent = "")
    {
        $sub_category = $this->tutor_booking_model->category_wise_subcategory($parent);
        $sub_categories = $sub_category->result_array();
        $count = $sub_category->num_rows();
        $options = "";
        $not_found = "no subcategory found ";

        if ($count > 0) {
            foreach ($sub_categories as $category) :
                $options .= '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
            endforeach;
            echo $options;
        } else {
            $options .= '<option value="0">'  . $not_found . '</option>';
            echo $options;
        }
    }

    public function tutor_booking_list($param1 = "", $param2 = "")
    {
        $this->both_admin_instructor_access();


        $page_data['page_name'] = 'tutor_booking_list';
        $page_data['page_title'] = get_phrase('list_of_bookings');
        $page_data['booking_list'] = $this->tutor_booking_model->get_all_tutor_booked_list();
        $page_data['inactive_booking'] = $this->tutor_booking_model->get_all_tutor_inactive_booked_list();
        $this->load->view('backend/index', $page_data);
    }

    public function tutor_inactive_booking_list($param1 = "", $param2 = "")
    {
        $this->both_admin_instructor_access();


        $page_data['page_name'] = 'tutor_inactive_booking_list';
        $page_data['page_title'] = get_phrase('list_of_inactive_bookings');
        $page_data['booking_list'] = $this->tutor_booking_model->get_all_tutor_inactive_booked_list();
        $this->load->view('backend/index', $page_data);
    }

    public function tutor_schedule_list_by_booking_id($booking_id = "")
    {
        $this->both_admin_instructor_access();

        $role = strtolower($this->session->userdata('role'));
        $schedules = $this->tutor_booking_model->get_schedule_by_bokking_id($booking_id);
        $schedules_achieve = $this->tutor_booking_model->get_achieve_schedule_by_bokking_id($booking_id);
        $booking = $this->tutor_booking_model->get_tutor_booking_data_by_bokking_id($booking_id)->row_array();
        $page_data['schedules'] =  $schedules;
        $page_data['schedules_achieve'] = $schedules_achieve;
        $page_data['inactive_schedule'] =  $this->tutor_booking_model->get_inactive_schedule_list_count($booking_id);
        $page_data['booking'] =  $booking;
        $page_data['page_name'] = 'tutor_schedule_list';
        $page_data['page_title'] = get_phrase('list_of_schedules');
        // $this->load->view("backend/$role/tutor_schedule_list", $page_data);
        $this->load->view('backend/index', $page_data);
    }

    public function tutor_inactive_schedule_list_by_booking_id($booking_id = "")
    {

        $this->both_admin_instructor_access();

        $role = strtolower($this->session->userdata('role'));
        $schedules = $this->tutor_booking_model->get_inactive_schedule_by_bokking_id($booking_id);
        $schedules_achieve = $this->tutor_booking_model->get_inactive_achieve_schedule_by_bokking_id($booking_id);
        $booking = $this->tutor_booking_model->get_tutor_booking_data_by_bokking_id($booking_id)->row_array();
        $page_data['schedules'] =  $schedules;
        $page_data['schedules_achieve'] = $schedules_achieve;
        $page_data['booking'] =  $booking;
        $page_data['page_name'] = 'tutor_inactive_schedule_list';
        $page_data['page_title'] = get_phrase('list_of_inactive_schedules');
        // $this->load->view("backend/$role/tutor_schedule_list", $page_data);
        $this->load->view('backend/index', $page_data);
    }


    public function edit_booking_by_id($booking_id = "")
    {
        $this->both_admin_instructor_access();

        $booking = $this->tutor_booking_model->get_tutor_booking_data_by_bokking_id($booking_id)->row_array();
        $schedules = $this->tutor_booking_model->get_schedule_by_bokking_id($booking_id);
        $no_of_schedules = $schedules->num_rows();
        $i = 1;
        $multiple_day_index = array();
        $schedule_ids = array();
        if ($no_of_schedules > 0) {
            $s = $schedules->result_array();
            foreach ($s as $key => $item) {


                array_push($schedule_ids, $item['id']);
                if ($item['tuition_type'] == 0) {
                    array_push($multiple_day_index, $i);
                }
                $i++;
            }
        }

        $page_data['booking_details'] =  $booking;
        $page_data['schedule_details'] =  $schedules;
        $page_data['no_of_schedules'] =  $no_of_schedules;
        $page_data['multiple_day_index'] = $multiple_day_index;
        $page_data['schedule_ids'] = $schedule_ids;
        $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories()->result_array();
        $page_data['page_name'] = 'edit_schedule';
        $page_data['page_title'] = 'edit_schedule';
        $this->load->view('backend/index', $page_data);
    }

    public function booking_data($param = "", $id = "")
    {
        $this->both_admin_instructor_access();


        if ($param == 'update') {
            $this->tutor_booking_model->update_booking_schedule($id);
            $this->session->set_flashdata('flash_message', get_phrase('updated_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
        }

        if ($param == 'inactive') {
            $this->tutor_booking_model->disable_booking($id);
            $this->session->set_flashdata('flash_message', get_phrase('disable_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
        }

        if ($param == 'active') {
            $this->tutor_booking_model->enable_booking($id);
            $this->session->set_flashdata('flash_message', get_phrase('Active_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
        }
    }

    public function edit_booking_on_booking_table_page($booking_id = "")
    { //on edit

        $this->both_admin_instructor_access();

        $role = strtolower($this->session->userdata('role'));
        $booking = $this->tutor_booking_model->get_tutor_booking_data_by_bokking_id($booking_id)->row_array();
        $page_data['booking_details'] =  $booking;
        $page_data['categories'] = $this->tutor_booking_model->get_tutor_categories()->result_array();
        $this->load->view("backend/$role/edit_tutor_booking", $page_data);
    }


    public function edit_schedule_by_id($id = "")
    {
        $this->both_admin_instructor_access();

        $role = strtolower($this->session->userdata('role'));
        $schedule = $this->tutor_booking_model->get_tutor_schedule_data_by_bokking_id($id)->row_array();
        $page_data['schedule_details'] =  $schedule;
        $this->load->view("backend/$role/edit_tutor_schedule", $page_data);
    }


    public function schedule_data($param = "", $id = "")
    {

        $this->both_admin_instructor_access();

        if ($param == 'update') {
            $schedule = $this->tutor_booking_model->get_schedule_info($id);
            $this->tutor_booking_model->update_schedule($id);
            $this->session->set_flashdata('flash_message', get_phrase('updated_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_schedule_list_by_booking_id/' . $schedule['booking_id']), 'refresh');
        }

        if ($param == 'inactive') {
            $schedule = $this->tutor_booking_model->get_schedule_info($id);
            $this->tutor_booking_model->inactive_schedule($id);
            $this->session->set_flashdata('flash_message', get_phrase('Inactivated_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_schedule_list_by_booking_id/' . $schedule['booking_id']), 'refresh');
        }

        if ($param == 'active') {
            $schedule = $this->tutor_booking_model->get_schedule_info($id);
            $this->tutor_booking_model->active_schedule($id);
            $this->session->set_flashdata('flash_message', get_phrase('activated_successfully'));
            redirect(site_url('addons/tutor_booking/tutor_schedule_list_by_booking_id/' . $schedule['booking_id']), 'refresh');
        }
    }



    public function list_of_tuitions($uri_segement = 0)
    {


        $value = $this->tutor_booking_model->get_all_schedules_before_filter(0, 0, "pause");
        $config['base_url'] = site_url('tutors');
        $config['total_rows'] = $value['bookings']->num_rows();
        $config['per_page'] = 6;
        $config['full_tag_open']   = '<ul class="pagination justify-content-center">';
        $config['full_tag_close']  = '</ul>';
        $config['prev_link']       = '<i class="fas fa-chevron-left"></i>';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '<i class="fas fa-chevron-right"></i>';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active disabled"> <span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $this->pagination->initialize($config);


        $value = $this->tutor_booking_model->get_all_schedules_before_filter($config['per_page'], $uri_segement, 'play');


        $page_data['up_coming_schedules'] = $value['bookings'];
        $page_data['highest_price'] = $value['highest_price'];
        $page_data['tutors'] = $value['tutors'];
        $page_data['data_base_main_category'] = $this->tutor_booking_model->get_main_category();
        $page_data['data_base_sub_category'] = $this->tutor_booking_model->get_sub_category();
        $page_data['page_name'] = "list_of_tuitions";
        $page_data['page_title'] = site_phrase("list_of_tuitions");
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }


    public function list_of_tuitions_after_filter($uri_segement = 0)
    {




        $filter = $_GET;
        $filtered_data = $this->tutor_booking_model->get_all_schedules_after_filter($filter, 0, 0, "pause");

        $config['base_url'] = site_url('tutors');
        $config['total_rows'] = $filtered_data['booking_after_filter']->num_rows();
        $config['per_page'] = 6;
        $config['full_tag_open']   = '<ul class="pagination justify-content-center">';
        $config['full_tag_close']  = '</ul>';
        $config['prev_link']       = '<i class="fas fa-chevron-left"></i>';
        $config['prev_tag_open']   = '<li class="page-item">';
        $config['prev_tag_close']  = '</li>';
        $config['next_link']       = '<i class="fas fa-chevron-right"></i>';
        $config['next_tag_open']   = '<li class="page-item">';
        $config['next_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="page-item active disabled"> <span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['num_tag_open']    = '<li class="page-item">';
        $config['num_tag_close']   = '</li>';
        $config['first_tag_open']  = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open']   = '<li class="page-item">';
        $config['last_tag_close']  = '</li>';
        $this->pagination->initialize($config);


        $filtered_data = $this->tutor_booking_model->get_all_schedules_after_filter($filter, $config['per_page'], $uri_segement, 'play');



        foreach ($filtered_data['searched_data'] as $key => $searched_data) {
            $page_data[$key] = $searched_data;
        }

        //$value=$this->tutor_booking_model->get_all_schedules_before_filter();
        $page_data['up_coming_schedules'] = $filtered_data['booking_after_filter'];
        $page_data['tutors'] = $filtered_data['tutors'];
        $page_data['data_base_main_category'] = $this->tutor_booking_model->get_main_category();
        #$page_data['data_base_sub_category'] = $this->tutor_booking_model->get_sub_category();
        $page_data['page_name'] = "list_of_tuitions";
        $page_data['page_title'] = site_phrase("list_of_tuitions");


        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }


    public function tutor_details($tutor_id = "")
    {

        //$tutor_id = $this->session->set_userdata('tutor_id', $tutor_id);



        $total_review = $this->tutor_booking_model->get_tutor_review($tutor_id);
        $number_of_rating = $total_review->num_rows();
        $tutor_rating = 0;

        if ($total_review->num_rows() > 0) {
            $rating_count = $total_review->result_array();

            foreach ($rating_count as $rating) {
                $tutor_rating += $rating['rating'];
            }

            $tutor_rating = $tutor_rating / $number_of_rating;
            $tutor_rating = round($tutor_rating, 1);
        }

        $if_access_to_write_a_review = $this->tutor_booking_model->check_access_to_write_a_review($tutor_id);
        $already_wrote_a_review = $this->tutor_booking_model->check_if_already_wrote_a_review($tutor_id);

        $tutor_schedules = $this->tutor_booking_model->get_tutor_all_schedules($tutor_id);


        $total_hours_taught = $this->tutor_booking_model->total_hours_taught($tutor_id);
        $total_student = $this->tutor_booking_model->total_student($tutor_id);

        $given_review = $this->tutor_booking_model->given_review($tutor_id);

        $page_data['tutor_id'] = $tutor_id;
        $page_data['given_review'] = $given_review;
        $page_data['if_access_to_write_a_review'] = $if_access_to_write_a_review;
        $page_data['already_wrote_a_review'] = $already_wrote_a_review;
        $page_data['total_student'] = $total_student;
        $page_data['total_hours_taught'] = $total_hours_taught;
        $page_data['number_of_rating'] = $number_of_rating;
        $page_data['tutor_rating'] = $tutor_rating;
        $page_data['page_name'] = "tutor_details";
        $page_data['total_review'] = $total_review;
        $page_data['tutor_schedules'] = $tutor_schedules;
        $page_data['total_review_short'] = $total_review;
        $page_data['page_title'] = site_phrase("tutore Details");
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function reviewpost($tutor_id)
    {
        if (!isset($_SESSION['role_id'])) {
            redirect(site_url('login'), 'refresh');
        }

        $data = $_POST;
        $data['tutor_id'] = $tutor_id;

        $this->tutor_booking_model->save_tutor_review($data);
        $this->session->set_flashdata('flash_message', get_phrase('Review Has given'));
        redirect(site_url('schedules_bookings/' . $tutor_id));
    }

    public function editreviewpost($tutor_id, $student_id)
    {
        if (!isset($_SESSION['role_id'])) {
            redirect(site_url('login'), 'refresh');
        }

        $data = $_POST;
        $data['tutor_id'] = $tutor_id;
        $data['student_id'] = $student_id;


        $this->tutor_booking_model->update_tutor_review($data);
        $this->session->set_flashdata('flash_message', get_phrase('Review Has updated'));
        redirect(site_url('schedules_bookings/' . $tutor_id));
    }


    public function book_a_schedule()
    {

        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');
        $data = $_POST;

        $get_schedule_info = $this->tutor_booking_model->get_schedule_info($data['schedule_id_booking']);


        //$get_booking_info=$this->tutor_booking_model->get_booking_info($get_schedule_info['booking_id']);

        $items = array();
        $total_payable_amount = 0;

        //item detail
        $item_details['id'] = $data['schedule_id_booking'];
        $item_details['title'] = get_phrase('Schedule booking');
        $item_details['thumbnail'] = '';
        $item_details['creator_id'] = $get_schedule_info['tutor_id'];
        $item_details['discount_flag'] = 0;
        $item_details['discounted_price'] = $data['amount'];
        $item_details['price'] = $data['amount'];

        $item_details['actual_price'] = $data['amount'];
        $item_details['sub_items'] = array();

        $items[] = $item_details;
        $total_payable_amount += $item_details['price'];
        //ended item detail

        //included tax
        //$total_payable_amount = round($total_payable_amount + ($total_payable_amount/100) * get_settings('course_selling_tax'), 2);

        //common structure for all payment gateways and all type of payment
        $data['total_payable_amount'] = $total_payable_amount;
        $data['items'] = $items;
        $data['is_instructor_payout_user_id'] = false;
        $data['payment_title'] = get_phrase('pay_for_schedule_booking');
        $data['success_url'] = site_url('addons/tutor_booking/success_booking_payment');
        $data['cancel_url'] = site_url('payment');
        $data['back_url'] = site_url('tutors');
        $this->session->set_userdata('payment_details', $data);

        // die();

        redirect(site_url('payment'), 'refresh');
    }

    function success_booking_payment($payment_method = "")
    {
        //STARTED payment model and functions are dynamic here
        $response = false;
        $payment_gateway = $this->db->get_where('payment_gateways', ['identifier' => $payment_method])->row_array();
        $model_name = strtolower($payment_gateway['model_name']);
        if ($payment_gateway['is_addon'] == 1 && $model_name != null) {
            $this->load->model('addons/' . strtolower($payment_gateway['model_name']));
        }

        if($payment_gateway['identifier'] == 'cmi'){
            $this->load->model(strtolower($payment_gateway['model_name']));
        }

        if ($model_name != null) {
            $payment_check_function = 'check_' . $payment_method . '_payment';
            $response = $this->$model_name->$payment_check_function($payment_method,'booking');
        }
        //ENDED payment model and functions are dynamic here

        $user_id = $this->session->userdata('user_id');
        $payment_details = $this->session->userdata('payment_details');


        if ($response === true) {
            $get_schedule_info = $this->tutor_booking_model->get_schedule_info($payment_details['items'][0]['id']);
            $this->tutor_booking_model->complete_schedule_booking($user_id, $payment_details['items'][0]['id'], $get_schedule_info['booking_id'], $payment_method, '', $payment_details['total_payable_amount']);

            $this->session->set_userdata('payment_details', []);
            $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
            if ($payment_method == 'cmi') {
                $page_data['page_name'] = "cmi-okFail";
                $page_data['page_title'] = site_phrase('cmi-okFail');
                $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
            } else {
                redirect('my_bookings', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error_message', site_phrase('payment_failed'));
            redirect('my_bookings', 'refresh');
        }
    }

    // SHOW PAYPAL CHECKOUT PAGE
    public function paypal_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true')
            redirect('home', 'refresh');

        //checking price
        if ($this->session->userdata('booking_amount') == $this->input->post('booking_amount')) :
            $booking_amount = $this->input->post('booking_amount');
        else :
            $booking_amount = $this->session->userdata('booking_amount');
        endif;

        $page_data['payment_request'] = $payment_request;
        $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        $page_data['booking_amount']   = $booking_amount;

        $this->load->view('tutor_booking_payment/paypal_checkout', $page_data);
    }

    // PAYPAL CHECKOUT ACTIONS
    public function paypal_payment($purchesed_schedule_id, $purchesed_booking_id, $user_id = "", $amount_paid = "", $paymentID = "", $paymentToken = "", $payerID = "", $payment_request_mobile = "")
    {
        $paypal_keys = get_settings('paypal');
        $paypal = json_decode($paypal_keys);

        if ($paypal[0]->mode == 'sandbox') {
            $paypalClientID = $paypal[0]->sandbox_client_id;
            $paypalSecret   = $paypal[0]->sandbox_secret_key;
        } else {
            $paypalClientID = $paypal[0]->production_client_id;
            $paypalSecret   = $paypal[0]->production_secret_key;
        }

        //THIS IS HOW I CHECKED THE PAYPAL PAYMENT STATUS
        $status = $this->payment_model->paypal_payment($paymentID, $paymentToken, $payerID, $paypalClientID, $paypalSecret);
        if (!$status) {
            $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
            redirect('my_bookings', 'refresh');
        }

        $transaction_id = json_encode($paymentID);
        $this->tutor_booking_model->complete_schedule_booking($user_id, $purchesed_schedule_id, $purchesed_booking_id, "paypal", $transaction_id, $amount_paid);


        $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
        if ($payment_request_mobile == 'true') :
            $course_id = $this->session->userdata('cart_items');
            redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
        else :
            $this->session->set_userdata('cart_items', array());
            redirect('my_bookings', 'refresh');
        endif;
    }

    //------------------

    public function razorpay_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true')
            redirect('home', 'refresh');


        $booking_amount = $this->session->userdata('booking_amount');
        $page_data['payment_request'] = $payment_request;
        $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        $page_data['booking_amount']   = $this->input->post('booking_amount');


        $this->load->view('tutor_booking_payment/razorpay/razorpay_checkout', $page_data);
    }

    public function razorpay_payment($payment_request_mobile = "")
    {




        $response = array();
        $user_id            = $_GET['user_id'];
        if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['amount']) && !empty($_GET['amount'])) {
            $amount             = $_GET['amount'];
            $razorpay_order_id      = $_GET['razorpay_order_id'];
            $payment_id         = $_GET['payment_id'];
            $signature        = $_GET['signature'];
            $purchesed_schedule_id = $_GET['purchesed_schedule_id'];
            $purchesed_booking_id = $_GET['purchesed_booking_id'];


            //THIS IS HOW I CHECKED THE PAYPAL PAYMENT STATUS
            $status = $this->payment_model->razorpay_payment($razorpay_order_id, $payment_id, $amount, $signature);


            if ($status == 1) {
                $payment_key['payment_id'] = $payment_id;
                $payment_key['razorpay_order_id'] = $razorpay_order_id;
                $payment_key['signature'] = $signature;
                $payment_key = json_encode($payment_key);


                $this->tutor_booking_model->complete_schedule_booking($user_id, $purchesed_schedule_id, $purchesed_booking_id, "razorpay", $payment_key, $amount);


                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                if ($payment_request_mobile == 'true') :

                    $course_id = $this->session->userdata('cart_items');
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else :

                    $this->session->set_userdata('cart_items', array());
                    redirect('my_bookings', 'refresh');
                endif;
            }
        } else {
            if ($payment_request_mobile == 'true') :

                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', $response['status_msg']);
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/error', 'refresh');
            else :

                $this->session->set_flashdata('error_message', site_phrase('payment_failed') . '! ' . site_phrase('something_is_wrong'));
                redirect('home/shopping_cart', 'refresh');
            endif;
        }
    }



    // SHOW STRIPE CHECKOUT PAGE
    public function stripe_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true')
            redirect('home', 'refresh');

        //checking price
        $booking_amount = $this->session->userdata('booking_amount');
        $page_data['payment_request'] = $payment_request;
        $page_data['user_details']    = $this->user_model->get_user($this->session->userdata('user_id'))->row_array();
        $page_data['booking_amount']   = $booking_amount;
        $this->load->view('tutor_booking_payment/stripe/stripe_checkout', $page_data);
    }

    // STRIPE CHECKOUT ACTIONS
    public function stripe_payment($purchesed_schedule_id = "", $purchesed_booking_id = "", $user_id = "", $amount = "", $payment_request_mobile = "", $session_id = "")
    {
        //THIS IS HOW I CHECKED THE STRIPE PAYMENT STATUS



        $response = $this->payment_model->stripe_payment($user_id, $session_id);




        if ($response['payment_status'] === 'succeeded') {
            // STUDENT ENROLMENT OPERATIONS AFTER A SUCCESSFUL PAYMENT
            $check_duplicate = $this->crud_model->check_duplicate_payment_for_stripe($response['transaction_id'], $session_id);
            if ($check_duplicate == false) :

                $transaction_id = json_encode($response['transaction_id']);
                $this->tutor_booking_model->complete_schedule_booking($user_id, $purchesed_schedule_id, $purchesed_booking_id, "stripe", $transaction_id, $amount);

            else :
                //duplicate payment
                $this->session->set_flashdata('error_message', site_phrase('session_time_out'));
                redirect('home/shopping_cart', 'refresh');
            endif;

            if ($payment_request_mobile == 'true') :
                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
            else :
                $this->session->set_userdata('cart_items', array());
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                redirect('my_bookings', 'refresh');
            endif;
        } else {
            if ($payment_request_mobile == 'true') :
                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', $response['status_msg']);
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/error', 'refresh');
            else :
                $this->session->set_flashdata('error_message', $response['status_msg']);
                redirect('my_bookings', 'refresh');
            endif;
        }
    }

    //-------------------


    public function booked_schedules()
    {
        //for admin and instructor

        $this->both_admin_instructor_access();


        $role = strtolower($this->session->userdata('role'));
        $booked_schedules_list = $this->tutor_booking_model->list_of_booked_schedule_student_list($role);
        $page_data['schedules'] =  $booked_schedules_list;
        $page_data['page_name'] = "booked_schedule_details";
        $page_data['page_title'] = site_phrase("booked_schedules");
        $this->load->view('backend/index', $page_data);
    }

    public function booked_schedules_student()
    {
        //for student

        if (!isset($_SESSION['role_id'])) {
            redirect(site_url('login'), 'refresh');
        }

        $booked_schedules_list_student = $this->tutor_booking_model->list_of_booked_schedule_by_student();
        $archieve_booked_schedule = $this->tutor_booking_model->list_of_booked_achieve_schedule_by_student();
        $page_data['schedules'] =  $booked_schedules_list_student;
        $page_data['archieve_schedules'] =  $archieve_booked_schedule;
        $page_data['student_payments'] =  $this->tutor_booking_model->booked_tuition_payment();
        $page_data['page_name'] = "booked_schedule_student";
        $page_data['page_title'] = site_phrase("booked_schedules");
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }


    // UPDATE ZOOM SETTINGS
    public function zoom_live_class_settings($param1 = "")
    {

        $this->both_admin_instructor_access();


        if ($param1 == 'update') {
            $this->tutor_booking_model->update_live_class_settings();
        }
        $page_data['page_name'] = 'tutor_live_class_settings';
        $page_data['page_title'] = get_phrase('live_class_settings');
        $this->load->view('backend/index.php', $page_data);
    }


    // JOIN TO LIVE CLASS
    public function join($schedule_id = "")
    {


        // CHECK USER OR ADMIN LOGIN STATUS
        $this->is_logged_in();

        $schedule_details = $this->tutor_booking_model->get_schedule_by_id($schedule_id)->row_array();
        $booking_details = $this->tutor_booking_model->get_booking_by_schedule_id($schedule_id)->row_array();


        $page_data['booking_details']      = $booking_details;
        $page_data['schedule_details']      = $schedule_details;
        $page_data['instructor_details']  = $this->user_model->get_all_user($schedule_details['tutor_id'])->row_array();
        $page_data['live_class_details']  = $this->tutor_booking_model->get_live_class_details($schedule_id);
        $page_data['logged_user_details'] = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();


        $this->load->view('frontend/' . get_frontend_settings('theme') . '/tutor_live_class', $page_data);
    }


    public function join_tutor($schedule_id = "")
    {

        $this->is_logged_in();


        $role = strtolower($this->session->userdata('role'));
        $schedule_details = $this->tutor_booking_model->get_schedule_by_id($schedule_id)->row_array();
        $booking_details = $this->tutor_booking_model->get_booking_by_schedule_id($schedule_id)->row_array();


        $page_data['booking_details']      = $booking_details;
        $page_data['schedule_details']      = $schedule_details;
        $page_data['instructor_details']  = $this->user_model->get_all_user($schedule_details['tutor_id'])->row_array();
        $page_data['live_class_details']  = $this->tutor_booking_model->get_live_class_details($schedule_id);
        $page_data['logged_user_details'] = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();


        $this->load->view("backend/$role/tutor_side_live_class", $page_data);
    }

    public function is_logged_in()
    {
        if ($this->session->userdata('user_login') != 1 && $this->session->userdata('admin_login') != 1) {
            redirect('home', 'refresh');
        }
    }
}
