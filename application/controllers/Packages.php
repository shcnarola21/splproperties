<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('package_model');
        is_user_logged();
    }

    public function index() {
        $user_data = $this->session->userdata('session_info');
        $provider_id = my_provider_id();
        $this->data = array();
        $this->data['title'] = "Packages";
        $this->data['provider_services'] = $this->package_model->get_provider_packages_services($provider_id);
        $this->template->load('default_front', 'packages/index', $this->data);
    }

    public function load_manage_package($package_id = null) {
        $id = base64_decode($package_id);
        $provider_id = my_provider_id();
        $data['provider_services'] = $this->package_model->get_provider_packages_services($provider_id);
        $data['package'] = array();
        $data['title'] = "Add Package";

        if (is_numeric($id)) {
            $data['title'] = "Edit Package";
            $data['package'] = $this->cms_model->get_info($id, 'packages', 'id');
            if (isset($data['package']) && !empty($data['package'])) {
                $data['package'] = $data['package'][0];
                $services_array = '';
                $services = $data['package']['services'];
                if (!empty($services)) {
                    $services_array = explode(',', $services);
                }
                $data['setup_fees'] = $this->package_model->get_all_setupfees($data['package']['provider_id'], $services_array);
            }
        }
        $this->load->view("packages/add_package", $data);
    }

    public function get($type, $addon_service = '') {
        $user_data = $this->session->userdata('session_info');
        $provider_id = my_provider_id();
        if ($type != "bundle") {

            if (strtolower($addon_service) == "addon") {
                $final['recordsTotal'] = $this->package_model->get_provider_packages('count', $provider_id, $type, $addon_service);
                $final['redraw'] = 1;
                $final['recordsFiltered'] = $final['recordsTotal'];
                $provider_packages = $this->package_model->get_provider_packages('result', $provider_id, $type, $addon_service)->result_array();
                $start = $this->input->get('start') + 1;
                foreach ($provider_packages as $key => $val) {
                    $provider_packages[$key] = $val;
                    $provider_packages[$key]['sr_no'] = $start++;
                    $provider_packages[$key]['price'] = number_format($val['price'], 2);
                    $provider_packages[$key]['responsive'] = '';
                }
                $final['data'] = $provider_packages;
            } elseif (strtolower($addon_service) == "setupfee") {
                $final['recordsTotal'] = $this->package_model->get_setup_fees($provider_id, 'count', $type);
                $final['redraw'] = 1;
                $final['recordsFiltered'] = $final['recordsTotal'];
                $provider_packages = $this->package_model->get_setup_fees($provider_id, 'result', $type)->result_array();
                $start = $this->input->get('start') + 1;
                foreach ($provider_packages as $key => $val) {
                    $provider_packages[$key] = $val;
                    $provider_packages[$key]['sr_no'] = $start++;
                    $provider_packages[$key]['fees'] = number_format($val['fees'], 2);
                    $provider_packages[$key]['responsive'] = '';
                }
                $final['data'] = $provider_packages;
            } else {
                $final['recordsTotal'] = $this->package_model->get_provider_packages('count', $provider_id, $type);
                $final['redraw'] = 1;
                $final['recordsFiltered'] = $final['recordsTotal'];
                $provider_packages = $this->package_model->get_provider_packages('result', $provider_id, $type)->result_array();
                $start = $this->input->get('start') + 1;
                foreach ($provider_packages as $key => $val) {
                    $provider_packages[$key] = $val;
                    $provider_packages[$key]['sr_no'] = $start++;
                    $provider_packages[$key]['price'] = number_format($val['price'], 2);
                    $provider_packages[$key]['responsive'] = '';
                }
                $final['data'] = $provider_packages;
            }
        } else {
            $final['recordsTotal'] = $this->package_model->get_provider_packages('count', $provider_id, $type);
            $final['redraw'] = 1;
            $final['recordsFiltered'] = $final['recordsTotal'];
            $provider_packages = $this->package_model->get_provider_packages('result', $provider_id, $type)->result_array();
            $start = $this->input->get('start') + 1;
            foreach ($provider_packages as $key => $val) {
                $provider_packages[$key] = $val;
                $provider_packages[$key]['sr_no'] = $start++;
                $provider_packages[$key]['price'] = number_format($val['price'], 2);
                $provider_packages[$key]['responsive'] = '';
            }
            $final['data'] = $provider_packages;
        }

        echo json_encode($final);
    }

    public function decimal_numeric($str) {
        if ((is_float($str)) || (is_numeric($str))) {
            $package_type = $this->input->post('package_type');
            $package_price = $this->input->post('price');

            if ($package_type != 'Addon' && $package_price < 0) {
                $this->form_validation->set_message('decimal_numeric', 'You cann\'t enter negative value for ' . $package_type);
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $this->form_validation->set_message('decimal_numeric', 'The %s field must have integer value.');
            return FALSE;
        }
    }

    function check_addon() {
        $type = $this->input->post('package_type');
        $services = $this->input->post('services');
        if ($type != 'Basic' && count($services) > 1) {
            $this->form_validation->set_message('check_addon', 'You can\'t create Bundle service with package type ' . $type . '.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function save() {
        $name = $this->input->post('package_name');
        $price = $this->input->post('package_price');
        $old_package_name = $this->input->post('name');
        $package_id = $this->input->post('package_id');

        $services = $this->input->post('package_services');
        $setup_fee_id = $this->input->post('setup_fee_id');
        $package_type = $this->input->post('package_type');

        $provider_id = my_provider_id();

        if ($old_package_name != $name) {
            $is_unique = '|is_unique[packages.name]';
        } else {
            $is_unique = '';
        }

        $this->form_validation->set_rules("package_services[]", "Services", "trim|required");
        $this->form_validation->set_rules("package_name", "package name", "trim|required" . $is_unique);
        $this->form_validation->set_rules("package_price", "package price", "trim|required|callback_decimal_numeric");

        if ($package_type == 'Basic') {
            $this->form_validation->set_rules("term", "term month(s)", "trim|required|numeric");
        }

        if (($this->form_validation->run() == TRUE)) {

            $input = array(
                'name' => $name,
                'price' => !empty($price) ? $price : 0,
                'provider_id' => $provider_id,
                'type' => $package_type,
                'term' => $this->input->post('term'),
                'services' => !empty($services) ? implode(',', $services) : '',
                'setup_fee_id' => $setup_fee_id ? $setup_fee_id : NULL,
                'created' => date("Y-m-d H:i:s"),
            );

            $this->package_model->save($input, $package_id);
            echo json_encode(array('status' => true));
        } else {
            $errors = array(
                'status' => false,
                'tabs' => get_validate_fields('packages', array_keys($this->form_validation->error_array())),
                'msg' => "<div class='alert alert-danger' style='margin-bottom:0px !important;'><button type='button' class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div><br>",
            );
            echo json_encode($errors);
        }
    }

    public function get_setupfee_list() {
        $user_data = $this->session->userdata('session_info');
        $services = $this->input->post('services');
        $services_array = array();
        if (!empty($services)) {
            $services_array = explode(',', $services);
        }
        $provider_id = my_provider_id();
        $package_setup_fees = $this->package_model->get_all_setupfees($provider_id, $services_array);
        $html = "";
        if (!empty($package_setup_fees)) {
            $html .= '<option value="">Select Fee</option>';
            foreach ($package_setup_fees as $ps) {
                $html .= '<option value="' . $ps['id'] . '" >' . $ps['provience'] . ' (' . $ps['fees'] . ')</option>';
            }
        }
        echo $html;
    }

    public function setup_fee() {
        $provider_id = my_provider_id();
        $this->data = array();
        $this->data['title'] = 'Packages Setup Fee';
        $this->template->load('default_front', 'packages/list_setup_fee', $this->data);
    }

    public function get_setup_fees() {
        $user_data = $this->session->userdata('session_info');
        $provider_id = my_provider_id();
        $final['recordsTotal'] = $this->package_model->get_setup_fees($provider_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $setup_fees = $this->package_model->get_setup_fees($provider_id, 'result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($setup_fees as $key => $val) {
            $setup_fees[$key] = $val;
            $setup_fees[$key]['sr_no'] = $start++;
            $setup_fees[$key]['responsive'] = '';
        }
        $final['data'] = $setup_fees;
        echo json_encode($final);
    }

    public function save_setup_fees() {
        $id = base64_decode($this->input->post('fee_id'));
        $service_id = base64_decode($this->input->post('service_id'));
        $provience = $this->input->post('provience');
        $setup_fees = $this->input->post('setup_fees');
        $user_data = $this->session->userdata('session_info');
        $agent_id = my_provider_id();
        if (is_numeric($id)) {
            $setup_fees_arr = $this->cms_model->get_info($id, 'setup_fees', 'id');
        }

        $this->form_validation->set_rules('provience', 'Provience', 'trim|required');
        $this->form_validation->set_rules('setup_fees', 'Setup fees', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            if (is_numeric($id)) {
                $fields = array(
                    'provience' => $provience,
                    'fees' => $setup_fees,
                );
            } else {
                $fields = array(
                    'provience' => $provience,
                    'fees' => $setup_fees,
                    'service_id' => $service_id,
                    'provider_id' => my_provider_id(),
                );
            }
            $this->package_model->save($fields, $id, 'setup_fees');
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function load_setup_fee($service_id = null, $setupfee_id = null) {
        $id = base64_decode($setupfee_id);
        $service_id = base64_decode($service_id);
        $data['setup_fees_arr'] = array();
        $data['service_id'] = $service_id;
        $data['service'] = $this->cms_model->get_info($service_id, 'services', 'id');
        if (is_numeric($id)) {
            $data['setup_fees_arr'] = $this->cms_model->get_info($id, 'setup_fees', 'id');
            if (!empty($data['setup_fees_arr']))
                $data['setup_fees_arr'] = $data['setup_fees_arr'][0];
        }
        $this->load->view("packages/add_setup_fee", $data);
    }

    public function delete_setup_fee() {
        $setup_id = $this->input->post('setup_id');
        $setup_id = base64_decode($setup_id);

        $package_setup_fee_arr = $this->cms_model->get_info($setup_id, 'packages', 'setup_fee_id');
        $setup_fee_info = $this->cms_model->get_info($setup_id, 'setup_fees', 'id');
        $result = array();
        if (!empty($package_setup_fee_arr)) {
            $this->data['alert_msg'] = "You couldn't delete the setup fee.Below Packages are using " . $setup_fee_info[0]['provience'] . '($' . $setup_fee_info[0]['fees'] . ')' . ".<br>Please change the setup Fee for the package then try to delete SetupFee.";
            $this->data['setup_fee_packages'] = $package_setup_fee_arr;
            $result['status'] = false;
            $result['setup_fee_packages'] = $this->load->view('packages/list_setupfee_packages', $this->data, TRUE);
        } else {
            $this->cms_model->master_delete('setup_fees', array('id' => $setup_id));
            $result['status'] = true;
        }
        echo json_encode($result);
    }

    public function delete_package() {
        $package_id = base64_decode($this->input->post('package'));
        $package_type = $this->input->post('type');
        if ($package_type == 'Addon') {
            $customers = $this->cms_model->get_all_details('customers', array('FIND_IN_SET(' . $package_id . ', basic_addon) >' => 0))->result_array();
        } else {
            $customers = $this->cms_model->get_all_details('customers', array('basic_package' => $package_id))->result_array();
        }
        $package_info = $this->cms_model->get_all_details('packages', array('id' => $package_id))->row_array();
        $result = array();
        if (!empty($customers)) {
            $this->data['alert_msg'] = "You couldn't delete the package.Below customers are using " . $package_info['name'] . '($' . $package_info['price'] . ')' . ".<br>Please change the Package for the customer then try to delete Package.";
            $this->data['customers'] = $customers;
            $result['status'] = false;
            $result['customers'] = $this->load->view('packages/list_package_customers', $this->data, TRUE);
        } else {
            $this->cms_model->master_delete('packages', array('id' => $package_id));
            $result['status'] = true;
        }
        echo json_encode($result);
    }

}
