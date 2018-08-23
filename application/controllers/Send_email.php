<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Send_email extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        is_user_logged();
    }

    public function index() {
        $this->data = array();
        $this->data['title'] = "Send Emai Configuration";
        $provider_id = my_provider_id();
        $this->data['email_settings'] = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
        $this->template->load('default_front', 'send_email/index', $this->data);
    }

    public function save() {
        $id = base64_decode($this->input->post('id'));
        $smtp_username = $this->input->post('smtp_username');
        $smtp_password = $this->input->post('smtp_password');
        $smtp_host = $this->input->post('smtp_host');
        $smtp_port = $this->input->post('smtp_port');
        $provider_id = my_provider_id();

        $this->form_validation->set_rules('smtp_username', 'Smtp Username', 'trim|required');
        $this->form_validation->set_rules('smtp_password', 'Smtp Password', 'trim|required');
        $this->form_validation->set_rules('smtp_host', 'Smtp Host', 'trim|required');
        $this->form_validation->set_rules('smtp_port', 'Smtp Port', 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $fields = array(
                'provider_id' => $provider_id,
                'smtp_username' => $smtp_username,
                'smtp_password' => $smtp_password,
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
            );
            if (!empty($id)) {
                $this->cms_model->master_update('send_email_settings', $fields, array('id' => $id));
            } else {
                $this->cms_model->master_insert($fields, 'send_email_settings');
            }
            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
        exit;
    }

    function send_test_email() {
        $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $provider_id = my_provider_id();
            $send_email_config = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
            if (!empty($send_email_config)) {
                $message = $this->load->view('email_template/default_header.php', array(), true);
                $message .= $this->input->post('message');
                $message .= $this->load->view('email_template/default_footer.php', array(), true);

                $email_array = array(
                    'to' => $this->input->post('email_address'),
                    'subject' => $this->input->post('subaject'),
                    'body_messages' => $message
                );

                if (!send_email($email_array)) {
                    echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>Something went wrong! We are not able to send you email.</div><br>";
                }else{
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
