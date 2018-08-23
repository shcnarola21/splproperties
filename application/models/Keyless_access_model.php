<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keyless_access_model extends CI_Model {

    function get_keyless_access($property_id, $type = 'result') {
        $columns = array('ka.fob', 'ka.password', 'ka.unit_no', 'p.address', 'zone_names', 'ka.created');
        $keyword = $this->input->get('search');

        $this->db->select('ka.*,GROUP_CONCAT(pz.name) as zone_names,p.address,p.city,p.state,p.country,p.zip_code,c.name as customer_name,c.email,c.phone,c.secondary_check,c.secondary_name,c.secondary_phone');
        $this->db->join('properties p', 'ka.property_id = p.id', 'left');
        $this->db->join('customers c', 'ka.unit_no=c.unit AND c.property_id=' . $property_id, 'left');
        $this->db->join('property_zones pz', 'ka.property_id = pz.property_id AND FIND_IN_SET(pz.id, ka.zone_id) > 0', 'left');

        $this->db->where(array('ka.property_id' => $property_id));
        if (!empty($keyword['value'])) {
            $where = '(ka.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ka.fob LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR zone_names LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR ka.password LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            $where .= 'OR DATE_FORMAT(ka.created,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= ')';
            $this->db->having($where);
        }
        $this->db->having('length(zone_names) > 0');
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }

        if ($type == 'count') {
            $query = $this->db->get("keyless_accesses ka");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("keyless_accesses ka");
            return $query;
        }
    }

}

?>