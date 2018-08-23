<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Device_model extends CI_Model {

    public function get_devices($type) {
        $columns = array('m.id', 'm.user_id', 'm.code', 'm.hardware_id', 'm.status');
        $keyword = $this->input->get('search');
        $user_data = $this->session->userdata('session_info');
        
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];

        $this->db->select('m.*,u.name as user_name');
        if ($user_type == 'user') {
            $this->db->where(array(
                'm.user_id' => $user_id
            ));
        }

        if (!empty($keyword['value'])) {
            $where = '(m.code LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.hardware_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.created LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->join("users" . ' as u', 'm.user_id=u.id', 'left');
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("metatable m");            
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("metatable m");
            return $query;
        }
    }

    public function get_assigned_devices($folder_id) {
        $user_type = $this->session->userdata('type');
        $this->db->select('d.*,m.hardware_id,m.device_name');
        $this->db->where(array(
            'd.folder_id' => $folder_id,
        ));
        $this->db->join("metatable" . ' as m', 'd.device_id=m.id', 'left');
        $query = $this->db->get("device_folder d");
        return $query;
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
