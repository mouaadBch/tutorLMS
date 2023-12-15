<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course_analytics_model extends CI_Model
{

	function __construct() {
		parent::__construct();
	}

	function get_course_progress_data($course_id = ""){
		$analytics_values = array(0,0,0,0,0,0,0,0,0,0);

		$this->db->where('course_progress <=', 10);
		$this->db->where('course_id', $course_id);
		$analytics_values[0] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 11);
		$this->db->where('course_progress <=', 20);
		$this->db->where('course_id', $course_id);
		$analytics_values[1] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 21);
		$this->db->where('course_progress <=', 30);
		$this->db->where('course_id', $course_id);
		$analytics_values[2] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 31);
		$this->db->where('course_progress <=', 40);
		$this->db->where('course_id', $course_id);
		$analytics_values[3] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 41);
		$this->db->where('course_progress <=', 50);
		$this->db->where('course_id', $course_id);
		$analytics_values[4] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 51);
		$this->db->where('course_progress <=', 60);
		$this->db->where('course_id', $course_id);
		$analytics_values[5] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 61);
		$this->db->where('course_progress <=', 70);
		$this->db->where('course_id', $course_id);
		$analytics_values[6] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 71);
		$this->db->where('course_progress <=', 80);
		$this->db->where('course_id', $course_id);
		$analytics_values[7] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 81);
		$this->db->where('course_progress <=', 90);
		$this->db->where('course_id', $course_id);
		$analytics_values[8] = $this->db->get_where('watch_histories')->num_rows();

		$this->db->where('course_progress >=', 100);
		$this->db->where('course_id', $course_id);
		$analytics_values[9] = $this->db->get_where('watch_histories')->num_rows();

	    return json_encode($analytics_values);
	}

	function get_course_enrolment_data($course_id = "", $date = ""){
		$response = array();
		$enrolment_analytics_values = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

		$filter_date = $this->input->post('filter_date');

		if(isset($filter_date) && $filter_date != ""){
			$timestamp = strtotime('1 '.$filter_date);
		}else{
			$timestamp = strtotime(date('d M Y h:i:s'));
		}
		$year = date('Y', $timestamp);
		$month = date('m', $timestamp);
		$month_name = date('M', $timestamp);
		$total_days_in_this_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$start_date = strtotime('01 '.$month_name.' '.$year);
		$end_date = strtotime($total_days_in_this_month.' '.$month_name.' '.$year.' 23:59:59');
		
	    $this->db->where('date_added >=', $start_date);
	    $this->db->where('date_added <=', $end_date);
	    $this->db->where('course_id', $course_id);
	    $query = $this->db->get('enrol');

	    foreach($query->result_array() as $key => $row){
	    	$enrolled_day = date('d', $row['date_added']);
	    	$enrolment_analytics_values[$enrolled_day-1] = $enrolment_analytics_values[$enrolled_day-1] + 1;
	    }
	    $response['enrolment_analytics_values'] = json_encode($enrolment_analytics_values);
	    $response['total_days_in_this_month'] = $total_days_in_this_month;
	    $response['selected_month'] = $month;
	    $response['selected_year'] = $year;
	    $response['total_enrol_student_number'] = $query->num_rows();

	    return $response;

	}



}