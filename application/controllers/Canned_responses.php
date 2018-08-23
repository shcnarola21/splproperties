<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canned_responses extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model', 'canned_responses_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Canned Responses';
        $this->template->load('default_front', 'canned_responses/index', $data);
    }

    public function add() {
        $data['title'] = 'Add Canned Responses';
        $this->template->load('default_front', 'canned_responses/add', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Canned Responses';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['canned_responses'] = $this->cms_model->get_all_details('canned_responses', array('id' => $id))->row_array();
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('canned_responses');
        }
        $this->template->load('default_front', 'canned_responses/add', $data);
    }

    public function get() {
        $final['recordsTotal'] = $this->canned_responses_model->get_all_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $canned_responses = $this->canned_responses_model->get_all_details('results')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($canned_responses as $key => $val) {
            $canned_responses[$key] = $val;
            $canned_responses[$key]['sr_no'] = $start++;
            $canned_responses[$key]['responsive'] = '';
        }
        $final['data'] = $canned_responses;
        echo json_encode($final);
    }

    public function save() {
        $id = base64_decode($this->input->post('id'));

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $fields = array(
                'title' => $this->input->post('title'),
                'subject' => $this->input->post('subject'),
                'body' => $this->input->post('message'),
                'provider_id' => my_provider_id(),
            );

            if (!empty($id)) {
                $this->cms_model->master_update('canned_responses', $fields, array('id' => $id));
            } else {
                $this->cms_model->master_insert($fields, 'canned_responses');
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    public function delete() {
        $cid = $this->input->post('cid');
        $cid = base64_decode($cid);
        $canned_response_info = $this->cms_model->get_info($cid, 'canned_responses', 'id');
        $result = array();
        if (!empty($canned_response_info)) {
            $this->cms_model->master_delete('canned_responses', array('id' => $cid));
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }
        echo json_encode($result);
    }

}

/* End of file Canned_responses.php */
/* Location: ./application/controllers/Canned_responses.php */