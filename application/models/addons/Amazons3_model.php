<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amazons3_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function update_amazons3_settings()
    {
        $data['value'] = html_escape($this->input->post('access_key'));
        $this->db->where('key', 'amazon_s3_access_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('secret_key'));
        $this->db->where('key', 'amazon_s3_secret_key');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('bucket_name'));
        $this->db->where('key', 'amazon_s3_bucket_name');
        $this->db->update('settings', $data);

        $data['value'] = html_escape($this->input->post('region_name'));
        $this->db->where('key', 'amazon_s3_region_name');
        $this->db->update('settings', $data);
    }
}
