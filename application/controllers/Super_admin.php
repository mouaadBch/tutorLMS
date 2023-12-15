<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Super_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        $this->user_model->check_session_data('admin');

        if (!has_permission('admins'))
            redirect(site_url('login'), 'refresh');

        // ini_set('memory_limit', '128M');
    }

    public function index()
    {
        $parametres = $this->db->get('parametre_superadmin')->result_array();
        $page_data['parametres'] = $parametres;
        $page_data['page_name'] = 'parametre_superadmin';
        $page_data['page_title'] = get_phrase('parametre superadmin');
        $this->load->view('backend/index', $page_data);
    }

    #update properties for this site web
    public function update_property_siteweb()
    {
        $nb_user = $this->input->post('max_nombre_user');
        $nb_courses = $this->input->post('max_nombre_courses');
        $nb_tutor = $this->input->post('maximum_number_of_tutor');
        $data = [
            'max_nombre_user' => $nb_user,
            'max_nombre_courses' => $nb_courses,
            'maximum_number_of_tutor' => $nb_tutor,
        ];

        foreach ($data as $key => $value) {
            if ($this->db->where('option', $key)->get('parametre_superadmin')->num_rows() > 0) {
                $this->db->where('option', $key)->update('parametre_superadmin', ["value" => $value]);
            } else {
                $this->db->insert('parametre_superadmin', ['option' => $key, "value" => $value]);
            }
        }
        redirect(site_url('super_admin/index'), 'refresh');
    }
}
