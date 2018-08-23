<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Todolist_model extends CI_Model {

    function get_todolists($type= 'result') {
        $provider_id = my_provider_id();
        $columns = array('td.id', 'td.description', 'td.created', 'td.status');
        $keyword = $this->input->get('search');

        $this->db->select('td.*');
        $this->db->where(array('td.provider_id' => $provider_id));
        if (!empty($keyword['value'])) {
            $where = '(td.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR td.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR td.status LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            $where .= 'OR DATE_FORMAT(td.created,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= ')';
            $this->db->having($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }

        if ($type == 'count') {
            $query = $this->db->get("provider_todolists td");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("provider_todolists td");
            return $query;
        }
    }

}

?>