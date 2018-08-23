<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('device_model', 'cms_model', 'upload_model', 'setting_model'));
        is_user_logged();
    }

    public function templates() {
        $data['title'] = 'Templates';
        $this->template->load('default_front', 'settings/template', $data);
    }

    public function get_templates() {
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];

        $final['recordsTotal'] = $this->setting_model->get_templates('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $templates = $this->setting_model->get_templates('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($templates as $key => $val) {
            $templates[$key] = $val;
            $templates[$key]['sr_no'] = $start++;
            $templates[$key]['responsive'] = '';
        }
        $final['data'] = $templates;
        echo json_encode($final);
    }

    public function email_config() {
        $data['title'] = 'Email Configuration';
        $data['send_email_config'] = $this->cms_model->get_all_details('send_email_config', array())->row_array();
        $this->template->load('default_front', 'settings/email_config', $data);
    }

    public function save_email_config() {
        $this->form_validation->set_rules("smtp_username", "SMTP Username", "trim|required");
        $this->form_validation->set_rules("smtp_password", "SMTP Password", "trim|required");
        $this->form_validation->set_rules("smtp_host", "SMTP Host", "trim|required");
        $this->form_validation->set_rules("smtp_port", "SMTP Port", "trim|required");

        if (($this->form_validation->run() == TRUE)) {
            $send_email_config_data = $this->cms_model->get_all_details('send_email_config', array())->row_array();
            $input = array(
                'smtp_username' => $this->input->post('smtp_username'),
                'smtp_password' => $this->input->post('smtp_password'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_port' => $this->input->post('smtp_port'),
            );

            if (!empty($send_email_config_data)) {
                $this->cms_model->master_update("send_email_config", $input, array('id' => $send_email_config_data['id']));
            } else {
                $this->cms_model->master_insert($input, "send_email_config");
            }
            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
    }

    public function templates_edit($id) {
        $id = base64_decode($id);
        $user_data = $this->session->userdata('session_info');
        if (!empty($id)) {
            $data['email_templates'] = $email_templates = $this->cms_model->get_all_details('email_templates', array('id' => $id))->row_array();
            if (!empty($email_templates)) {
                $data['title'] = 'Edit Template';
                $this->template->load('default_front', 'settings/template_edit', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('templates');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('templates');
        }
    }

    public function save_tempalte() {
        $this->form_validation->set_rules("email_subject", "Subject", "trim|required");
        $this->form_validation->set_rules("text_email", "Email Text", "trim|required");

        if (($this->form_validation->run() == TRUE)) {
            $enable_send_email = $this->input->post('enable_send_email');
            $input = array(
                'is_enable' => ($enable_send_email == 'on') ? 1 : 0,
                'email_subject' => $this->input->post('email_subject'),
                'email_text' => $this->input->post('text_email')
            );
            $this->cms_model->master_update("email_templates", $input, array('email_for' => 'Registration'));
            echo '1^';
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
    }

    public function enable_template() {
        $enable_send_email = $this->input->post('is_enable');
        $id = $this->input->post('id');
        $id = base64_decode($id);
        $input = array(
            'is_enable' => ($enable_send_email == 'true') ? 1 : 0,
        );
        $this->cms_model->master_update("email_templates", $input, array('id' => $id));
    }

}

/* End of file Settings.php */
/* Location: ./application/controllers/Settings.php */