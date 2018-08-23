<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_setting extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('setting_model');
        is_user_logged();
    }

    public function index() {
        $this->data = array();
        $this->data['title'] = "Tax Setting";
        $provider_id = my_provider_id();
        $this->data['settings'] = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
        $this->template->load('default_front', 'settings/tax_setting', $this->data);
    }

    public function save() {
        $date = $this->input->post('date');
        $provider_id = my_provider_id();
        $auto_send_yearly_tax_receipt = $this->input->post('auto_send_yearly_tax_receipt');
        if ($auto_send_yearly_tax_receipt) {
            $this->form_validation->set_rules('date', 'Date', 'trim|required');
        }
        $this->form_validation->set_rules('id', 'id', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $current_year = date('Y');
            $chk_date = $date . ' ' . $current_year;
            $final_Date = '0000-' . date('m-d', strtotime($chk_date));
            $fields = array(
                'auto_send_yearly_tax_receipt' => !empty($auto_send_yearly_tax_receipt) ? 1 : 0,
                'auto_send_yearly_tax_receipt_date' => !empty($auto_send_yearly_tax_receipt) ? $final_Date : null,
            );
            $this->cms_model->master_update('providers', $fields, array('id' => $provider_id));
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    public function save_auto_increase() {
        $yearly_auto_increase_value = $this->input->post('yearly_auto_increase_value');
        $yearly_auto_increase_specific_time = $this->input->post('yearly_auto_increase_specific_time');
        $yearly_auto_increase_date = $this->input->post('yearly_auto_increase_date');
        $this->form_validation->set_rules('yearly_auto_increase_value', 'Yearly Auto Increase Value', 'trim|required');
        //$this->form_validation->set_rules('yearly_auto_increase_specific_time', 'Yearly Auto Increase Specific Time', 'trim|required');
        if ($yearly_auto_increase_specific_time == 'specific_date') {
            $this->form_validation->set_rules('yearly_auto_increase_date', 'Yearly Auto Increase Date', 'trim|required');
        }

        $yearly_auto_increase_specific_time_val = null;
        if ($yearly_auto_increase_specific_time == 'specific_date') {
            $yearly_auto_increase_specific_time_val = 'yes';
        }
        if ($yearly_auto_increase_specific_time == 'now') {
            $yearly_auto_increase_specific_time_val = 'no';
        }

        $provider_id = my_provider_id();
        if ($this->form_validation->run() == TRUE) {
            $fields = array(
                'yearly_auto_increase_value' => $yearly_auto_increase_value,
                'yearly_auto_increase_specific_time' => $yearly_auto_increase_specific_time_val,
                'yearly_auto_increase_date' => ($yearly_auto_increase_specific_time == 'specific_date' && !empty($yearly_auto_increase_date)) ? date('Y-m-d', strtotime($yearly_auto_increase_date)) : null,
            );
            $this->cms_model->master_update('providers', $fields, array('id' => $provider_id));
            if ($yearly_auto_increase_specific_time == 'now') {
                $date = date('Y-m-d');
                $config = array(
                    'provider_id' => $provider_id,
                    'percentage' => $yearly_auto_increase_value,
                );
                auto_increase_package_price_now($config, true);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    public function load_package_history() {
        $provider_id = my_provider_id();
        $this->load->view('settings/list_package_changed_history', array('provider_id' => $provider_id));
    }

    public function get_package_history() {
        $final['recordsTotal'] = $this->setting_model->get_package_history('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $provider_packages = $this->setting_model->get_package_history('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($provider_packages as $key => $val) {
            $provider_packages[$key] = $val;
            $provider_packages[$key]['sr_no'] = $start++;
            $provider_packages[$key]['responsive'] = '';
        }
        $final['data'] = $provider_packages;

        echo json_encode($final);
    }
}
