<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model','dashboard_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Dashboard';
        if (is_rental_service_available()) {
//            pr($this->config->item('dashboard_property_unit'));
            $data['dashboard_property_unit'] = $this->config->item('dashboard_property_unit');
            $provider_id = my_provider_id();
            $available_units = 0;
            $data['properties'] = $this->cms_model->get_all_details('properties', array('provider_id' => $provider_id))->result_array();
            $select = 'SUM(CASE WHEN status="active" THEN 1 ELSE 0 END) total_active,'
                    . 'SUM(CASE WHEN status="suspended" THEN 1 ELSE 0 END) total_suspended,'
                    . 'SUM(CASE WHEN status="terminated" THEN 1 ELSE 0 END) total_terminated,'
                    . 'SUM(CASE WHEN unit IS NOT NULL AND status="active" THEN 1 ELSE 0 END) total_units,'
                    . 'COUNT(c.cid) total_customer';
            $data['total_counts'] = $this->cms_model->get_all_details('customers c', array('provider_id' => $provider_id), '', '', $select)->row_array();
//            $customers = $this->cms_model->get_all_details('customers', array('provider_id' => $provider_id, 'status' => "active"), '', '', '*, CONCAT(property_id, "_",unit) as property_unit')->result_array();
            $customers = $this->dashboard_model->customer_package($provider_id);
            $data['provider_info'] = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
            if (!empty($data['properties'])) {
                $available_units = array_sum(array_column($data['properties'], 'units'));
            }
            if (!empty($data['total_counts'])) {
                $available_units = $available_units - $data['total_counts']['total_units'];
            }
            if (!empty($customers)) {
                $customer_units = array_column($customers, 'property_unit');
                foreach ($data['properties'] as $key => $value) {
                    for ($i = 1; $i <= $value['units']; $i++) {
                        $unit_string = $value['id'] . '_' . $i;
                        $customer_property_index = array_search($unit_string, $customer_units);

                        if (!is_bool($customer_property_index)) {
//                            if ($customers[$customer_property_index]['property_id'] == $value['id']) {
                            $data['properties'][$key]['customer_info'][$i] = array('cid' => $customers[$customer_property_index]['cid'],
                                'rating' => $customers[$customer_property_index]['rating'],
                                'name' => $customers[$customer_property_index]['name'],
                                'email' => $customers[$customer_property_index]['email'],
                                'phone' => $customers[$customer_property_index]['phone'],
                                'package_price' => $customers[$customer_property_index]['package_price'],
                                'secondary_check' => $customers[$customer_property_index]['secondary_check'],
                                'secondary_name' => $customers[$customer_property_index]['secondary_name'],
                                'secondary_phone' => $customers[$customer_property_index]['secondary_phone'],
                            );
//                            }
                        }
                    }
                }
            }
            $data['available_units'] = $available_units;
//                                pr($data['properties']);
//                                pr('<br/>');
        }
        $this->template->load('default_front', 'dashboard', $data);
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */