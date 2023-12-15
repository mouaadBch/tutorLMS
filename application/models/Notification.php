<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_notification_by_id_and_userId($id, $user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->where('id', $id);
        $this->db->order_by('created_at', 'desc');
        return $this->db->get('notification')->row();
    }

    function get_all_notification($user_id, $status = 0)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->where('status', $status);
        $this->db->order_by('created_at', 'desc');
        return $this->db->get('notification')->result_array();
    }

    function get_notification_numbre($user_id, $status = 0)
    {
        $this->db->select('COUNT(id) as numbre');
        $this->db->where('user_id', $user_id);
        $this->db->where('status', $status);
        $notification = $this->db->get('notification')->row();
        return $notification->numbre;
    }

    function edit_status($idNotification, $user_id)
    {
        $notification = $this->get_notification_by_id_and_userId($idNotification, $user_id);
        if ($notification) {
            $this->db->where('id', $idNotification);
            if ($notification->status == 0) {
                $status = 1;
            } else {
                $status = 0;
            }
            $this->db->update('notification', ['status' => $status, 'update_at' => strtotime('now')]);
        } else {
            return false;
        }
        return true;
    }

    function push_notification($user_id, $title, $message, $link = '', $status='')
    {
        $data['user_id'] = $user_id;
        $data['title'] = $title;
        $data['message'] = $message;
        $data['link'] = $link;
        $data['created_at'] = strtotime('now');
        $data['status'] = $status;
        try {
            $this->db->insert('notification', $data);
        } catch (Exception $th) {
            return false;
        }
        return true;
    }
}
