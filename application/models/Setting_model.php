<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

    function get_templates($type) {
        $columns = array('e.id', 'e.email_for', 'e.email_subject', 'e.email_text');
        $keyword = $this->input->get('search');
        $this->db->select('e.*');

        if (!empty($keyword['value'])) {
            $where = '(e.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.email_for LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR e.email_subject LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR m.created LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $order = $this->input->get('order');
        $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get("email_templates e");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("email_templates e");
            return $query;
        }
    }

    function get_package_history($type) {
        $provider_id = my_provider_id();
        $columns = array('h.id', 'h.package_id', 'h.package_name', 'h.percentage', 'h.org_price', 'h.new_price', 'h.created');
        $keyword = $this->input->get('search');
        $this->db->select('h.*,DATE(h.created) as h_date');

        if (!empty($keyword['value'])) {
            $where = '(h.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR h.package_id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR h.package_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR h.percentage LIKE "%' . $keyword['value'] . '%" OR h.org_price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR  h.new_price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR  h.created LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $order = $this->input->get('order');
        $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        if ($type == 'count') {
            $query = $this->db->get("history_auto_increase_package_prices h");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("history_auto_increase_package_prices h");
            return $query;
        }
    }

}

/* End of file Setting_model.php */
/* Location: ./application/models/Setting_model.php */
