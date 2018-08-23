<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canned_responses_model extends CI_Model {

    public function get_all_details($type) {
        $provider_id = my_provider_id();
        $columns = array('r.id', 'r.title', 'r.subject', 'r.created');
        $keyword = $this->input->get('search');

        $this->db->select('*');

        if (!empty($keyword['value'])) {
            $where = '(r.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.title LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.subject LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR r.created LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }


        $this->db->where('r.provider_id', $provider_id);

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("canned_responses r");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("canned_responses r");
            return $query;
        }
    }

}

/* End of file Providers_model.php */
/* Location: ./application/models/Providers_model.php */
