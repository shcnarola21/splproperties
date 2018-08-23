<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    function customer_package($provider_id) {
        $this->db->select('c.*, CONCAT(property_id, "_",unit) as property_unit,p.price as package_price');
        $this->db->join('packages p', 'c.basic_package=p.id', 'left');
        $this->db->from('customers c');

        $this->db->where(array('c.provider_id' => $provider_id, 'status' => "active"));

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}

/* End of file Dashboard_model.php */
/* Location: ./application/models/Dashboard_model.php */