<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keylessaccess extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model', 'keyless_access_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Keyless Access';
        $provider_id = my_provider_id();
        $data['properties'] = $properties = $this->cms_model->get_all_details('properties', array('provider_id' => $provider_id))->result_array();
        $this->template->load('default_front', 'keylessaccess/index', $data);
    }

    public function get($property_id) {
        $final['recordsTotal'] = $this->keyless_access_model->get_keyless_access($property_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $keyless_access = $this->keyless_access_model->get_keyless_access($property_id, 'result')->result_array();
        $property_zones = $this->cms_model->get_all_details('property_zones', array('property_id' => $property_id))->result_array();
        $zones_ids = array();
        if (!empty($property_zones)) {
            $zones_ids = array_column($property_zones, 'id');
        }

        foreach ($keyless_access as $key => $val) {
            $keyless_access[$key] = $val;
            $keyless_access[$key]['responsive'] = '';
            $keyless_access[$key]['created'] = date('Y-m-d', strtotime($val['created']));

            $current_zones = explode(',', $val['zone_id']);
            if (!empty($zones_ids)) {
                foreach ($current_zones as $k => $val) {
                    $curent_zone_index = array_search($val, $zones_ids);
                    if (!is_bool($curent_zone_index)) {
                        $current_info = array('name' => $property_zones[$curent_zone_index]['name'],
                            'password' => $property_zones[$curent_zone_index]['password']);
                        $keyless_access[$key]['zone_info'][$property_zones[$curent_zone_index]['id']] = $current_info;
                    }
                }
            }
        }
        $final['data'] = $keyless_access;
        echo json_encode($final);
    }

    public function load_manage_keylessaccess() {
        $property_id = $this->input->post('property');
        $keyless_id = base64_decode($this->input->post('keyless_access'));

        $this->data['property_id'] = base64_encode($property_id);
        $this->data['property_info'] = $this->cms_model->get_all_details('properties', array('id' => $property_id), null, null, 'units')->row_array();
        $this->data['property_zones'] = $this->cms_model->get_all_details('property_zones', array('property_id' => $property_id))->result_array();

        $this->data['keyless_access'] = $this->cms_model->get_all_details('keyless_accesses', array('id' => $keyless_id))->row_array();
        $this->load->view('keylessaccess/manage_keylessaccess', $this->data);
    }

    function save() {
        $property_id = base64_decode($this->input->post('property_id'));
        $keyless_access = base64_decode($this->input->post('keyless_access'));

        $this->form_validation->set_rules("fob", "FOB ID", "trim|required");
        $this->form_validation->set_rules("unit", "Unit", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        $this->form_validation->set_rules("zones[]", "zone", "trim|required");
        if (($this->form_validation->run() == TRUE)) {
            $fob = $this->input->post('fob');
            $password = $this->input->post('password');
            $zones = $this->input->post('zones');
            $unit = $this->input->post('unit');

            $input = array(
                'property_id' => $property_id,
                'unit_no' => $unit,
                'zone_id' => implode(',', $zones),
                'fob' => $fob,
                'password' => $password,
            );
            if (empty($keyless_access)) {
                $this->cms_model->master_insert($input, "keyless_accesses");
            } else {
                $condition = array('id' => $keyless_access);
                $this->cms_model->master_update("keyless_accesses", $input, $condition);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function delete_keylessaccess() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $keyless_accesses = $this->cms_model->get_all_details('keyless_accesses', array('id' => $id))->row_array();
            if (!empty($keyless_accesses)) {
                $this->cms_model->master_delete('keyless_accesses', array('id' => $id));
                echo '1^';
            } else {
                echo '0^';
            }
        } else {
            echo '0^';
        }
    }

}

?>