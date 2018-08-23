<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Messaging extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        is_user_logged();
    }

    public function index() {
        $this->data = array();
        $this->data['title'] = "Messaging";
        $provider_id = my_provider_id();
        $this->data['canned_responses'] = $this->cms_model->get_all_details('canned_responses', array('provider_id' => $provider_id))->result_array();
        $this->data['customers'] = $this->cms_model->get_all_details('customers', array('provider_id' => $provider_id))->row_array();
        $this->template->load('default_front', 'messaging/index', $this->data);
    }

    function send() {
        $to = $this->input->post('to');
        if ($to == 'specific') {
            $this->form_validation->set_rules('to', 'Email Address', 'trim|required');
        }
        $this->form_validation->set_rules('to', 'To', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $provider_id = my_provider_id();
            $send_email_config = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
            if (!empty($send_email_config)) {
                $message = $this->load->view('email_template/default_header.php', array(), true);
                $message .= $this->input->post('message');
                $message .= $this->load->view('email_template/default_footer.php', array(), true);

                $emails = array();
                $email_address = "";
                if ($to == 'specific') {
                    $email_address = $this->input->post('email_to');
                    $emails[] = $email_address;
                }
                if ($to == 'all') {
                    $customer_arr = $this->cms_model->get_all_details('customers', array('provider_id' => $provider_id))->result_array();
                    if (!empty($customer_arr)) {
                        foreach ($customer_arr as $c) {
                            $emails[] = $c['email'];
                        }
                    }
                }
                $email_array = array(
                    'to' => $emails,
                    'subject' => $this->input->post('subject'),
                    'body_messages' => $message
                );

                if (!send_email($email_array)) {
                    echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>Something went wrong! We are not able to send email.</div><br>";
                } else {
                    echo "1^";
                }
            } else {
                echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>SMTP detail not found.Please Enter Send Email Configuration</div><br>";
            }
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

}
