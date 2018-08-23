<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Devices extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('device_model');
        $this->load->model('cms_model');
        $this->load->model('upload_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Devices';
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $data['folders'] = $this->cms_model->get_all_details('folders', array('user_id' => $user_id))->result_array();
        $this->template->load('default_front', 'devices/index', $data);
    }

    public function view($device_id) {
        $data['title'] = 'Devices';
        $device_id = base64_decode($device_id);
        $data['device'] = $device = $this->cms_model->get_all_details('metatable', array('id' => $device_id))->row_array();

        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];
        if ($user_type != 'admin') {
            if ($device['user_id'] != $user_id) {
                $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                redirect('/dashboard');
            }
        }

        if (!empty($device)) {
            $data['user_folders'] = $this->upload_model->get_assigned_folders($device_id)->result_array();
            $this->template->load('default_front', 'devices/view', $data);
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('devices');
        }
    }

    public function get() {
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];

        $final['recordsTotal'] = $this->device_model->get_devices('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $devices = $this->device_model->get_devices('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($devices as $key => $val) {
            $devices[$key] = $val;
            $devices[$key]['sr_no'] = $start++;
            $devices[$key]['responsive'] = '';
        }
        $final['data'] = $devices;
        echo json_encode($final);
    }

    public function add() {        
        $code = $this->input->post('code');
        $device_name = $this->input->post('device_name');
        $this->form_validation->set_rules("code", "Device Code", "trim|required");
        $this->form_validation->set_rules("device_name", "Device Name", "trim|required");
        $this->form_validation->set_rules("folders[]", "Folders", "trim|required");
        if (($this->form_validation->run() == TRUE)) {
            $user_data = $this->session->userdata('session_info');
            $user_id = $user_data['user_id'];

            $input = array(
                'code' => $code,
                'device_name' => $device_name,
                'user_id' => $user_id
            );
            $folders = $this->input->post('folders');
            $device_id = $this->cms_model->master_insert($input, "metatable");

            if (!empty($folders)) {
                $insert_foldes = array();
                foreach ($folders as $f) {
                    $insert_device[] = array(
                        'device_id' => $device_id,
                        'folder_id' => $f,
                        'user_id' => $user_id,
                    );
                }
                if (!empty($insert_device)) {
                    $this->cms_model->insert_batch('device_folder', $insert_device);
                }
            }

            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
        exit;
    }

    public function delete($id) {
        $id = base64_decode($id);
        if (!empty($id)) {
            $device_folder = $this->cms_model->master_delete('device_folder', array('device_id' => $id));
            $device = $this->cms_model->master_delete('metatable', array('id' => $id));
            $this->session->set_flashdata('success', 'Device deleted successfully.');
            redirect('devices');
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('devices');
        }
    }

    public function assign_folder($device_id) {
        $device_id = base64_decode($device_id);
        $data['device_id'] = $device_id;
        $device = $this->cms_model->get_all_details('metatable', array('id' => $device_id))->row_array();
        $data['folders'] = $this->cms_model->get_all_details('folders', array('user_id' => $device['user_id']))->result_array();
        $data['user_folders'] = $this->cms_model->get_all_details('device_folder', array('user_id' => $device['user_id'], 'device_id' => $device_id))->result_array();
        $this->load->view('devices/assign_folder', $data);
    }

    public function save_assign_folder() {
        $this->form_validation->set_rules("device_id", "Device", "trim|required");
        if (($this->form_validation->run() == TRUE)) {
            $device_id = $this->input->post('device_id');
            $device = $this->cms_model->get_all_details('metatable', array('id' => $device_id))->row_array();
            $folders = $this->input->post('folders');
            $this->cms_model->master_delete('device_folder', array('user_id' => $device['user_id'], 'device_id' => $device_id));
            if (!empty($folders)) {
                $insert_foldes = array();
                foreach ($folders as $f) {
                    $insert_device[] = array(
                        'device_id' => $device_id,
                        'folder_id' => $f,
                        'user_id' => $device['user_id'],
                    );
                }
                if (!empty($insert_device)) {
                    $this->cms_model->insert_batch('device_folder', $insert_device);
                }
            }
            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
        exit;
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Devices.php */