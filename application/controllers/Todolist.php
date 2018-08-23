<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Todolist extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model', 'todolist_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Maintenace / Repair(s)';
        $this->template->load('default_front', 'todolist/index', $data);
    }

    public function get() {
        $final['recordsTotal'] = $this->todolist_model->get_todolists('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $todolists = $this->todolist_model->get_todolists('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($todolists as $key => $val) {
            $todolists[$key] = $val;
            $todolists[$key]['responsive'] = '';
            $todolists[$key]['created'] = date('Y-m-d', strtotime($val['created']));
        }
        $final['data'] = $todolists;
        echo json_encode($final);
    }
    
    public function load_manage_todolist() {
        $todolist_id = base64_decode($this->input->post('todolist'));
        $provider_id = my_provider_id();
        $this->data['todolist_info'] = $this->cms_model->get_all_details('provider_todolists', array('provider_id' => $provider_id, 'id' => $todolist_id))->row_array();
        $this->load->view('todolist/manage_todolist', $this->data);
    }
    
    function save() {
        $todolist_id = base64_decode($this->input->post('todolist_id'));
        $this->form_validation->set_rules("description", "Description", "trim|required");
        $this->form_validation->set_rules("status", "Status", "trim|required");
        $provider_id = my_provider_id();
        if (($this->form_validation->run() == TRUE)) {
            $status = $this->input->post('status');
            $description = $this->input->post('description');

            $input = array(
                'status' => $status,
                'description' => !empty($description) ? $description : NULL,
                'provider_id' => $provider_id,
            );
            if (empty($todolist_id)) {
                $this->cms_model->master_insert($input, "provider_todolists");
            } else {
                $condition = array('id' => $todolist_id);
                $this->cms_model->master_update("provider_todolists", $input, $condition);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }
    
    public function delete_todolist() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $todolist = $this->cms_model->get_all_details('provider_todolists', array('id' => $id))->row_array();
            if (!empty($todolist)) {
                $this->cms_model->master_delete('provider_todolists', array('id' => $id));
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