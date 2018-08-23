<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_system extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Payment System';
        $provider_id = my_provider_id();
        $data['provider_info'] = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
        $data['payment_system'] = $this->cms_model->get_all_details('payment_system', array('provider_id' => $provider_id))->row_array();
        $this->template->load('default_front', 'payment_system/index', $data);
    }

    public function save() {
        $provider_id = my_provider_id();
        $payment_system_info = $this->cms_model->get_all_details('payment_system', array('provider_id' => $provider_id))->row_array();
        $payment_system = $this->input->post('payment_system');
        $validate = true;
        if ($payment_system) {
            $validate = false;
            $this->form_validation->set_rules('payment_system_type', 'Payment System Type', 'trim|required');
            $this->form_validation->set_rules('payment_system_user', 'Payment System User', 'trim|required');
            $this->form_validation->set_rules('payment_system_password', 'Payment System Password', 'trim|required');
            $this->form_validation->set_rules('payment_system_signature', 'Payment System Signature', 'trim|required');
        } else {
            $validate = true;
        }

        if ($this->form_validation->run() == TRUE || $validate) {
            $fields = array(
                'payment_system_type' => $this->input->post('payment_system_type'),
                'payment_system_user' => $this->input->post('payment_system_user'),
                'payment_system_password' => $this->input->post('payment_system_password'),
                'payment_system_signature' => $this->input->post('payment_system_signature'),
                'provider_id' => $provider_id
            );
            if (!empty($payment_system_info)) {
                $this->cms_model->master_update('payment_system', $fields, array('provider_id' => $provider_id));
            } else {
                $this->cms_model->master_insert($fields, 'payment_system');
            }
            $this->cms_model->master_update('providers', array('payment_system' => !empty($payment_system) ? 1 : 0), array('id' => $provider_id));
            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
        exit;
    }

}

/* End of file Payment_system.php */
/* Location: ./application/controllers/Payment_system.php */