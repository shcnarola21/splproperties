<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notes extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('customers_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Notes';
        $this->template->load('default_front', 'notes/index', $data);
    }

    function get_notes() {
        $provider_id = my_provider_id();

        $final['recordsTotal'] = $this->customers_model->get_customer_notes($provider_id, 'count', false);
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $notes = $this->customers_model->get_customer_notes($provider_id, 'result', false)->result_array();
        foreach ($notes as $key => $val) {
            $notes[$key] = $val;
            $notes[$key]['responsive'] = '';
        }
        $final['data'] = $notes;
        echo json_encode($final);
    }

    public function load_manage_notes() {
        $note_id = base64_decode($this->input->post('note'));
        $provider_id = my_provider_id();
        $this->data['provider_note'] = true;
        $this->data['customers'] = $this->cms_model->get_all_details('customers', array('provider_id' => $provider_id))->result_array();
        $this->data['note_info'] = $this->cms_model->get_all_details('customer_notes', array('id' => $note_id))->row_array();
        echo $this->load->view('customers/manage_note', $this->data, TRUE);
    }

    function save_note() {
        $note_id = base64_decode($this->input->post('note_id'));
        $this->form_validation->set_rules("note_for", "Customer", "trim|required");
        $this->form_validation->set_rules("notes", "Note", "trim|required");
        $provider_id = my_provider_id();
        if (($this->form_validation->run() == TRUE)) {
            $customer_id = base64_decode($this->input->post('note_for'));
            $user_data = $this->session->userdata('session_info');
            $added_by = NULL;
            if (is_provider() && my_provider_id() != $user_data['user_id']) {
                $added_by = $user_data['user_id'];
            }
            $input = array(
                'notes' => $this->input->post('notes'),
                'provider_id' => $provider_id,
            );
            if (empty($note_id)) {
                $input['customer_id'] = $customer_id;
                $input['added_by'] = $added_by;
                $this->cms_model->master_insert($input, "customer_notes");
            } else {
                $condition = array('id' => $note_id);
                $this->cms_model->master_update("customer_notes", $input, $condition);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }

}
