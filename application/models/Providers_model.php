<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Providers_model extends CI_Model {

    public function find_provider_by_email($email_add) {
        $this->db->select('id as user_id, name');
        $this->db->from('providers');
        $this->db->where('email', $email_add);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_provider_details($user_id) {
        $this->db->select('*');
        $this->db->from('providers');
        $this->db->where('id', $user_id);
        $this->db->limit(1);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_provider_profile($user_id) {
        $this->db->select('*');
        $this->db->from('providers');
        $this->db->where('id', $user_id);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function check_unique_email_for_provider($where) {
        $this->db->where($where);
        $query = $this->db->get("providers");
        return $query->num_rows();
    }

    public function get_profile($user_id) {
        $this->db->select('*,u.id as uid');
        $this->db->from('providers' . ' u');
        $this->db->where(array('u.id' => $user_id));
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_all_details($table = '', $condition = '', $sortArr = '', $limitArr = '') {
        if ($sortArr != '' && is_array($sortArr)) {
            foreach ($sortArr as $sortRow) {
                if (is_array($sortRow)) {
                    $this->db->order_by($sortRow ['field'], $sortRow ['type']);
                }
            }
        }
        if ($limitArr != '') {
            return $this->db->get_where($table, $condition, $limitArr['l1'], $limitArr['l2']);
        } else {
            return $this->db->get_where($table, $condition);
        }
    }

    public function get_providers($type) {
        $columns = array('u.id', 'u.name', 'u.email', 'u.status', 'u.created');
        $keyword = $this->input->get('search');

        $this->db->select('u.id,u.status,u.name as user_name,u.email,u.created');
        $this->db->where('u.type != "admin"');

        if (!empty($keyword['value'])) {
            $where = '(u.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.status LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }
        $user_data = $this->session->userdata('session_info');
        if (!empty($user_data)) {
            if ($user_data['user_id'] != 1) {
                $this->db->where(array('u.id !=' => 31));
            }
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("providers u");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("providers u");
            return $query;
        }
    }

    function get_info($id = null, $tablename = "providers", $field = "id") {
        $this->db->select('*');
        if ($id) {
            $this->db->where($field, $id);
        }
        $this->db->from($tablename);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
