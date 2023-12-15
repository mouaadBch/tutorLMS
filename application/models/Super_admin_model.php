<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Super_admin_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    #get value for parametre
    function get_parametre($key)
    {
        $value = $this->db->where('option', $key)->get('parametre_superadmin')->row_array();
        return $value['value'];
    }
    # get nombre tutors in my site
    function get_nbr_tutor()
    {
        return $this->db->where('role_id', 2)->where('is_instructor', 1)->get('users')->num_rows();
    }

    #get nombre totale user enroled in my site
    function get_nbr_user_enroled()
    {
        $enroled_ebook = $this->db->select('user_id')->group_by('user_id')->get('ebook_payment');
        $nbr_enroled_ebook = $enroled_ebook->num_rows();
        $enroled_ebook_users = $enroled_ebook->result_array();
        $enroled_ebook_user_ids = [];

        foreach ($enroled_ebook_users as $enroled_ebook_user) {
            $enroled_ebook_user_ids[] = $enroled_ebook_user['user_id'];
        }

        $this->db->select('user_id');
        if (!empty($enroled_ebook_user_ids)) {
            $this->db->where_not_in('user_id', $enroled_ebook_user_ids);
        }
        $this->db->where('expiry_date >=', time() + 86400);
        $this->db->or_where('expiry_date IS NULL');
        $this->db->group_by('user_id');

        $nbr_enroled_course = $this->db->get('enrol')->num_rows();

        return $nbr_enroled_ebook +  $nbr_enroled_course;
    }

    function get_nb_courses()
    {
        $nbr_courses = $this->db->get('course')->num_rows();
        $nbr_ebooks = $this->db->get('ebook')->num_rows();
        return $nbr_courses + $nbr_ebooks;
    }
}
