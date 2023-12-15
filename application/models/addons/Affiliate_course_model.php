<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_course_model extends CI_Model
{
    public function get_userby_id($id = "")
    {
        return  $this->db->get_where('users', array('id' => $id))->row_array();
    }

    public function get_admin_details()
    {
        return  $this->db->get_where('users', array('role_id' => 1))->row_array();
    }

    public function last_affiliate_course_entry($id = "", $course_id = "", $transaction_id = "")
    {
        return  $table = $this->db->get_where('payment', array('user_id' => $id, 'course_id' => $course_id, 'transaction_id', $transaction_id))->row_array();
    }

    public function get_user_by_their_unique_identifier($unique_identifier = "")
    {
        return  $this->db->get_where('affiliator_status', array('unique_identifier' => $unique_identifier))->row_array();
    }

    public function get_affiliate_course_table_info_by_user($user_id = "")
    {

        return  $this->db->where(array('referee_id' => $user_id, 'type' => "course"))->order_by('date_added', 'DESC')->get('course_affiliation');
    }

    public function get_withdrawl_request_info_for_referral_course_amount($user_id = "")
    {

        return  $this->db->where(array('user_id' => $user_id, 'type' => "course"))->order_by('date', 'DESC')->get('course_affiliation_payment');
    }

    public function get_withdrawl_pending_request_info_for_course($user_id = "")
    {
        return  $this->db->where(array('user_id' => $user_id, 'status' => "pending", 'type' => "course"))->order_by('date', 'DESC')->get('course_affiliation_payment');
    }
    public function get_pending_request_info_for_course_status_table()
    {
        return  $this->db->where(array('status' => "0"))->order_by('id', 'DESC')->get('affiliator_status');
    }



    public function is_affilator($user_id = "")
    {
        $check = $this->db->where(array('user_id' => $user_id))->get('affiliator_status');
        if ($check->num_rows() == 1) {
            $status=$check->row_array();
            return $status['status'];
        } else {
            return 0;
        }
    }
    public function get_all_data_of_affiliator_status_table()
    {
        return $this->db->where(array('status' => "1"))->get('affiliator_status');
    }
    public function get__affiliator_status_table_info_by_user_id($user_id="")
    {
        return $this->db->where(array('user_id' => $user_id))->get('affiliator_status')->row_array();
    }
    public function get_pending_affiliator_application()
    {
        return $this->db->where(array('status' => "0"))->get('affiliator_status');
    }
    public function get_suspend_affiliator_application()
    {
        return $this->db->where(array('status' => "2"))->get('affiliator_status');
    }



    public function delete_course_withdrawl_pending_request($user_id = "")
    {
        $this->db->where(array('user_id' => $user_id, 'status' => "pending", 'type' => "course"));
        $this->db->delete('course_affiliation_payment');
        $this->session->set_flashdata('flash_message', get_phrase('pending_request_deleted_successfully'));
    }

    public function get_applications_for_becoming_an_affiliator($id = "", $type = "")
    {
        if ($id > 0 && !empty($type)) {
            if ($type == 'user') {
                $applications = $this->db->get_where('affiliator_status', array('user_id' => $id));
                return $applications;
            } else {
                $applications = $this->db->get_where('affiliator_status', array('id' => $id));
                return $applications;
            }
        } else {
            $this->db->order_by("id", "DESC");
            $applications = $this->db->get_where('affiliator_status');
            return $applications;
        }
    }

    public function post_affiliator_application()
    {
        // FIRST GET THE USER DETAILS
        $user_details = $this->user_model->get_all_user($this->input->post('id'))->row_array();

        // CHECK IF THE PROVIDED ID AND EMAIL ARE COMING FROM VALID USER
        if ($user_details['email'] == $this->input->post('email')) {

            // GET PREVIOUS DATA FROM APPLICATION TABLE
            $previous_data = $this->get_applications_for_becoming_an_affiliator($user_details['id'], 'user')->num_rows();
            // CHECK IF THE USER HAS SUBMITTED FORM BEFORE
            if ($previous_data > 0) {
                $this->session->set_flashdata('error_message', get_phrase('already_submitted'));
                redirect(site_url('addons/affiliate_course/become_an_affiliator'), 'refresh');
            }
   
            $data['user_id'] = $this->input->post('id');
            $data['unique_identifier'] = $data['user_id'].strtolower(random(10));
            $data['address'] = $this->input->post('address');
            $data['phone'] = $this->input->post('phone');
            $data['message'] = $this->input->post('message');
            $data['status'] = "0";
            if (isset($_FILES['document']) && $_FILES['document']['name'] != "") {
                if (!file_exists('uploads/document')) {
                    mkdir('uploads/document', 0777, true);
                }
                $accepted_ext = array('pdf', 'png', 'jpg', 'jpeg');
                $path = $_FILES['document']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), $accepted_ext)) {
                    $document_custom_name = random(15) . '.' . $ext;
                    $data['document'] = $document_custom_name;
                    move_uploaded_file($_FILES['document']['tmp_name'], 'uploads/document/' . $document_custom_name);
                } else {
                    $this->session->set_flashdata('error_message', get_phrase('invalide_file'));
                    redirect(site_url('addons/affiliate_course/become_an_affiliator'), 'refresh');
                }
            }
            $this->db->insert('affiliator_status', $data);
            $this->session->set_flashdata('flash_message', get_phrase('application_submitted_successfully'));
            redirect(site_url('addons/affiliate_course/become_an_affiliator'), 'refresh');
        } else {
            $this->session->set_flashdata('error_message', get_phrase('user_not_found'));
            redirect(site_url('addons/affiliate_course/become_an_affiliator'), 'refresh');
        }
    }


    public function get_course_completed_affiliate_history_by_date_range($timestamp_start = "", $timestamp_end = "",$selected_user="")
    {
        $this->db->order_by('id', 'DESC');
        $this->db->where('date_added >=', $timestamp_start);
        $this->db->where('date_added <=', $timestamp_end);
        $this->db->where('type', "course");   
        if($selected_user!="")
        {
            $this->db->where('referee_id', $selected_user);
        }
        return $this->db->get('course_affiliation');
    }

    public function get_table_pending_course_amount_info_from_course_affiliation_payouts()
    {

        return  $this->db->where(array('status' => "pending", 'type' => "course"))->order_by('date', 'DESC')->get('course_affiliation_payment');
    }

    public function get_table_complete_course_amount_info_from_course_affiliation_payouts()
    {

        $this->db->order_by('id', 'DESC');
       // $this->db->where('date >=', $timestamp_start);
      //  $this->db->where('date <=', $timestamp_end);
        $this->db->where('type', "course");
        $this->db->where('status', "approved");
        return $this->db->get('course_affiliation_payment');
    }


    public function get_affiliate_course_payouts($id = "")
    {


        $this->db->where('id', $id);
        return $this->db->get('course_affiliation_payment');
    }

    public function update_payout_status($payout_id = "", $method = "")
    {

        $updater = array(
            'status' => "approved",
            'payment_type' => $method
        );
        $this->db->where('id', $payout_id);
        $this->db->update('course_affiliation_payment', $updater);

        $payout_details = $this->get_affiliate_course_payouts($payout_id)->row_array();
        $user_id = $payout_details['user_id'];
        $get_user=$this->get_userby_id($user_id);
        $this->email_model->send_email_when_withdrawl_request_for_affiliator_approved($get_user['email'],$get_user['first_name']);

    }



    public function add_affiliator_by_admin()
    {
        $existence = "no";
        $that_id="null";
        $users_table_info = $this->db->get('users');
        foreach ($users_table_info->result_array() as $detect_user_email) {
            if ($detect_user_email['email'] == html_escape($this->input->post('email'))) {
                $that_id=$detect_user_email['id'];
                $existence = "yes";
            }
        }

  
        
                $data['first_name'] = html_escape($this->input->post('first_name'));
                $data['last_name'] = html_escape($this->input->post('last_name'));
                $data['email'] = html_escape($this->input->post('email'));
           
                $social_link['facebook'] = html_escape($this->input->post('facebook_link'));
                $social_link['twitter'] = html_escape($this->input->post('twitter_link'));
                $social_link['linkedin'] = html_escape($this->input->post('linkedin_link'));
                $data['social_links'] = json_encode($social_link);
                $data['role_id'] = 2;

                $data['date_added'] = strtotime(date("Y-m-d H:i:s"));
                $data['wishlist'] = json_encode(array());
                $data['status'] = 1;
                $data['image'] = md5(rand(10000, 10000000));

                // Add paypal keys
                $payment_keys = array();

                $paypal['production_client_id']  = html_escape($this->input->post('paypal_client_id'));
                $paypal['production_secret_key'] = html_escape($this->input->post('paypal_secret_key'));
                $payment_keys['paypal'] = $paypal;

                // Add Stripe keys
                $stripe['public_live_key'] = html_escape($this->input->post('stripe_public_key'));
                $stripe['secret_live_key'] = html_escape($this->input->post('stripe_secret_key'));
                $payment_keys['stripe'] = $stripe;

                // Add razorpay keys
                $razorpay['key_id'] = html_escape($this->input->post('key_id'));
                $razorpay['secret_key'] = html_escape($this->input->post('secret_key'));
                $payment_keys['razorpay'] = $razorpay;

                //All payment keys
                $data['payment_keys'] = json_encode($payment_keys);

              if($existence == "yes")
              {
                $c=$this->db->get_where('affiliator_status', array('user_id' => $that_id));

                if($c->num_rows()==0)
                {
                   
                $afiiliate['phone'] = html_escape($this->input->post('phone'));
                $afiiliate['user_id'] = $that_id;
                $afiiliate['unique_identifier'] = $afiiliate['user_id'].strtolower(random(10));
                $afiiliate['status'] = 1;
                $this->db->insert('affiliator_status', $afiiliate);
                }
                else
                {
                    $this->session->set_flashdata('error_message', get_phrase('Already Exist in Affiliator list'));
                }
                
              }
              else
              {
             
                $generate_a_password=strtoupper(bin2hex(random_bytes(4)));
                $data['password'] = sha1($generate_a_password);

                $this->db->insert('users', $data);
                $user_id = $this->db->insert_id();
               // $this->user_model->update_unique_identifier($user_id);
                $afiiliate['phone'] = html_escape($this->input->post('phone'));
                $afiiliate['user_id'] = $user_id;
                $afiiliate['unique_identifier'] = $afiiliate['user_id'].strtolower(random(10));
                $afiiliate['status'] = 1;
                $this->db->insert('affiliator_status', $afiiliate);
                $this->user_model->upload_user_image($data['image']);
                $this->email_model->become_a_course_affiliator_by_admin($data['email'],$data['first_name'],$generate_a_password);
                $this->session->set_flashdata('flash_message', get_phrase('added_successfully'));


              }

           
               
            
       
    }

    public function get_duplicate_affiliator_exiistence($email = "")
    {
        // for getting the parent of ref code 
        return  $this->db->get_where('users', array('email' => $email))->result_array();
    }

    public function get_user_by_unique_identifier($unique_identifier = "")
    {
        // for getting the parent of ref code 
        return  $this->db->get_where('affiliator_status', array('unique_identifier' => $unique_identifier))->row_array();
    }
    public function get_all_active_and_suspend_affiliators()
    {

       $this->db->where('status !=', "0");
       return $this->db->get('affiliator_status');
    }

    function configure_affiliator_payment($payout_id = false){

        $payout_details =  $this->db->where('id', $payout_id)->get('course_affiliation_payment')->row_array();
        
        $amount = $payout_details['amount'];
        $items = array();
        $total_payable_amount = 0;
        $instructor_details = $this->user_model->get_all_user($payout_details['user_id'])->row_array();
            
        //item detail
        $item_details['payout_id'] = $payout_details['id'];
        $item_details['title'] = get_phrase('pay_to').' '.$instructor_details['first_name'].' '.$instructor_details['last_name'];
        $item_details['thumbnail'] = '';
        $item_details['creator_id'] = '';
        $item_details['discount_flag'] = 0;
        $item_details['discounted_price'] = $amount;
        $item_details['price'] = $amount;
        $item_details['actual_price'] = $amount;
        $item_details['sub_items'] = array();
        $items[0] = $item_details;
        //ended item details

        //common structure for all payment gateways and all type of payment
        $data['total_payable_amount'] = $amount;
        $data['items'] = $items;
        $data['is_instructor_payout_user_id'] = $payout_details['user_id'];
        $data['payment_title'] = get_phrase('pay_for_affiliator_payout');
        $data['success_url'] = site_url('addons/affiliate_course/payment_success');
        $data['cancel_url'] = site_url('payment');
        $data['back_url'] = site_url('addons/affiliate_course/affiliation_course_payouts');

        $this->session->set_userdata('payment_details', $data);

    }



}
