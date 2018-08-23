<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('service_model');
        $this->load->model('cms_model');
        is_user_logged();
    }

    public function index() {
        $this->data = array();
        $this->data['title'] = "Services";

        $this->template->load('default_front', 'services/index', $this->data);
    }

    public function get_services() {

        $final['recordsTotal'] = $this->service_model->get_services('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $services = $this->service_model->get_services('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($services as $key => $val) {
            $services[$key] = $val;
            $services[$key]['sr_no'] = $start++;
            $services[$key]['responsive'] = '';
        }
        $final['data'] = $services;
        echo json_encode($final);
    }

    public function save_service() {
        $id = base64_decode($this->input->post('service_id'));
        $name = $this->input->post('name');
        if (is_numeric($id)) {
            $services_arr = $this->service_model->get_info($id, 'services', 'id');
        }

        $this->form_validation->set_rules('name', 'Service Name', 'trim|required|callback_checkUnique_Service', array('checkUnique_Service' => 'Same service exists.'));

        if ($this->form_validation->run() == TRUE) {
            $fields = array(
                'service_name' => $name,
            );

            $this->service_model->save($fields, $id, 'services');
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function load_service($service_id = null) {
        $id = base64_decode($service_id);
        $data['services_arr'] = array();
        if (is_numeric($id)) {
            $data['services_arr'] = $this->service_model->get_info($id, 'services', 'id');
        }
        $this->load->view("services/manage", $data);
    }

    public function delete_service() {
        $service_id = $this->input->post('service_id');
        $service_id = base64_decode($service_id);

        $service_packages_arr = $this->service_model->get_provider_service_packages($service_id);      
        $service_info = $this->service_model->get_info($service_id, 'services', 'id');
        $result = array();
        if (!empty($service_packages_arr)) {
            $this->data['alert_msg'] = "You couldn't delete the service.Below Packages are using " . $service_info[0]['service_name'] . " service.<br>Please change the Package service then try to delete Service.";
            $this->data['service_packages'] = $service_packages_arr;
            $result['status'] = false;
            $result['service_packages'] = $this->load->view('services/list_service_package', $this->data, TRUE);
        } else {
            $this->cms_model->master_delete('services', array('id' => $service_id));
            $result['status'] = true;
        }
        echo json_encode($result);
    }

    public function checkUnique_Service($service_name) {
//        $service_name = trim($this->input->get_post('service_name'));
        $id = base64_decode($this->input->post('service_id'));
        $data = array('service_name' => $service_name);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $service = $this->service_model->check_unique_service($data);
        if ($service > 0) {
            return false;
        } else {
            return true;
        }
    }

}
