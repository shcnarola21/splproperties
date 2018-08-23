<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function find_user_by_email($email_add) {
        $this->db->select('id as user_id, name');
        $this->db->from('users');
        $this->db->where('email', $email_add);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_user_details($user_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $this->db->limit(1);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_user_profile($user_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function check_unique_email_for_user($where) {
        $this->db->where($where);
        $query = $this->db->get("users");
        return $query->num_rows();
    }

    public function get_profile($user_id) {
        $this->db->select('*,u.id as uid');
        $this->db->from('users' . ' u');
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

    public function get_users($type) {
        $users = $this->session->userdata('session_info');
        $parent = isset($users['parent']) ? $users['parent'] : '' ;

        $columns = array('u.id', 'u.name','u.email','u.status' ,'u.created');
        $keyword = $this->input->get('search');

        $this->db->select('u.id,u.status,u.name as user_name,u.email,u.created');
        if(is_master_admin())
        {
            if(!empty($parent))
            {
                $this->db->where(array('u.parent' =>  $users['user_id'],
                'u.is_provider_user' => '0',
                'u.parent !=' => '0',
                ));
            }else{
                $this->db->where(array('u.parent !=' => '0',
                'u.is_provider_user' => '0'
                ));
            }
           
        }else
        {
            $this->db->where(array('u.parent' =>  $users['user_id'],
            'is_provider_user' => '1'
            ));
        }
        
        if (!empty($keyword['value'])) {
            $where = '(u.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.status LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("users u");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("users u");
            return $query;
        }
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
