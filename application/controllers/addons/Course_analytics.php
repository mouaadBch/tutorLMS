<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_analytics extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        $this->load->model('addons/course_analytics_model');
        /*cache control*/

    }

    public function load_analytics_body($course_id = "") {
        $total_enroll_histories = $this->crud_model->enrol_history($course_id);
        $page_data['total_enroll_students'] = $total_enroll_histories->num_rows();
        $page_data['analytics_values'] = $this->course_analytics_model->get_course_progress_data($course_id);
        $page_data['course_id'] = $course_id;
        $this->load->view('backend/'.strtolower($this->session->userdata('role')).'/course_analytics_body', $page_data);
    }    

    public function get_course_enrolment_data($course_id = "") {
        $page_data['course_added_date'] = $this->crud_model->get_course_by_id($course_id)->row('date_added');
        $response = $this->course_analytics_model->get_course_enrolment_data($course_id);
        $page_data['total_days_in_this_month'] = $response['total_days_in_this_month'];
        $page_data['enrollment_analytics_values'] = $response['enrolment_analytics_values'];
        $page_data['selected_month'] = $response['selected_month'];
        $page_data['selected_year'] = $response['selected_year'];
        $page_data['total_enrol_student_number'] = $response['total_enrol_student_number'];
        $page_data['course_id'] = $course_id;
        $this->load->view('backend/'.strtolower($this->session->userdata('role')).'/course_enrolment_analytics_body', $page_data);
    }

    public function about_of_course_analytics(){
        echo "<p>You will be able to see the course progress for all enrolled students here. Which will help you understand the needs of your students.</p><p>On the left side of the chart, you will see the range of the top number of students, and on the bottom of the chart, you will see the range of percentage. Also, you will be able to see the table of the chart on the right.</p>";
    }

    public function about_of_course_enrolments(){
        echo "<p>You will be able to see the student enrolment reports.</p><p>On the left side of the chart, you will see the number of enrolled students, and on the right of the chart, you will see the enrolled total days of the month. Also, you will able to see the table of the chart on the right side.</p>";
    }
}