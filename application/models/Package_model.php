<?php

class Package_model extends CI_Model {

    function __construct() {
        parent:: __construct();
    }

    function save($fields, $package_id = '', $table = "packages") {
        if (!empty($package_id)) {
            $this->db->where('id', $package_id);
            $this->db->update($table, $fields);
            return true;
        } else {
            $this->db->insert($table, $fields);
            return $this->db->insert_id();
        }
    }

    function get_provider_packages_services($provider_id) {
        $this->db->select('s.id as service_id,s.service_name');
        $this->db->join('services s', 'FIND_IN_SET(s.id,p.services) > 0', 'left');

        $this->db->where('p.id', $provider_id);
        $this->db->from('providers p');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function get_provider_packages($result, $provider_id, $type, $addon_service = null) {
        $columns = array('p.id', 'p.name', 'p.price', 'p.type');
        $keyword = $this->input->get('search');
        if ($type == 'bundle') {
            $like_where = 'p.type="Basic" AND p.services LIKE "%,%"';
            $this->db->where($like_where);
        } else {
            if ($addon_service) {
                $this->db->where(array('p.services' => $type, 'p.type' => 'Addon'));
            } else {
                $this->db->where(array('p.services' => $type, 'p.type' => 'Basic'));
            }
        }
        if (!empty($keyword['value'])) {
            $where = '(p.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR p.price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . '  OR p.type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }

        $this->db->select('p.id as id,p.name as name,p.price as price,p.type as type');
        $this->db->where('p.provider_id', $provider_id);
        $this->db->from('packages p');
        $query = $this->db->get();
        if ($result == 'count') {
            return $query->num_rows();
        } else {
            return $query;
        }
    }

    function get_all_setupfees($provider_id = null, $services = array()) {
        $this->db->select('*');
        if (!empty($provider_id)) {
            $this->db->where('provider_id', $provider_id);
        }
        if (!empty($services) && is_array($services)) {
            $this->db->where_in('service_id', $services);
        }
        $this->db->from('setup_fees');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    function get_setupfee_info($provience = null, $provider_id = null) {
        $this->db->select('*');
        if (!empty($provience) && !empty($provider_id)) {
            $this->db->where('provider_id', $provider_id);
        } else {
            $this->db->where('(provider_id is NULL OR provider_id = 0)');
        }
        $this->db->where('provience', $provience);
        $this->db->from('setup_fees');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function get_setup_fees($provider_id, $type, $by_service = false) {
        $columns = array('sf.id', 'sf.provience', 'sf.fees', 'sf.provider_id');
        $keyword = $this->input->get('search');

        if (!empty($keyword['value'])) {
            $where = '(sf.provience LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR sf.fees LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }
        $this->db->where('sf.provider_id', $provider_id);
        if (is_numeric($by_service)) {
            $this->db->where('sf.service_id', $by_service);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("setup_fees sf");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("setup_fees sf");
            return $query;
        }
    }

    function is_rental_service_available($provider_id) {
        $this->db->select('s.id as service_id');
        $this->db->join('services s', 'FIND_IN_SET(s.id,p.services) > 0 AND s.service_name LIKE "%Rental%"', 'inner');

        $this->db->where('p.id', $provider_id);
        $this->db->from('providers p');
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_package_info($package_str) {
        $package_arr = explode(',', $package_str);
        $this->db->select('*', false);
        $this->db->where_in('id', $package_arr);
        $this->db->from('packages');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        }
        return FALSE;
    }

}
