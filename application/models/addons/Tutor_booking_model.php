<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tutor_booking_model extends CI_Model
{

    public function get_tutor_categories($param1 = "")
    {
        if ($param1 != "") {
            $this->db->where('id', $param1);
        }
        $this->db->where('parent', 0);
        return $this->db->get('tutor_category');
    }


    public function add_tuitor_category()
    {

        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));


        // CHECK IF THE CATEGORY NAME ALREADY EXISTS
        $this->db->where('name', $data['name']);
        $this->db->or_where('slug', $data['slug']);
        $previous_data = $this->db->get('tutor_category')->num_rows();

        if ($previous_data == 0) {

            $this->db->insert('tutor_category', $data);
            return true;
        }

        return false;
    }

    public function edit_tutor_category($param1)
    {
        $data['name']   = html_escape($this->input->post('name'));
        $data['parent'] = html_escape($this->input->post('parent'));
        $data['slug']   = slugify(html_escape($this->input->post('name')));

        // CHECK IF THE CATEGORY NAME ALREADY EXISTS
        $this->db->where('name', $data['name']);
        $this->db->or_where('slug', $data['slug']);
        $previous_data = $this->db->get('tutor_category')->result_array();

        $checker = true;
        foreach ($previous_data as $row) {
            if ($row['id'] != $param1) {
                $checker = false;
                break;
            }
        }

        if ($checker) {

            $this->db->where('id', $param1);
            $this->db->update('tutor_category', $data);

            return true;
        }
        return false;
    }

    public function delete_tutor_category($category_id)
    {
        $this->db->where('id', $category_id);
        $this->db->delete('tutor_category');

        $this->db->where('parent', $category_id);
        $this->db->delete('tutor_category');
    }

    public function get_tutor_sub_categories($parent_id = "")
    {
        return $this->db->get_where('tutor_category', array('parent' => $parent_id))->result_array();
    }

    public function category_wise_subcategory($parent_id = "")
    {
        return $this->db->get_where('tutor_category', array('parent' => $parent_id));
    }

    public function get_tutor_category_details_by_id($id)
    {
        return $this->db->get_where('tutor_category', array('id' => $id));
    }

    public function add_schedule()
    {

        $info_from_view = $_POST;


        $data['title'] = $info_from_view['title'];
        $data['category_id'] = $info_from_view['category_id'];
        $data['sub_category_id'] = $info_from_view['sub_category_id'];
        $data['price_type'] = $info_from_view['price_type'];
        $data['price'] = $info_from_view['price'];
        $data['tutor_id'] = $this->session->userdata('user_id');
        $data['date_added'] = strtotime(Date('Y-m-d\TH:i'));
        $data['tution_class_type'] = $info_from_view['class_type'];
        $data['meeting_link'] = $info_from_view['meeting_link'];
        $data['message'] = $info_from_view['message'];

        $this->db->insert('tutor_booking', $data);
        $booking_id = $this->db->insert_id();

        $total_no_schedule = sizeof($info_from_view['start_time']) - 1;
        /* $single_schedule; */
        $single_schedule['booking_id'] = $booking_id;
        $single_schedule['tutor_id'] = $data['tutor_id'];

        $m = 1;
        for ($i = 0; $i < $total_no_schedule; $i++) {
            $tution_type = $m . "_tution_type_indentify";
            $single_schedule['tuition_type'] = $info_from_view[$tution_type];
            $single_schedule['tution_class_type'] = $data['tution_class_type'];
            $single_schedule['start_time'] = strtotime($info_from_view['start_time'][$i]);
            $duration = (int)$info_from_view['end_time'][$i];
            $single_schedule['end_time'] = strtotime(" +$duration minutes", strtotime($info_from_view['start_time'][$i]));

            //add valid time ( date till tution active)

            if ($single_schedule['tuition_type'] != 1) {
                $time_in_hour = substr($info_from_view['start_time'][$i], strpos($info_from_view['start_time'][$i], "T"));
                $valid_time = $info_from_view['valid_till'][$i] . $time_in_hour;
                $valid_time = strtotime(" +$duration minutes", strtotime($valid_time));
                $single_schedule['valid_till'] = $valid_time;

                $diff = $single_schedule['valid_till'] - $single_schedule['start_time'];
                $class_active_number_of_days   = floor($diff / (60 * 60 * 24));
            } else {
                $single_schedule['valid_till'] = $single_schedule['end_time'];
            }

            if ($single_schedule['tuition_type'] == 0) {
                $array = $info_from_view[$m . '_day'];
                $array_size = sizeof($array);
                $day = array();

                for ($p = 0; $p < $array_size; $p++) {
                    $day[$p] = $array[$p];
                }

                $single_schedule['selected_days'] = json_encode($day);
            }

            //need_work
            $check_dublicate_schedule = $this->db->get_where('tutor_schedule', array('tutor_id' => $this->session->userdata('user_id'), 'active_status' => 1));

            if ($check_dublicate_schedule->num_rows() > 0) {
                $check_dublicate_schedule = $check_dublicate_schedule->result_array();

                foreach ($check_dublicate_schedule as $dublicate) {

                    $check_start = $dublicate['start_time'];
                    $check_end = $dublicate['end_time'];

                    if (($check_start <= $single_schedule['start_time']) && ($single_schedule['start_time'] <= $check_end)) {

                        $this->db->where('id', $single_schedule['booking_id']);
                        $this->db->delete('tutor_booking');
                        $this->db->where('booking_id', $single_schedule['booking_id']);
                        $this->db->delete('tutor_schedule');
                        $this->session->set_flashdata('error_message', get_phrase('Sorry duplicate schedule inserted '));
                        redirect(site_url('addons/tutor_booking/schedule'), 'refresh');
                    } else {
                    }
                }
            }

            if ($single_schedule['tuition_type'] == 7) {
                $first_start_Time = $single_schedule['start_time'];
                $first_end_Time = $single_schedule['end_time'];

                for ($i = 0; $i <= $class_active_number_of_days; $i++) {
                    $single_schedule['start_time'] = strtotime(" +$i days", $first_start_Time);
                    $single_schedule['end_time'] = strtotime(" +$i days", $first_end_Time);

                    $this->db->insert('tutor_schedule', $single_schedule);
                }
            } elseif ($single_schedule['tuition_type'] == 0) {
                $first_start_Time = $single_schedule['start_time'];
                $first_end_Time = $single_schedule['end_time'];

                $day_array = json_decode($single_schedule['selected_days'], true);

                for ($i = 0; $i <= $class_active_number_of_days; $i++) {
                    $single_schedule['start_time'] = strtotime(" +$i days", $first_start_Time);
                    $single_schedule['end_time'] = strtotime(" +$i days", $first_end_Time);

                    if (in_array(strtolower(date('l', $single_schedule['start_time'])), $day_array)) {
                        $this->db->insert('tutor_schedule', $single_schedule);
                    }
                }
            } else {
                $this->db->insert('tutor_schedule', $single_schedule);
            }



            $m++;

            if (isset($single_schedule['selected_days'])) {

                unset($single_schedule['selected_days']);
            }
        }
    }

    public function edit_schedule()
    {

        $info_from_view = $_POST;



        $data['title'] = $info_from_view['title'];
        $data['category_id'] = $info_from_view['category_id'];
        $data['sub_category_id'] = $info_from_view['sub_category_id'];
        $data['price_type'] = $info_from_view['price_type'];
        $data['price'] = $info_from_view['price'];
        $data['tutor_id'] = $this->session->userdata('user_id');
        $data['tution_class_type'] = $info_from_view['class_type'];
        $data['meeting_link'] = $info_from_view['meeting_link'];
        $data['message'] = $info_from_view['message'];

        $booking_id = $info_from_view['booking_id'];
        $this->db->where('id', $booking_id);
        $this->db->update('tutor_booking', $data);

        $schedule_id_in_Array = $info_from_view['schedule_ids'];

        $total_no_schedule = sizeof($info_from_view['start_time']) - 1;
        /* $single_schedule; */
        $single_schedule['booking_id'] = $info_from_view['booking_id'];
        $single_schedule['tutor_id'] = $info_from_view['tutor_id'];
        $not_in_array = array();





        $m = 1;
        for ($i = 0; $i < $total_no_schedule; $i++) {
            if (isset($info_from_view["schedule_id_" . $m])) {
                if (in_array($info_from_view["schedule_id_" . $m], $schedule_id_in_Array)) {
                    $tution_type = $m . "_tution_type_indentify";
                    $single_schedule['tuition_type'] = $info_from_view[$tution_type];
                    $single_schedule['tution_class_type'] = $data['tution_class_type'];
                    $single_schedule['start_time'] = strtotime($info_from_view['start_time'][$i]);
                    $duration = (int)$info_from_view['end_time'][$i];
                    $single_schedule['end_time'] = strtotime(" +$duration minutes", strtotime($info_from_view['start_time'][$i]));



                    $this->db->where('id', $info_from_view["schedule_id_" . $m]);
                    $the_schedule = $this->db->get('tutor_schedule')->row_array();

                    if ($single_schedule['start_time'] > $the_schedule['valid_till']) {
                        $this->session->set_flashdata('error_message', get_phrase('You have exceed timing '));
                        redirect(site_url('addons/tutor_booking/edit_booking_by_id/' . $the_schedule['booking_id']), 'refresh');
                    }

                    if ($single_schedule['tuition_type'] == 0) {
                        $single_schedule['selected_days'] = json_encode($info_from_view[$m . '_day']);
                    }

                    $key = array_search($info_from_view["schedule_id_" . $m], $schedule_id_in_Array);
                    unset($schedule_id_in_Array[$key]);


                    $this->db->where('tutor_id', $single_schedule['tutor_id']);
                    $this->db->where('id != ', $info_from_view["schedule_id_" . $m]);
                    $this->db->where('active_status', 1);
                    $check_dublicate_schedule = $this->db->get('tutor_schedule');


                    if ($check_dublicate_schedule->num_rows() > 0) {
                        $check_dublicate_schedule = $check_dublicate_schedule->result_array();

                        foreach ($check_dublicate_schedule as $dublicate) {

                            $check_start = $dublicate['start_time'];
                            $check_end = $dublicate['end_time'];

                            if (($single_schedule['start_time'] >=  $check_start) && ($single_schedule['start_time'] <= $check_end)) {
                            } else {

                                $this->db->where('id', $info_from_view["schedule_id_" . $m]);
                                $this->db->update('tutor_schedule', $single_schedule);
                                if (isset($single_schedule['selected_days'])) {
                                    unset($single_schedule['selected_days']);
                                }
                            }
                        }
                    }
                } elseif ($info_from_view["schedule_id_" . $m] == 0) {
                    $tution_type = $m . "_tution_type_indentify";
                    $single_schedule['tuition_type'] = $info_from_view[$tution_type];
                    $single_schedule['tution_class_type'] = $data['tution_class_type'];
                    $single_schedule['start_time'] = strtotime($info_from_view['start_time'][$i]);
                    $duration = (int)$info_from_view['end_time'][$i];
                    $single_schedule['end_time'] = strtotime(" +$duration minutes", strtotime($info_from_view['start_time'][$i]));

                    $time_in_hour = substr($info_from_view['start_time'][$i], strpos($info_from_view['start_time'][$i], "T"));
                    $valid_time = $info_from_view['valid_till'][$i] . $time_in_hour;
                    $valid_time = strtotime(" +$duration minutes", strtotime($valid_time));
                    $single_schedule['valid_till'] = $valid_time;

                    $diff = $single_schedule['valid_till'] - $single_schedule['start_time'];
                    $class_active_number_of_days   = floor($diff / (60 * 60 * 24));


                    if ($single_schedule['tuition_type'] == 0) {
                        $single_schedule['selected_days'] = json_encode($info_from_view[$m . '_day']);
                    }

                    $this->db->where('tutor_id', $single_schedule['tutor_id']);
                    $this->db->where('id != ', $info_from_view["schedule_id_" . $m]);
                    $check_dublicate_schedule = $this->db->get('tutor_schedule');


                    if ($check_dublicate_schedule->num_rows() > 0) {
                        $check_dublicate_schedule = $check_dublicate_schedule->result_array();

                        foreach ($check_dublicate_schedule as $dublicate) {

                            $check_start = $dublicate['start_time'];
                            $check_end = $dublicate['end_time'];

                            if (($single_schedule['start_time'] >=  $check_start) && ($single_schedule['start_time'] <= $check_end)) {

                                $this->session->set_flashdata('error_message', get_phrase('Sorry duplicate schedule inserted '));
                                redirect(site_url('addons/tutor_booking/schedule'), 'refresh');
                            } else {
                            }
                        }
                    }

                    $this->db->insert('tutor_schedule', $single_schedule);



                    if (isset($single_schedule['selected_days'])) {
                        unset($single_schedule['selected_days']);
                    }
                }
            }
            $m++;
        }

        $no_of_delet = sizeof($schedule_id_in_Array);
        if ($no_of_delet > 0) {
            foreach ($schedule_id_in_Array as $value) {
                $temp['active_status'] = 0;
                $this->db->where('id', $value);
                $this->db->update('tutor_schedule', $temp);
            }
        }
    }

    public function get_all_tutor_booked_list()
    {
        if ($this->session->userdata('role_id') == 1) {
            $this->db->order_by('id', 'desc');
            $this->db->where('active_status', 1);
            return $this->db->get('tutor_booking');
        } elseif ($this->session->userdata('role_id') == 2) {
            $this->db->order_by('id', 'desc');
            $this->db->where('active_status', 1);
            return $this->db->get_where('tutor_booking', array('tutor_id' => $this->session->userdata('user_id')));
        }
    }

    public function get_all_tutor_inactive_booked_list()
    {
        if ($this->session->userdata('role_id') == 1) {
            $this->db->order_by('id', 'desc');
            $this->db->where('active_status', 0);
            return $this->db->get('tutor_booking');
        } elseif ($this->session->userdata('role_id') == 2) {
            $this->db->order_by('id', 'desc');
            $this->db->where('active_status', 0);
            return $this->db->get_where('tutor_booking', array('tutor_id' => $this->session->userdata('user_id')));
        }
    }

    public function get_schedule_by_bokking_id($booking_id = "")
    {

        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;

        $this->db->where('active_status', 1);
        $this->db->where('start_time >= ', $date);
        $this->db->where('booking_id', $booking_id);
        return  $this->db->get('tutor_schedule');
    }

    public function get_inactive_schedule_by_bokking_id($booking_id = "")
    {

        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;

        $this->db->where('active_status', 0);
        $this->db->where('start_time >= ', $date);
        $this->db->where('booking_id', $booking_id);
        return  $this->db->get('tutor_schedule');
    }

    public function get_achieve_schedule_by_bokking_id($booking_id = "")
    {

        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;

        $this->db->where('start_time < ', $date);
        $this->db->where('booking_id', $booking_id);
        $this->db->where('active_status', 1);
        return  $this->db->get('tutor_schedule');
    }

    public function get_inactive_schedule_list_count($booking_id = "")
    {




        $this->db->where('booking_id', $booking_id);
        $this->db->where('active_status', 0);
        return  $this->db->get('tutor_schedule')->num_rows();
    }

    public function get_inactive_achieve_schedule_by_bokking_id($booking_id = "")
    {

        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;

        $this->db->where('start_time < ', $date);
        $this->db->where('booking_id', $booking_id);
        $this->db->where('active_status', 0);
        return  $this->db->get('tutor_schedule');
    }

    public function get_tutor_booking_data_by_bokking_id($booking_id = "")
    {
        return $this->db->get_where('tutor_booking', array('id' => $booking_id));
    }

    public function update_booking_schedule($id)
    {


        $updater = array(
            'title' => html_escape($this->input->post('title')),
            'category_id' => html_escape($this->input->post('category_id')),
            'sub_category_id' => html_escape($this->input->post('sub_category_id')),
            'price_type' => html_escape($this->input->post('price_type')),
            'tution_class_type' => html_escape($this->input->post('class_type')),
            'price' => html_escape($this->input->post('price')),
            'meeting_link' => html_escape($this->input->post('meeting_link')),
        );
        $this->db->where('id', $id);
        $this->db->update('tutor_booking', $updater);
    }

    public function disable_booking($id)
    {

        $data['active_status'] = 0;

        $this->db->where('id', $id);
        $this->db->update('tutor_booking', $data);

        $this->db->where('booking_id', $id);
        $this->db->update('tutor_schedule', $data);
    }

    public function enable_booking($id)
    {

        $data['active_status'] = 1;

        $this->db->where('id', $id);
        $this->db->update('tutor_booking', $data);

        $this->db->where('booking_id', $id);
        $this->db->update('tutor_schedule', $data);
    }


    public function update_schedule($id)
    {
        $data['start_time'] = $this->input->post('start_time');
        $data['end_time'] = $this->input->post('end_time');
        $duration = (int)$data['end_time'];


        $data['end_time'] = strtotime(" +$duration minutes", strtotime($data['start_time']));
        $data['start_time'] = strtotime($data['start_time']);

        $this->db->where('id', $id);
        $the_schedule = $this->db->get('tutor_schedule')->row_array();

        if ($data['start_time'] > $the_schedule['valid_till']) {
            $this->session->set_flashdata('error_message', get_phrase('You have exceed timing '));
            redirect(site_url('addons/tutor_booking/edit_booking_by_id/' . $the_schedule['booking_id']), 'refresh');
        }




        $data['tuition_type'] = html_escape($this->input->post('1_tution_type_indentify'));
        if ($data['tuition_type'] == 0) {
            $array_days = html_escape($this->input->post('day'));
            $data['selected_days'] = json_encode($array_days);
            $data['valid_till'] = strtotime('+30 day', $data['start_time']);
        }
        if ($data['tuition_type'] == 7) {
            $data['valid_till'] = strtotime('+30 day', $data['start_time']);
        }

        if ($data['tuition_type'] == 1) {
            $data['valid_till'] = $data['start_time'];
        }



        $this->db->where('tutor_id', $this->session->userdata('user_id'));
        $this->db->where('id != ', $id);
        $this->db->where('active_status', 1);
        $check_dublicate_schedule = $this->db->get('tutor_schedule');


        if ($check_dublicate_schedule->num_rows() > 0) {
            $check_dublicate_schedule = $check_dublicate_schedule->result_array();

            foreach ($check_dublicate_schedule as $dublicate) {

                $check_start = $dublicate['start_time'];
                $check_end = $dublicate['end_time'];

                if (($data['start_time'] >=  $check_start) && ($data['start_time'] <= $check_end)) {

                    $this->session->set_flashdata('error_message', get_phrase('Sorry duplicate schedule inserted '));
                    redirect(site_url('addons/tutor_booking/tutor_booking_list'), 'refresh');
                } else {
                }
            }
        }




        $this->db->where('id', $id);
        $this->db->update('tutor_schedule', $data);
    }

    public function get_tutor_schedule_data_by_bokking_id($id = "")
    {
        return $this->db->get_where('tutor_schedule', array('id' => $id));
    }

    public function inactive_schedule($id)
    {



        $get_schedule = $this->db->get_where('tutor_schedule', array('id' => $id))->row_array();
        $get_booking_details = $this->db->get_where('tutor_booking', array('id' => $get_schedule['booking_id']))->row_array();
        $a_booking_all_shedule = $this->db->get_where('tutor_schedule', array('booking_id' => $get_schedule['booking_id']))->num_rows();

        $data['active_status'] = 0;

        $this->db->where('id', $id);
        $this->db->update('tutor_schedule', $data);
    }


    public function active_schedule($id)
    {
        $get_schedule = $this->db->get_where('tutor_schedule', array('id' => $id))->row_array();
        $get_booking_details = $this->db->get_where('tutor_booking', array('id' => $get_schedule['booking_id']))->row_array();


        $this->db->where('tutor_id', $this->session->userdata('user_id'));
        $this->db->where('id != ', $id);
        $this->db->where('active_status', 1);
        $check_dublicate_schedule = $this->db->get('tutor_schedule');


        if ($check_dublicate_schedule->num_rows() > 0) {
            $check_dublicate_schedule = $check_dublicate_schedule->result_array();

            foreach ($check_dublicate_schedule as $dublicate) {

                $check_start = $dublicate['start_time'];
                $check_end = $dublicate['end_time'];

                if (($get_schedule['start_time'] >=  $check_start) && ($get_schedule['start_time'] <= $check_end)) {

                    $this->session->set_flashdata('error_message', get_phrase('Sorry duplicate schedule inserted '));
                    redirect(site_url('addons/tutor_booking/tutor_inactive_schedule_list_by_booking_id/' . $get_booking_details['id']), 'refresh');
                }
            }
        }

        $data['active_status'] = 1;

        $this->db->where('id', $id);
        $this->db->update('tutor_schedule', $data);

        $this->session->set_flashdata('flash_message', get_phrase('Schedule activated'));
        redirect(site_url('addons/tutor_booking/tutor_booking_list/' . $get_booking_details['id']), 'refresh');
    }



    public function get_all_schedules_before_filter($limit, $offset, $action)
    {


        $date = strtotime(Date('Y-m-d\TH:i'));

        $this->db->where('valid_till >= ', $date);
        $this->db->where('status', 0);
        $this->db->where('active_status', 1);
        $up_coming_schedule = $this->db->get('tutor_schedule');

        $bookings_ids = array();
        $tutor_ids = array();
        $bookings = array();




        if ($up_coming_schedule->num_rows() > 0) {
            $up_coming_schedule = $up_coming_schedule->result_array();

            foreach ($up_coming_schedule as $find_booking) {
                if (!in_array($find_booking['booking_id'], $bookings_ids)) {
                    array_push($bookings_ids, $find_booking['booking_id']);
                    $this->db->where('active_status', 1);
                    $this->db->where('id', $find_booking['booking_id']);
                    $each_booking = $this->db->get('tutor_booking');
                    array_push($bookings, $each_booking);
                }
            }


            $this->db->where_in('id', $bookings_ids);

            if ($action = "play") {
                $this->db->limit($limit, $offset);
            }
            $this->db->where('active_status', 1);
            $booking_list = $this->db->get('tutor_booking');
        } else {
            $this->db->where('date_added > ', $date);

            if ($action = "play") {
                $this->db->limit($limit, $offset);
                $this->db->where('active_status', 1);
            }
            $booking_list = $this->db->get('tutor_booking');
        }









        $tutor_ids = array();
        $prices = array();
        $this->db->where('active_status', 1);
        $all_bookings_done_by_tutor = $this->db->get('tutor_booking');

        if ($all_bookings_done_by_tutor->num_rows() > 0) {
            $all_bookings_done_by_tutor = $all_bookings_done_by_tutor->result_array();
            foreach ($all_bookings_done_by_tutor as $tutor_id) {

                if (!in_array($tutor_id['tutor_id'], $tutor_ids)) {
                    array_push($tutor_ids, $tutor_id['tutor_id']);
                }

                if (!in_array($tutor_id['price'], $prices)) {
                    array_push($prices, $tutor_id['price']);
                }
            }
        }


        if (!empty($prices)) {
            rsort($prices);
            $value['highest_price'] = $prices[0];
        } else {

            $value['highest_price'] = 0;
        }




        $value['bookings'] = $booking_list;
        $value['tutors'] = $tutor_ids;



        return $value;


        // return $up_coming_schedule;

    }


    public function get_main_category()
    {

        $this->db->where('parent', 0);
        return $this->db->get('tutor_category');
    }

    public function get_sub_category()
    {

        $this->db->where('parent !=', 0);
        return $this->db->get('tutor_category');
    }


    public function get_all_schedules_after_filter($filter, $limit, $offset, $action)
    {

        $filter = $filter;




        $date = strtotime(Date('Y-m-d\TH:i'));
        $this->db->where('valid_till >= ', $date);
        $this->db->where('active_status', 1);
        $searched_data = array();

        if (isset($filter['searched_tutors'])) {
            $tutors_array = array();
            foreach ($filter['searched_tutors'] as $searched_tutor) {
                array_push($tutors_array, $searched_tutor);
            }

            $this->db->where_in('tutor_id', $tutors_array);
            $searched_data['searched_tutors'] = $filter['searched_tutors'];
        }

        if (isset($filter['searched_duration'])) {

            $duration_array = array();
            foreach ($filter['searched_duration'] as $durationr) {
                array_push($duration_array, $durationr);
            }
            $this->db->where_in('tuition_type', $duration_array);
            $searched_data['searched_duration'] = $filter['searched_duration'];
        }




        $this->db->where('status', 0);
        $this->db->where('active_status', 1);
        $after_booking_filter = $this->db->get('tutor_schedule');





        $bookings_ids = array();
        $tutor_ids = array();
        $bookings = array();




        if ($after_booking_filter->num_rows() > 0) {


            foreach ($after_booking_filter->result_array() as $find_booking) {
                if (!in_array($find_booking['booking_id'], $bookings_ids)) {
                    array_push($bookings_ids, $find_booking['booking_id']);

                    if (!in_array($find_booking['tutor_id'], $tutor_ids)) {
                        array_push($tutor_ids, $find_booking['tutor_id']);
                    }




                    $this->db->where('id', $find_booking['booking_id']);
                    $this->db->where('active_status', 1);
                    $each_booking = $this->db->get('tutor_booking')->row_array();
                    array_push($bookings, $each_booking);
                }
            }

            $this->db->where_in('id', $bookings_ids);
        }

        $this->db->where('active_status', 1);

        if (isset($filter['searched_word'])) {
            $this->db->like('title', $filter['searched_word']);
            $searched_data['searched_word'] = $filter['searched_word'];
        }



        if (isset($filter['searched_tution_class_type'])) {

            $tution_class_type_array = array(3);
            foreach ($filter['searched_tution_class_type'] as $tution_class_type) {
                array_push($tution_class_type_array, $tution_class_type);
            }

            $this->db->where_in('tution_class_type', $tution_class_type_array);
            $searched_data['searched_tution_class_type'] = $filter['searched_tution_class_type'];
        }




        if (isset($filter['searched_main_category'])) {

            $main_category_array = array();
            foreach ($filter['searched_main_category'] as $main_category) {
                array_push($main_category_array, $main_category);
            }

            $this->db->where_in('category_id', $main_category_array);
            $searched_data['searched_main_category'] = $filter['searched_main_category'];
        }


        if (isset($filter['searched_sub_category'])) {

            $sub_category_array = array();
            foreach ($filter['searched_sub_category'] as $sub_category) {
                array_push($sub_category_array, $sub_category);
            }

            $this->db->where_in('sub_category_id', $sub_category_array);
            $searched_data['searched_sub_category'] = $filter['searched_sub_category'];
        }


        if (isset($filter['price_max']) || isset($filter['price_min'])) {

            if (isset($filter['price_max'])) {
                $f = $filter['price_max'];
            } else {
                $f = 100;
            }

            if (isset($filter['price_min'])) {
                $l = $filter['price_min'];
            } else {
                $l = 1;
            }

            $this->db->where(" price BETWEEN $l AND $f ");

            $searched_data['price_min'] = $filter['price_min'];
            $searched_data['price_max'] = $filter['price_max'];
        }



        if (isset($filter['searched_price_type'])) {

            $price_type_array = array();
            foreach ($filter['searched_price_type'] as $price_type) {
                array_push($price_type_array, $price_type);
            }


            $this->db->where_in('price_type', $price_type_array);

            $searched_data['searched_price_type'] = $filter['searched_price_type'];
        }


        if ($action = "play") {
            $this->db->limit($limit, $offset);
        }
        $booking_after_filter = $this->db->get('tutor_booking');

        if ($after_booking_filter->num_rows() == 0) {
            $this->db->where('active_status', 5);

            $booking_after_filter = $this->db->get('tutor_booking');
        }




        $tutor_ids = array();
        $prices = array();
        $this->db->where('active_status', 1);
        $all_bookings_done_by_tutor = $this->db->get('tutor_booking');

        if ($all_bookings_done_by_tutor->num_rows() > 0) {
            $all_bookings_done_by_tutor = $all_bookings_done_by_tutor->result_array();
            foreach ($all_bookings_done_by_tutor as $tutor_id) {

                if (!in_array($tutor_id['tutor_id'], $tutor_ids)) {
                    array_push($tutor_ids, $tutor_id['tutor_id']);
                }

                if (!in_array($tutor_id['price'], $prices)) {
                    array_push($prices, $tutor_id['price']);
                }
            }
        }

        if (!empty($prices)) {
            rsort($prices);
            $searched_data['highest_price'] = $prices[0];
        } else {
            $searched_data['highest_price'] = 0;
        }




        $value['bookings'] = $bookings;
        $value['tutors'] = $tutor_ids;
        $value['booking_after_filter'] = $booking_after_filter;
        $value['searched_data'] = $searched_data;

        return $value;


        // return $up_coming_schedule;

    }

    public function save_tutor_review($review)
    {

        $data['user_id'] = $this->session->userdata('user_id');
        $data['tutor_id'] = $review['tutor_id'];
        $data['review'] = $review['review'];
        $data['rating'] = $review['rating'];
        $data['status'] = 1;
        $data['date'] = time();

        $this->db->insert('tutor_reviews', $data);
    }

    public function update_tutor_review($review)
    {

        $data['review'] = $review['review'];
        $data['rating'] = $review['rating'];

        $this->db->where('user_id', $review['student_id']);
        $this->db->where('tutor_id', $review['tutor_id']);
        $this->db->update('tutor_reviews', $data);
    }

    public function get_tutor_review($id)
    {
        $this->db->order_by('id', 'desc');
        $this->db->where('tutor_id', $id);
        return $this->db->get('tutor_reviews');
    }

    public function given_review($tutor_id)
    {

        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->get('tutor_reviews');
    }



    public function get_tutor_all_schedules($tutor_id)
    {


        $date = strtotime(Date('Y-m-d\TH:i'));
        $this->db->where('valid_till >= ', $date);
        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('active_status', 1);
        $this->db->order_by('start_time','ASC');
        $tutor_schedule = $this->db->get('tutor_schedule');




        return $tutor_schedule;
    }


    public function total_hours_taught($tutor_id)
    {



        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('status', 1);
        $tutor_schedule = $this->db->get('tutor_schedule');
        $total_hours_taught = 0;

        if ($tutor_schedule->num_rows() > 0) {
            $schedules = $tutor_schedule->result_array();

            foreach ($schedules as $schedule) {

                $time = $schedule['end_time'] - $schedule['start_time'];

                $total_hours_taught += $time / (60 * 60);
            }
        }

        return $total_hours_taught;
    }


    public function total_student($tutor_id)
    {


        $this->db->select('*');
        $this->db->distinct();
        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('status', 1);
        $this->db->where('student_id >', 0);
        return $this->db->get('tutor_schedule')->num_rows();
    }


    public function get_schedule_info($schedule_id)
    {
        $this->db->where('id', $schedule_id);
        $schedule = $this->db->get('tutor_schedule')->row_array();

        return $schedule;
    }

    public function get_booking_info($booking_id)
    {

        $this->db->where('id', $booking_id);
        $booking = $this->db->get('tutor_booking')->row_array();



        return $booking;
    }



    public function complete_schedule_booking($user_id, $purchesed_schedule_id, $purchesed_booking_id, $payment_type, $transaction_id, $booking_amount)
    {

        $this->db->where('id', $purchesed_booking_id);
        $paying_booking = $this->db->get('tutor_booking')->row_array();

        $this->db->where('id', $purchesed_schedule_id);
        $this->db->where('booking_id', $purchesed_booking_id);
        $paying_schedule = $this->db->get('tutor_schedule')->row_array();

        $tutor_details = $this->db->get_where('users', array('id' => $paying_schedule['tutor_id']))->row_array();

        if ($tutor_details['role_id'] != 1) {
            // $instructor_revenue_percentage = get_settings('instructor_revenue');
            $instructor_revenue_percentage = $tutor_details['percentage_rev_tutor'] ?? get_settings('instructor_revenue');
            $tutor_payment['instructor_revenue'] = ceil(($booking_amount * $instructor_revenue_percentage) / 100);
            $tutor_payment['admin_revenue'] = $booking_amount - $tutor_payment['instructor_revenue'];
        } else {
            $tutor_payment['admin_revenue'] = $booking_amount;
            $tutor_payment['instructor_revenue'] = 0;
        }

        $tutor_payment['student_id'] = $user_id;
        $tutor_payment['tutor_id'] = $paying_schedule['tutor_id'];
        $tutor_payment['booking_id'] = $purchesed_booking_id;
        $tutor_payment['schedule_id'] = $purchesed_schedule_id;
        $tutor_payment['amount'] = $booking_amount;
        $tutor_payment['date_added'] = strtotime(date('m/d/Y'));
        $tutor_payment['payment_type'] = $payment_type;
        $tutor_payment['transaction_id'] = $transaction_id;
        $tutor_payment['last_modified'] = "";

        $this->db->insert('tutor_payment', $tutor_payment);

        $data['student_id'] = $user_id;
        $data['status'] = 1;

        $this->db->where('id', $purchesed_schedule_id);
        $this->db->update('tutor_schedule', $data);
    }



    public function list_of_booked_schedule_student_list($role)
    {

        if ($role != 'admin') {
            $this->db->where('tutor_id', $this->session->userdata('user_id'));
        }
        $this->db->order_by('id', 'desc');
        $this->db->where('status', 1);
        return $this->db->get('tutor_schedule');
    }


    public function list_of_booked_schedule_by_student()
    {
        $user_id = $this->session->userdata('user_id');

        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;
        $this->db->order_by('start_time', 'ASC');
        $this->db->where('start_time >= ', $date);
        $this->db->where('student_id', $user_id);
        $this->db->where('status', 1);
        return $this->db->get('tutor_schedule');
    }


    public function list_of_booked_achieve_schedule_by_student()
    {
        $user_id = $this->session->userdata('user_id');
        $date = strtotime(Date('Y-m-d\TH:i')) - 9000;
        $this->db->order_by('id', 'desc');
        $this->db->where('start_time < ', $date);
        $this->db->where('student_id', $user_id);
        $this->db->where('status', 1);
        return $this->db->get('tutor_schedule');
    }


    public function booked_tuition_payment()
    {

        $user_id = $this->session->userdata('user_id');
        $this->db->order_by('id', 'desc');
        $this->db->where('student_id', $user_id);

        return $this->db->get('tutor_payment');
    }








    public function check_access_to_write_a_review($tutor_id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('status', 1);
        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('student_id', $user_id);
        $result = $this->db->get('tutor_schedule');



        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function check_if_already_wrote_a_review($tutor_id)
    {

        $this->db->where('tutor_id', $tutor_id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $result = $this->db->get('tutor_reviews');

        if ($result->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }



    public function update_live_class_settings()
    {
        if (empty($this->input->post('zoom_meeting_id')) || empty($this->input->post('zoom_meeting_password'))) {
            $this->session->set_flashdata('error_message', get_phrase('nothing_can_be_empty'));
            redirect(site_url('addons/tutor_booking/zoom_live_class_settings'), 'refresh');
        }
        $data['zoom_meeting_id'] = $this->input->post('zoom_meeting_id');
        $data['zoom_meeting_password'] = $this->input->post('zoom_meeting_password');
        $data['zoom_api_key'] = $this->input->post('zoom_api_key');
        $data['zoom_secret_key'] = $this->input->post('zoom_secret_key');
        $tutor_id = $this->session->userdata('user_id');

        $count = $this->db->get_where('tutor_live_class_settings', array('tutor_id' => $tutor_id))->num_rows();

        if ($count > 0) {
            $this->db->where('tutor_id', $tutor_id);
            $this->db->update('tutor_live_class_settings', $data);
        } else {
            $data['tutor_id'] = $tutor_id;
            $this->db->insert('tutor_live_class_settings', $data);
        }

        $this->session->set_flashdata('flash_message', get_phrase('zoom_account_has_been_updated'));
        redirect(site_url('addons/tutor_booking/zoom_live_class_settings'), 'refresh');
    }


    public function get_schedule_by_id($schedule = "")
    {
        return $this->db->get_where('tutor_schedule', array('id' => $schedule));
    }


    public function get_booking_by_schedule_id($schedule = "")
    {
        $schedule_details = $this->db->get_where('tutor_schedule', array('id' => $schedule))->row_array();
        return $this->db->get_where('tutor_booking', array('id' => $schedule_details['booking_id']));
    }

    public function get_live_class_details($schedule_id = "")
    {
        $schedule = $this->db->get_where('tutor_schedule', array('id' => $schedule_id))->row_array();

        return $this->db->get_where('tutor_live_class_settings', array('tutor_id' => $schedule['tutor_id']))->row_array();
    }
}
