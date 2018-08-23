<?php

class Service_model extends CI_Model {

    function __construct() {
        parent:: __construct();
    }

    function save($fields, $package_id = '', $table = "services") {
        if (!empty($package_id)) {
            $this->db->where('id', $package_id);
            $this->db->update($table, $fields);
            return true;
        } else {
            $this->db->insert($table, $fields);
            return $this->db->insert_id();
        }
    }

    function get_info($id, $tablename = "services", $field = "id") {
        $this->db->select('*');
        $this->db->where($field, $id);
        $this->db->from($tablename);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    public function get_services($type) {
        $columns = array('s.id', 's.service_name');
        $keyword = $this->input->get('search');

        if (!empty($keyword['value'])) {
            $where = '(s.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("services s");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("services s");
            return $query;
        }
    }

    public function check_unique_service($where) {
        $this->db->where($where);
        $query = $this->db->get("services");
        return $query->num_rows();
    }

    function get_provider_service_packages($service_id) {
        $this->db->select('s.id as service_id,s.service_name,p.id as package_id,pr.name as provider_name,p.name as package_name,p.type as type,pr.id as provider_id');
        $this->db->join('packages p', 'FIND_IN_SET(' . $service_id . ',p.services) > 0', 'inner');
        $this->db->join('providers pr', 'p.provider_id = pr.id', 'inner');
        $this->db->where('s.id', $service_id);
        $this->db->from('services s');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {            
            return $query->result_array();
        } else {
            return false;
        }
    }

}
