<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('customers_model');
        $this->load->model('package_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Customers';
        $this->template->load('default_front', 'customers/index', $data);
    }

    public function get() {
        if (is_provider()) {
            $final['recordsTotal'] = $this->customers_model->get_customers('count');
            $final['redraw'] = 1;
            $final['recordsFiltered'] = $final['recordsTotal'];
            $customers = $this->customers_model->get_customers('result')->result_array();
            $start = $this->input->get('start') + 1;
            foreach ($customers as $key => $val) {
                $customers[$key] = $val;
                $customers[$key]['responsive'] = '';
                $customers[$key]['last_terminated_on'] = !empty($val['last_terminated_on']) ? date('Y-m-d', strtotime($val['last_terminated_on'])) : NULL;
                $customers[$key]['name'] = strlen($val['name']) > 25 ? substr($val['name'], 0, 25) . "..." : $val['name'];
            }
            $final['data'] = $customers;
            echo json_encode($final);
        } else {
            $this->session->set_flashdata('error', 'You are not authorised to access this page.');
            redirect('/dashboard');
        }
    }

    public function edit() {
        $id = base64_decode($this->input->post('id'));
        $provider_id = my_provider_id();
        $edit_billing = $this->input->post('edit');
        $customer = $this->cms_model->get_all_details('customers', array('cid' => $id))->row_array();
        $data['customer'] = $customer;
        $data['provider_info'] = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
        $data['customer_property'] = $this->cms_model->get_all_details('properties', array('id' => $customer['property_id']))->row_array();

        $data['provider_properties'] = $this->cms_model->get_all_details('properties', array('provider_id' => $provider_id, 'units !=' => 0))->result_array();
        $data['countries'] = get_countries();
        $data['billing'] = ($edit_billing == 'billing') ? true : false;
        $data['accountinfo'] = ($edit_billing == 'accountinfo') ? true : false;
        $this->load->view("customers/edit_customer", $data);
    }

    public function send_email_view() {
        $id = base64_decode($this->input->post('id'));
        $provider_id = my_provider_id();
        $customer = $this->cms_model->get_all_details('customers', array('cid' => $id))->row_array();
        $data['customer'] = $customer;
        $data['canned_responses'] = $this->cms_model->get_all_details('canned_responses', array('provider_id' => $provider_id))->result_array();
        $this->load->view("customers/customer_email", $data);
    }

    public function send_customer_to_email() {
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('email_to', 'Email', 'trim|required');
        if ($this->form_validation->run() == TRUE) {

            $provider_id = my_provider_id();
            $send_email_config = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
            if (!empty($send_email_config)) {
                $message = $this->load->view('email_template/default_header.php', array(), true);
                $message .= $this->input->post('message');
                $message .= $this->load->view('email_template/default_footer.php', array(), true);

                $email_array = array(
                    'to' => explode(',', $this->input->post('email_to')),
                    'subject' => $this->input->post('subject'),
                    'body_messages' => $message
                );

                if (!send_email($email_array)) {
                    echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>Something went wrong! We are not able to send you email.</div><br>";
                } else {
                    echo "1^";
                }
            } else {
                echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>SMTP detail not found.Please Enter Send Email Configuration</div><br>";
            }
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div><br>";
        }
    }

    public function get_canned_response_info() {
        $canned_response_id = base64_decode($this->input->post('id'));
        $canned_response = $this->cms_model->get_all_details('canned_responses', array('id' => $canned_response_id))->row_array();
        $data['canned_response'] = $canned_response;
        echo json_encode($data);
    }

    public function add() {
        $this->data['title'] = 'Add Customer';
        $provider_id = my_provider_id();
        $this->data['countries'] = get_countries();
        $this->data['timezones'] = $this->get_timezone('Canada');
        $this->data['provider_packages'] = $this->cms_model->get_all_details('packages', 'provider_id = ' . $provider_id . ' AND type="Basic"')->result_array();
        $this->data['provider_services'] = $this->package_model->get_provider_packages_services($provider_id);
        $this->data['provider_properties'] = $this->cms_model->get_all_details('properties', array('provider_id' => $provider_id, 'units !=' => 0))->result_array();

        $this->template->load('default_front', 'customers/add', $this->data);
    }

    public function add_customer() {
        $user_data = $this->session->userdata('session_info');
        $customer_package = $this->input->post('customer_package');
        if (isset($_POST['package_price'])) {
            $filed = 'package_price';
        } elseif (isset($_POST['customer_package'])) {
            $filed = 'customer_package';
        }
        $this->form_validation->set_rules($filed, 'Package', 'trim|required');
        $this->form_validation->set_rules("customer_name", "Customer Name", "trim|required|callback_customer_name_check");
        $unique_callback = '|callback_validate_email';
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[customers.email]|callback_validate_email');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[customers.email]|callback_validate_email');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('default_language', 'Language', 'trim|required');
        if (isset($_POST['property_id']) && !empty($this->input->post('property_id'))) {
            $this->form_validation->set_rules("property_unit", "Property Unit", "trim|required|callback_check_property_unit");
        }

        $secondary_check = $this->input->post('secondary_check');
        if ($secondary_check != 'yes') {
            $this->form_validation->set_rules("secondary_name", "Secondary Name", "trim|required");
            $this->form_validation->set_rules('secondary_phone', 'Secondary Phone Number', 'trim|required');
        }

        $billing_check = $this->input->post('billing_check');
        $provider_payment_type = $this->input->post('default_customer_type');
        if ($billing_check != 'yes') {
            $this->form_validation->set_rules("billing_name", "Billing Name", "trim|required");
            $this->form_validation->set_rules('billing_email', 'Billing email', 'trim|required|valid_email');
            $this->form_validation->set_rules('billing_country', 'Billing Country', 'trim|required');
        }
        if (($this->form_validation->run() == TRUE)) {
            $provider_id = my_provider_id();
            $additional_emails = $this->input->post('additional_email');
            $additional_emails_str = "";
            if (!empty($additional_emails)) {
                $a_emails = array_unique(explode(',', $additional_emails));
                $additional_emails_str = implode(',', $a_emails);
            }
            $basic_package_id = $this->input->post('customer_package');
            $packages = $this->cms_model->get_info($basic_package_id, 'packages', 'id');
            $basic_addon = $this->input->post('addons[]');
            $payment_info = array(
                'payment_type' => 'Cash',
                'provider_id' => $provider_id,
                'name' => $this->input->post('customer_name'),
                'default_language' => $this->input->post('default_language'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'zip_code' => $this->input->post('zip_code'),
                'email' => $this->input->post('email'),
                'additional_emails' => $additional_emails,
                'phone' => $this->input->post('phone'),
                'security_question' => $this->input->post('security_question'),
                'security_answer' => $this->input->post('security_answer'),
                'date_of_birth' => !empty($date_of_birth) ? $date_of_birth : null,
            );
            if ($billing_check == 'yes') {
                $payment_info['billing_check'] = 'yes';
            } else {
                $payment_info['billing_check'] = 'no';
                $payment_info['billing_name'] = $this->input->post('billing_name');
                $payment_info['billing_address'] = $this->input->post('billing_address');
                $payment_info['billing_city'] = $this->input->post('billing_city');
                $payment_info['billing_state'] = $this->input->post('billing_state');
                $payment_info['billing_country'] = $this->input->post('billing_country');
                $payment_info['billing_zipcode'] = $this->input->post('billing_zipcode');
                $payment_info['billing_phone'] = $this->input->post('billing_phone');
                $payment_info['billing_email'] = $this->input->post('billing_email');
            }
            if (isset($_POST['package_price'])) {
                $rental_data = array('customer_id' => '',
                    'name' => $this->input->post('package_name'),
                    'price' => $this->input->post('package_price'),
                );
            } else {
                $payment_info['basic_package'] = $customer_package;
            }
            $next_renewal_date = date('Y-m-d', strtotime("+" . $packages[0]['term'] . " month", strtotime(date('Y-m-d H:i:s'))));
            $customer_info = array(
                'default_language' => $payment_info['default_language'],
                'name' => $payment_info['name'],
                'secondary_check' => $secondary_check == 'yes' ? 'yes' : 'no',
                'secondary_name' => ($secondary_check == 'yes') ? $payment_info['name'] : $this->input->post('secondary_name'),
                'secondary_phone' => ($secondary_check == 'yes') ? $payment_info['phone'] : $this->input->post('secondary_phone'),
                'property_id' => isset($_POST['property_id']) && !empty($this->input->post('property_id')) ? base64_decode($this->input->post('property_id')) : NULL,
                'unit' => isset($_POST['property_unit']) && !empty($this->input->post('property_unit')) ? $this->input->post('property_unit') : NULL,
                'address' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $payment_info['address'] : NULL,
                'city' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $payment_info['city'] : NULL,
                'state' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $payment_info['state'] : NULL,
                'country' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $payment_info['country'] : NULL,
                'zip_code' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $payment_info['zip_code'] : NULL,
                'email' => $payment_info['email'],
                'phone' => $payment_info['phone'],
                'status' => 'active',
                'provider_id' => $provider_id,
                'basic_package' => isset($payment_info['basic_package']) && !empty($payment_info['basic_package']) ? $payment_info['basic_package'] : NULL,
                'basic_addon' => !empty($basic_addon) ? implode(',', $basic_addon) : NULL,
                'billing_check' => $payment_info['billing_check'],
                'billing_name' => ($payment_info['billing_check'] == 'yes') ? $payment_info['name'] : $payment_info['billing_name'],
                'billing_email' => ($payment_info['billing_check'] == 'yes') ? $payment_info['email'] : $payment_info['billing_email'],
                'billing_phone' => ($payment_info['billing_check'] == 'yes') ? $payment_info['phone'] : $payment_info['billing_phone'],
                'billing_address' => ($payment_info['billing_check'] == 'yes') ? $payment_info['address'] : $payment_info['billing_address'],
                'billing_zipcode' => ($payment_info['billing_check'] == 'yes') ? $payment_info['zip_code'] : $payment_info['billing_zipcode'],
                'billing_state' => ($payment_info['billing_check'] == 'yes') ? $payment_info['state'] : $payment_info['billing_state'],
                'billing_city' => ($payment_info['billing_check'] == 'yes') ? $payment_info['city'] : $payment_info['billing_city'],
                'billing_country' => ($payment_info['billing_check'] == 'yes') ? $payment_info['country'] : $payment_info['billing_country'],
                'payment_type' => 'Cash',
                'start_date' => date('Y-m-d'),
                'renewal_date' => $next_renewal_date,
                'provider_payment_type' => $provider_payment_type,
                'created' => date('Y-m-d H:i:s'),
            );


            $customer_id = $this->customers_model->save($customer_info, '', 'customers');
            if (isset($rental_data) && !empty($rental_data) && !empty($customer_id)) {
                $rental_data['customer_id'] = $customer_id;
                $this->cms_model->master_insert($rental_data, 'rental_packages');
            }
            $call_security_data = array(
                'customer_id' => $customer_id,
                'security_question' => !empty($payment_info['security_question']) ? $payment_info['security_question'] : NULL,
                'security_answer' => !empty($payment_info['security_answer']) ? $payment_info['security_answer'] : NULL,
                'date_of_birth' => !empty($payment_info['date_of_birth']) ? $payment_info['date_of_birth'] : NULL,
                'created' => date('Y-m-d H:i:s'),
            );
            $this->customers_model->save($call_security_data, '', "call_security");

            echo json_encode(array('status' => true));
        } else {
            $errors = array(
                'status' => false,
                'tabs' => get_validate_fields('customer', array_keys($this->form_validation->error_array())),
                'msg' => "<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>",
            );
            echo json_encode($errors);
        }
    }

//    For editing customer,account info  and billing info 
    function save() {
        $customer_id = $this->input->post('customers_id');
        $provider_payment_type = $this->input->post('default_customer_type');
        $billing_info = $this->input->post('billing_info');
        $account_info = $this->input->post('account_info');
        $cardinfo = $this->input->post('cardinfo');
        $validation = False;

        $customer_info = $this->cms_model->get_all_details('customers', array('cid' => $customer_id))->row_array();

        if (!empty($billing_info) && $billing_info == 'billing_info') {
            $input = array(
                'billing_name' => $this->input->post('billing_name'),
                'billing_email' => $this->input->post('billing_email'),
                'billing_address' => $this->input->post('billing_address'),
                'billing_city' => $this->input->post('billing_city'),
                'billing_state' => $this->input->post('billing_state'),
                'billing_country' => $this->input->post('billing_country'),
                'billing_zipcode' => $this->input->post('billing_zipcode'),
                'billing_phone' => $this->input->post('billing_phone'),
            );

            $condition = array('cid' => $customer_id);
            $this->cms_model->master_update("customers", $input, $condition);
            echo "1^";
        } else if (!empty($account_info) && $account_info == 'account_info') {
            $this->form_validation->set_rules("customer_account_status", "Account status", "trim|required");
            if (is_provider()) {
                $this->form_validation->set_rules("start_date", "start Date", "trim|required");
            }
            $status = $this->input->post('customer_account_status');
            $termination_event = $this->input->post('terminate_info');
            $termination_specific_date = $this->input->post('terminate_specific_date');
            if ($status == 'terminated') {
                $this->form_validation->set_rules("terminate_info", "Account Termination", "trim|required");
            }
            if ($termination_event == 'specific') {
                $this->form_validation->set_rules("terminate_specific_date", "Account Termination Date", "trim|required");
            }
            $this->form_validation->set_rules("renewal_date", "Renewal Date", "trim|required");
            $this->form_validation->set_rules("customer_account_type", "Payment Type", "trim|required");
            if (($this->form_validation->run() == TRUE) || $validation == TRUE) {

                $input = array(
                    'payment_type' => $this->input->post('customer_account_type'),
                    'renewal_date' => date('Y-m-d', strtotime($this->input->post('renewal_date'))),
                    'termination_event' => ($status == 'terminated' && $termination_event != 'now') ? $termination_event : NULL,
                    'termination_specific_date' => $status == 'terminated' && $termination_event == 'specific' ? date('Y-m-d', strtotime($termination_specific_date)) : NULL,
                    'last_terminated_on' => ($status == 'terminated' && $termination_event == 'now') ? date('Y-m-d H:i:s') : NULL
                );
                if (($status == 'terminated' && $termination_event == 'now')) {
                    $input['status'] = $status;
                } else if ($status != 'terminated') {
                    $input['status'] = $status;
                }
                if (is_provider()) {
                    $input['start_date'] = date('Y-m-d', strtotime($this->input->post('start_date')));
                }

                $condition = array('cid' => $customer_id);
                $this->cms_model->master_update("customers", $input, $condition);
                echo "1^";
            } else {
                echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
            }
        } else {
            if ($cardinfo != 'cardinfo') {
                $customers_email = $this->input->post('customers_email');
                $email = $this->input->post('email');
                $unique_callback = '|callback_validate_email';
                $this->form_validation->set_rules("customer_name", "customer name", "trim|required|callback_customer_name_check");
                $this->form_validation->set_rules("email", "email", "trim|required" . $unique_callback);
                if (isset($_POST['property_id']) && !empty($this->input->post('property_id'))) {
                    $this->form_validation->set_rules("property_unit", "Property Unit", "trim|required|callback_check_property_unit");
                }
                $secondary_check = $this->input->post('secondary_check');
                if ($secondary_check != 'yes') {
                    $this->form_validation->set_rules("secondary_name", "Secondary Name", "trim|required");
//                    $this->form_validation->set_rules('secondary_phone', 'Secondary Phone Number', 'trim|required');
                }
            } else {
                $payment_type = $this->input->post('payment_type');
                $validation = TRUE;
            }

            $input = array();
            if (($this->form_validation->run() == TRUE) || $validation == TRUE) {
                if ($cardinfo != 'cardinfo') {
                    $additional_emails = $this->input->post('additional_email');
                    $additional_emails_str = "";
                    if (!empty($additional_emails)) {
                        $a_emails = array_unique(explode(',', $additional_emails));
                        $additional_emails_str = implode(',', $a_emails);
                    }
                    $input = array(
                        'name' => $this->input->post('customer_name'),
                        'rating' => !empty($this->input->post('rating')) ? $this->input->post('rating') : NULL,
                        'email' => $this->input->post('email'),
                        'secondary_check' => $secondary_check == 'yes' ? 'yes' : 'no',
                        'secondary_name' => ($secondary_check == 'yes') ? $this->input->post('customer_name') : $this->input->post('secondary_name'),
                        'secondary_phone' => ($secondary_check == 'yes') ? $this->input->post('phone') : $this->input->post('secondary_phone'),
                        'additional_email' => !empty($additional_emails_str) ? $additional_emails_str : NULL,
                        'property_id' => isset($_POST['property_id']) && !empty($this->input->post('property_id')) ? base64_decode($this->input->post('property_id')) : NULL,
                        'unit' => isset($_POST['property_unit']) && !empty($this->input->post('property_unit')) ? $this->input->post('property_unit') : NULL,
                        'address' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $this->input->post('address') : NULL,
                        'city' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $this->input->post('city') : NULL,
                        'zip_code' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $this->input->post('zip_code') : NULL,
                        'state' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $this->input->post('state') : NULL,
                        'country' => isset($_POST['property_id']) && empty($this->input->post('property_id')) ? $this->input->post('country') : NULL,
                        'phone' => $this->input->post('phone'),
                        'default_language' => $this->input->post('default_language')
                    );
                    if (!empty($provider_payment_type)) {
                        $input['provider_payment_type'] = $provider_payment_type;
                    }
                } else {
                    $namecard = $this->input->post('namecard');
                    $card_type = $this->input->post('card_type');
                    $rccnumber = $this->input->post('ccnumber');
                    $ccmonth = $this->input->post('ccmonth');
                    $ccyear = $this->input->post('ccyear');
                    $ccvc = $this->input->post('ccvc');
                    $notes = $this->input->post('payment_notes');
                    $flag = false;
                    $ccnumber = NULL;
                    if (!empty($rccnumber)) {
                        $ccnumber_count = substr_count($rccnumber, '*');
                        if ($ccnumber_count == "0") {
                            $ccnumber = _encrypt($rccnumber);
                            $flag = true;
                        }
                    }
                    if (!empty($ccvc))
                        $ccvc = _encrypt($ccvc);

                    $input = array(
                        'namecard' => !empty($namecard) ? $namecard : NULL,
                        'card_type' => !empty($card_type) ? $card_type : NULL,
                        'ccmonth' => !empty($ccmonth) ? $ccmonth : NULL,
                        'ccyear' => !empty($ccyear) ? $ccyear : NULL,
                        'ccvc' => !empty($ccvc) ? $ccvc : NULL,
                        'payment_notes' => !empty($notes) ? $notes : NULL,
                    );
                    if ($flag) {
                        $input['ccnumber'] = $ccnumber;
                    }
                }
                $condition = array('cid' => $customer_id);
                $this->cms_model->master_update("customers", $input, $condition);
                echo "1^";
            } else {
                echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
            }
        }
    }

    function customer_name_check($customer_name) {
        $check_valid = preg_match('/[\/~`\!@#\$%\&\*\(\)\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $customer_name);
        $customer_name_arr = explode(" ", $customer_name);
        if (count($customer_name_arr) < 2) {
            $this->form_validation->set_message('customer_name_check', 'Please enter firstname and lastname.');
            return FALSE;
        } else if ($check_valid) {
            $this->form_validation->set_message('customer_name_check', 'Customer Name can not contain any special charectors.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_property_unit($value) {
        $unit_value = $value;
        $customer_id = $this->input->post('customers_id');
        $property_id = base64_decode($this->input->post('property_id'));
        $provider_id = my_provider_id();
        $property_exists = $this->cms_model->get_all_details('customers', array('property_id' => $property_id, 'provider_id' => $provider_id, 'unit' => $unit_value, 'status !=' => 'terminated'))->row_array();
        if (!empty($property_exists)) {
            if (isset($customer_id) && $customer_id == $property_exists['cid']) {
                return TRUE;
            } else {
                $this->form_validation->set_message('check_property_unit', 'Property unit no: ' . $unit_value . ' is already occupied, please select another unit.');
            }
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function validate_email() {
        $email_array = array();
        $email = $this->input->post('email');
        $additional_email = $this->input->post('additional_email');
        if (!empty($email)) {
            $email_array[] = $email;
        }

        if (!empty($email)) {
            $is_email_valid = true;
            foreach ($email_array as $e) {
                if (filter_var($e, FILTER_VALIDATE_EMAIL) === false) {
                    $is_email_valid = false;
                    $this->form_validation->set_message('validate_email', 'Please Enter valid email.');
                    return FALSE;
                }
            }

            if (!empty($additional_email)) {
                $additional_email_arr = explode(',', $additional_email);
                foreach ($additional_email_arr as $e) {
                    if (filter_var($e, FILTER_VALIDATE_EMAIL) === false) {
                        $this->form_validation->set_message('validate_email', 'Please Enter valid email.');
                        return FALSE;
                    }
                }
            }

            if ($is_email_valid) {
                $is_email_exist = array();
                $customer_id = $this->input->post('customers_id');
                foreach ($email_array as $e) {
                    $check_email_exist = $this->customers_model->check_email(trim($e), $customer_id);

                    if ($check_email_exist) {
                        $is_email_exist[] = $e;
                    }
                }
                if (!empty($is_email_exist)) {
                    $this->form_validation->set_message('validate_email', implode(',', $is_email_exist) . ' email already exist.');
                    return FALSE;
                }
            }
            return true;
        }
    }

    public function view($id, $tab = '') {
        $this->data['title'] = 'View Customer';
        $id = base64_decode($id);
        $is_rental_service_available = is_rental_service_available();

        if (!empty($id)) {
            $customer = $this->cms_model->get_all_details('customers', array('cid' => $id))->row_array();
            $provider_id = $customer['provider_id'];
            $this->data['customer'] = $customer;
//            $this->data['package_info'] = $this->cms_model->get_all_details('packages', array('id' => $customer['basic_package']))->row_array();
            $this->data['customer_property'] = $this->cms_model->get_all_details('properties', array('id' => $customer['property_id']))->row_array();
            if ($is_rental_service_available) {
                $this->data['rental_package'] = $this->cms_model->get_all_details('rental_packages', array('id' => $customer['cid'], 'type' => 'basic'))->row_array();
                $this->data['rental_package_addons'] = $this->cms_model->get_all_details('rental_packages', array('id' => $customer['cid'], 'type' => 'addon'))->result_array();
            }
            $this->data['provider_packages'] = $this->cms_model->get_all_details('packages', 'provider_id = ' . $provider_id . ' AND type="Basic"')->result_array();
            $this->data['provider_addons'] = $this->cms_model->get_all_details('packages', 'provider_id = ' . $provider_id . ' AND type="Addon"')->result_array();
            $this->data['provider_services'] = $this->package_model->get_provider_packages_services($provider_id);
            $this->data['call_security'] = $this->get_call_security($customer['cid']);
            $this->data['provider_info'] = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
            $this->data['customer_credit'] = $this->customers_model->get_customer_total_credit($id);
            $this->data['customer_lastmonth_credit'] = $this->get_customer_last_month_credit($id);
            if (!empty($customer)) {
                $this->data['cardinfo'] = $this->cms_model->get_all_details('customers', array('cid' => $id, 'provider_id' => $provider_id), '', '', 'payment_type,namecard,card_type,ccnumber,ccmonth,ccyear,ccvc,payment_notes')->row_array();
                $this->data['package_info'] = $package_info = $this->customers_model->get_customer_package($customer['basic_package']);
                $this->data['package_addons'] = $this->customers_model->get_package_addons($this->data['package_info']['services']);
                if ($this->input->is_ajax_request()) {
                    if ($tab == "AccountInfo") {
                        $this->load->view('customers/profile_tab', $this->data);
                    } elseif ($tab == "Packages") {
                        $this->load->view('customers/packages_tab', $this->data);
                    } elseif ($tab == "CardInfo") {
                        $this->load->view('customers/cardinfo_tab', $this->data);
                    } elseif ($tab == "Contracts") {
                        $this->load->view('customers/contract_tab', $this->data);
                    }
                } else {
                    $this->template->load('default_front', 'customers/view', $this->data);
                }
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('customers');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('customers');
        }
    }

    function get_customer_last_month_credit($customer_id) {
        if ($customer_id) {
            $get_data = $this->input->post('get_data');
            $customer_lastmonth_credit = $this->cms_model->get_all_details('customer_credits', "type IN('Last Month ') AND customer_id =" . $customer_id)->row_array();
            $customer = $this->cms_model->get_all_details('customers', array('cid' => $customer_id))->row_array();
            if (!empty($customer)) {
                $package_info = $this->customers_model->get_customer_package($customer['basic_package']);
                if (!empty($package_info) && $customer_lastmonth_credit) {
                    if ($customer_lastmonth_credit['amount'] < $package_info['price']) {
                        if ($this->input->is_ajax_request() && isset($get_data)) {
                            echo '1^';
                            exit;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
        if ($this->input->is_ajax_request() && isset($get_data)) {
            echo '0^';
            exit;
        } else {
            return true;
        }
    }

    function get_tab_view($customer_id) {
        $tab = $this->input->post('tab');
        $this->view(base64_encode($customer_id), $tab);
    }

    function get_creditcard_number() {
        $cc_password = $this->input->post('password');
        $type = $this->input->post('type');
        $id = base64_decode($this->input->post('id'));
        $master_id = isset($_SESSION['master_id']) ? $_SESSION['master_id'] : $_SESSION['session_info']['user_id'];
        $table = isset($_SESSION['master_id']) ? 'users' : (($_SESSION['session_info']['type'] == 'Provider') ? 'providers' : 'users');
        if ($table == 'providers') {
            $master_id = my_provider_id();
        }
        if ($type == "customer") {
            $loggin_user_info = $this->cms_model->get_all_details($table, array('id' => $master_id))->row_array();
            $customer_info = $this->cms_model->get_all_details('customers', array('cid' => $id))->row_array();
        }
        if (isset($loggin_user_info) && !empty($loggin_user_info)) {
            if (sha1($cc_password . $loggin_user_info['salt']) == $loggin_user_info['password']) {
                echo convert_enc_string($customer_info['ccnumber']);
            } else {
                echo "0";
            }
        } else {
            echo "0";
        }
        exit;
    }

    function get_property_units($property_id = '') {
        $property_id = base64_decode($this->input->post('property_id'));
        $this->data['property'] = $this->cms_model->get_all_details('properties', array('id' => $property_id))->result_array();
        $option = '<option value="">Select Unit</option>';
        if (!empty($this->data['property'])) {
            $this->data['property'] = $this->data['property'][0];
            for ($i = 1; $i <= $this->data['property']['units']; $i++) {
                $option .= '<option value=' . $i . '>' . $i . '</option>';
            }
        }
        echo $option;
        exit;
    }

    public function manage_rentaladdon_tab() {
        $data['index'] = $index = $this->input->post('index');
        if ($index) {
            $this->load->view("customers/rental_package_tab_content", $data);
        }
    }

    public function get_package_info() {
        $package_id = base64_decode($this->input->post('package'));
        $this->data['package_info'] = $this->customers_model->get_customer_package($package_id);
        $this->load->view('customers/single_package_detail', $this->data);
    }

    function packages_save($customer_id) {
        if (isset($_POST['package_price'])) {
            $filed = 'package_price';
        } elseif (isset($_POST['customer_package'])) {
            $filed = 'customer_package';
        }
        $this->form_validation->set_rules($filed, "package", "trim|required");
        if (isset($_POST['rental_adon_name'])) {
            $this->form_validation->set_rules("rental_adon_name[]", "Name", "trim|required|callback_custom_addon_validate");
        }
        if (($this->form_validation->run() == TRUE)) {
            $package = $this->input->post($filed);
            if (isset($_POST['package_price'])) {
                $rental_package_id = base64_decode($this->input->post('rental_package_id'));
                $rental_package_name = $this->input->post('package_name');
                $data = array('customer_id' => $customer_id,
                    'name' => $rental_package_name,
                    'price' => $package,
                );
                if ($rental_package_id) {
                    $this->cms_model->master_update('rental_packages', $data, array('id' => $rental_package_id));
                } else {
                    $this->cms_model->master_insert($data, 'rental_packages');
                }
            } elseif (isset($_POST['customer_package'])) {
                $addons = $this->input->post('addons[]');
                if (!empty($addons)) {
                    $addons = implode(',', $addons);
                } else {
                    $addons = NULL;
                }
                $data = array('basic_package' => $package,
                    'basic_addon' => $addons);

                $this->cms_model->master_update('customers', $data, array('cid' => $customer_id));
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        pr($_POST);
    }

    public function custom_addon_validate() {
        $addon_name_val = $_POST['rental_adon_name'];
        $addon_err = array();
        $name_err = array();
        $price_err = array();
        foreach ($addon_name_val as $key => $val) {
            if (!empty($val) && !empty($_POST['rental_adon_name'][$key]) && !empty($_POST['rental_adon_price'][$key])) {
                
            } else {
                if (empty($val)) {
                    $name_err['rental_adon_name'][$key] = 'Please enter addon ' . $key . ' name.';
                }
                if (empty($_POST['rental_adon_price'][$key])) {
                    $price_err['rental_adon_price'][$key] = 'Please enter addon ' . $key . ' price.';
                }

                if (!empty($name_err)) {
                    $addon_err['rental_adon_name'] = $name_err['rental_adon_name'];
                }

                if (!empty($price_err)) {
                    $addon_err['rental_adon_price'] = $price_err['rental_adon_price'];
                }
                if (!empty($addon_err))
                    $this->form_validation->set_message("custom_addon_validate", json_encode($addon_err));
            }
        }
        return !empty($addon_err) ? FALSE : TRUE;
    }

    public function get_call_security($customer_id, $is_ajax = false) {
        $customer_info = $this->cms_model->get_info($customer_id, 'call_security', 'customer_id');
        if ($is_ajax || $is_ajax == 'true') {
            echo json_encode($customer_info);
            exit;
        } else {
            return $customer_info;
        }
    }

    function save_billing_check($id) {
        $billing_check = $this->input->post('billing_check');
        $data = array('billing_check' => ($billing_check == "true") ? "yes" : "no");
        $this->cms_model->master_update('customers', $data, array('cid' => $id));
    }

    function save_secondary_check($id) {
        $secondary_check = $this->input->post('secondary_check');
        $data = array('secondary_check' => ($secondary_check == "true") ? "yes" : "no");
        $this->cms_model->master_update('customers', $data, array('cid' => $id));
    }

    public function save_call_security($customer_id) {

        //get customer info
        $call_security_info = $this->cms_model->get_info($customer_id, 'call_security', 'customer_id');

        $question = $this->input->post('security_question');
        $answer = $this->input->post('security_answer');
        $db_date = date('Y-m-d', strtotime($this->input->post('date_of_birth')));
        $input = array(
            'security_question' => !empty($question) ? $question : null,
            'security_answer' => !empty($answer) ? $answer : null,
            'date_of_birth' => !empty($db_date) ? $db_date : null
        );

        $check_fields = array(
            'security_question' => 'Security Question',
            'security_answer' => 'Security Answer',
            'date_of_birth' => 'Date of Birth'
        );

        $action = "";
        if (empty($call_security_info)) {
            $input['customer_id'] = $customer_id;
            $this->cms_model->master_insert($input, "call_security");
            $action = "inserted";
        } else {
            $condition = array('customer_id' => $customer_id);
            $this->cms_model->master_update("call_security", $input, $condition);
            $action = "updated";
        }
        echo "1^";
        exit;
    }

    public function get_timezone($default = "") {
        $country = $this->input->post('country');
        if (!empty($default)) {
            $country = $default;
        }
        $timezone = $this->cms_model->get_info($country, 'timezone', 'country');
        $option = '<option value="">Select Timezone</option>';
        if (!empty($timezone)) {
            foreach ($timezone as $t) {
                $option .= '<option value=' . $t['id'] . '>' . $t['timezone'] . ' ' . $t['utc_offset'] . '</option>';
            }
        }
        if (!empty($default)) {
            return $timezone;
        } else {
            echo $option;
        }
    }

    public function check_package_service($package_id = "", $get = false, $order_id = "", $is_edit = "") {
        $package_info = $this->cms_model->get_info($package_id, "packages", $field = 'id');
        $package = array();
        $provider_id = my_provider_id();
        if (!empty($package_info)) {
            $package_services = $package_info[0]['services'];
            $services = explode(',', $package_services);
            $package['service_addon'] = $this->customers_model->get_packages_by_agent($provider_id, 'Addon', $services);
        }
        echo json_encode($package);
    }

//    functions for notes tab
    function get_notes($customer_id) {
        $final['recordsTotal'] = $this->customers_model->get_customer_notes($customer_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $notes = $this->customers_model->get_customer_notes($customer_id, 'result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($notes as $key => $val) {
            $notes[$key] = $val;
            $notes[$key]['responsive'] = '';
        }
        $final['data'] = $notes;
        echo json_encode($final);
    }

    public function load_manage_notes() {
        $note_id = base64_decode($this->input->post('note'));
        $this->data['note_for'] = base64_encode($this->input->post('note_for'));
        $this->data['note_info'] = $this->cms_model->get_all_details('customer_notes', array('id' => $note_id))->row_array();
        $this->load->view('customers/manage_note', $this->data);
    }

    public function delete_note() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $note = $this->cms_model->get_all_details('customer_notes', array('id' => $id))->row_array();
            if (!empty($note)) {
                $this->cms_model->master_delete('customer_notes', array('id' => $id));
                echo '1^';
            } else {
                echo '0^';
            }
        } else {
            echo '0^';
        }
    }

//    functions for customer credit tab
    function get_credits($customer_id) {
        $final['recordsTotal'] = $this->customers_model->get_customer_credits($customer_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $credits = $this->customers_model->get_customer_credits($customer_id, 'result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($credits as $key => $val) {
            $credits[$key] = $val;
            $credits[$key]['month'] = date('Y-F', strtotime($val['created']));
            $credits[$key]['created'] = date('Y-m-d', strtotime($val['created']));
            $credits[$key]['responsive'] = '';
        }
        $final['data'] = $credits;
        echo json_encode($final);
    }

    public function customer_credit() {
        $customer_credit = 0.00;
        $customer_id = base64_decode($this->input->post('customer_id'));
        if ($customer_id) {
            $customer_credit = $this->customers_model->get_customer_total_credit($customer_id);
        }
        echo number_format($customer_credit, 2);
    }

    public function load_manage_credit() {
        $credit_id = base64_decode($this->input->post('credit'));
        $this->data['credit_for'] = base64_encode($this->input->post('credit_for'));
        $this->data['credit_info'] = $this->cms_model->get_all_details('customer_credits', array('id' => $credit_id))->row_array();
        $this->load->view('customers/manage_credit', $this->data);
    }

    function save_credit() {
        $credit_id = base64_decode($this->input->post('credit_id'));
        $credit_info = $this->cms_model->get_all_details('customer_credits', array('id' => $credit_id))->row_array();
        $this->form_validation->set_rules("credit_type", "Credit Type", "trim|required|callback_validate_credit_type");
        $this->form_validation->set_rules("credit_amount", "Credit Amount", "trim|required");
        $this->form_validation->set_rules("description", "Description", "trim|required");
        $provider_id = my_provider_id();
        if (($this->form_validation->run() == TRUE)) {
            $customer_id = base64_decode($this->input->post('credit_for'));
            $user_data = $this->session->userdata('session_info');
            $added_by = NULL;
            if (is_provider() && my_provider_id() != $user_data['user_id']) {
                $added_by = $user_data['user_id'];
            }
            $type = $this->input->post('credit_type');
            $amount = $this->input->post('credit_amount');
            $description = $this->input->post('description');

            $input = array(
                'type' => $type,
                'amount' => $amount,
                'description' => !empty($description) ? $description : NULL,
                'provider_id' => $provider_id,
            );
            if (empty($credit_id)) {
                $input['customer_id'] = $customer_id;
                $input['added_by'] = $added_by;
                $this->cms_model->master_insert($input, "customer_credits");
            } else {
                $condition = array('id' => $credit_id);
                $this->cms_model->master_update("customer_credits", $input, $condition);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function validate_credit_type($value) {
        if ($value != 'General') {
            $credit_id = base64_decode($this->input->post('credit_id'));
            $customer_id = base64_decode($this->input->post('credit_for'));
            $where = "type IN ('" . $value . "') AND customer_id='" . $customer_id . "'";
            if (!empty($credit_id)) {
                $where .= 'AND id!=' . $credit_id;
            }
            $customer_credit = $this->cms_model->get_all_details('customer_credits', $where)->result_array();
            if (!empty($customer_credit)) {
                $this->form_validation->set_message('validate_credit_type', 'Credit type <b>' . $value . '</b> exists for current month, please select another type.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function delete_credit() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $note = $this->cms_model->get_all_details('customer_credits', array('id' => $id))->row_array();
            if (!empty($note)) {
                $this->cms_model->master_delete('customer_credits', array('id' => $id));
                echo '1^';
            } else {
                echo '0^';
            }
        } else {
            echo '0^';
        }
    }

//    Eviction tab fucntions
    function get_notification($customer_id) {
        $notification_type = array('notification' => 'Notification',
            'eviction' => 'Eviction notice');
        $final['recordsTotal'] = $this->customers_model->get_customer_notification($customer_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $notifications = $this->customers_model->get_customer_notification($customer_id, 'result')->result_array();
        foreach ($notifications as $key => $val) {
            $notifications[$key] = $val;
            $notifications[$key]['responsive'] = '';
//            $notifications[$key]['type'] = array_key_exists($val['type'], $notification_type) ? $notification_type[$val['type']] : $val['type'];
            $notifications[$key]['updated_time'] = date('Y-m-d', strtotime($val['updated_time']));
        }
        $final['data'] = $notifications;
        echo json_encode($final);
    }

    public function eviction_email_view() {
        $id = base64_decode($this->input->post('id'));
        $data['notice_id'] = $this->input->post('notice_id');
        $notice_id = base64_decode($this->input->post('notice_id'));
        $notify_type = $this->input->post('notify_type');
        $provider_id = my_provider_id();
        $customer = $this->cms_model->get_all_details('customers', array('cid' => $id))->row_array();
        $eviction_template = $this->cms_model->get_all_details('templates', array('provider_id' => $provider_id, 'type' => 'Notice', 'is_enable' => 1))->row_array();
        $data['eviction_template'] = $eviction_template;
        $data['canned_responses'] = $this->cms_model->get_all_details('canned_responses', array('provider_id' => $provider_id))->result_array();
        $data['customer'] = $customer;
        $data['customer_property'] = $this->cms_model->get_all_details('properties', array('id' => $customer['property_id']))->row_array();
        $data['provider_info'] = $this->cms_model->get_all_details('providers', array('id' => $customer['provider_id']))->row_array();
        $data['transactions'] = $this->cms_model->get_all_details('transactions', array('customer_id' => $customer['cid'], 'type' => 'cron'), array(array('field' => 'id', 'type' => 'DESC')), array('l1' => 3, 'l2' => 0))->result_array();
        if (!empty($notice_id)) {
            $data['notification_info'] = $notification_info = $this->cms_model->get_all_details('notification_history', array('id' => $notice_id))->row_array();
        }
        if ($notify_type == 'notification') {
            echo $this->load->view('eviction/notification_content', $data, true);
        } if ($notify_type == 'eviction') {
            echo $this->load->view('eviction/eviction_content', $data, true);
        } else {
            if (is_null($notify_type)) {
                echo $this->load->view("customers/eviction_email", $data, true);
            }
        }
        exit;
    }

    public function send_notice_to_customer() {
        $customer_id = $this->input->post('customer_id');
        $notify_type = $this->input->post('notify_type');
        $canned_response_id = $this->input->post('canned_response_id');
        $email_to = $this->input->post('email_to');
        $subject = $this->input->post('subject');
        $post_message = $this->input->post('message');
        if ($notify_type == 'notification') {
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('email_to', 'Email', 'trim|required');
        } else if ($notify_type == 'eviction') {
            $this->form_validation->set_rules('eviction_to', "Tenant's name", 'trim|required');
            $this->form_validation->set_rules('eviction_from', "Landlord's name", 'trim|required');
            $this->form_validation->set_rules('eviction_address', 'Address', 'trim|required');
            $this->form_validation->set_rules('rent_amount', 'Rent Amount', 'trim|required');
            $this->form_validation->set_rules('eviction_date', 'Eviction Date', 'trim|required');
            $this->form_validation->set_rules('from_date[]', 'Rent From Date', 'trim|required');
            $this->form_validation->set_rules('to_date[]', 'Rent To Date', 'trim|required');
            $this->form_validation->set_rules('rent_charged[]', 'Rent Charged', 'trim|required');
//            $this->form_validation->set_rules('rent_paid[]', 'Rent Paid', 'trim|required');
            $this->form_validation->set_rules('rent_owing[]', 'Rent Owing', 'trim|required');
            $this->form_validation->set_rules('total_rent_owing', 'Total Rent Owing', 'trim|required');
            $this->form_validation->set_rules('signature_type', 'Signature type', 'trim|required');
            $this->form_validation->set_rules('s_fname', 'Landloard First name', 'trim|required');
//            $this->form_validation->set_rules('s_lname', 'Landloard Last name', 'trim|required');
            $this->form_validation->set_rules('s_phone', 'Landloard Phone number', 'trim|required');
            $this->form_validation->set_rules('s_date', 'Signature Date', 'trim|required');
            $this->form_validation->set_rules('s_signature', 'Landloard signature', 'trim|required');
        }

        if ($this->form_validation->run() == TRUE) {
            $provider_id = my_provider_id();
            $send_email_config = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
            if (!empty($send_email_config)) {
                if ($notify_type == 'notification') {
                    $message = $this->load->view('email_template/default_header.php', array(), true);
                    $message .= $post_message;
                    $message .= $this->load->view('email_template/default_footer.php', array(), true);

                    $email_array = array(
                        'to' => $email_to,
                        'subject' => $subject,
                        'body_messages' => $message
                    );
                    if (!send_email($email_array)) {
                        echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>Something went wrong! We are not able to send you email.</div><br>";
                    } else {
                        $notification_history = array('email_to' => $email_to,
                            'canned_response_id' => !empty($canned_response_id) ? base64_decode($canned_response_id) : NULL,
                            'subject' => $subject,
                            'type' => $notify_type,
                            'customer_id' => $customer_id,
                            'provider_id' => $provider_id,
                            'content' => $post_message,
                        );

                        $history_id = $this->cms_model->master_insert($notification_history, "notification_history");
                        if ($history_id) {
                            echo "1^";
                        } else {
                            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>Notification sent, but not logged in the system.</div><br>";
                        }
                    }
                } else if ($notify_type == 'eviction') {
                    $data = $_POST;
                    unset($data['customer_id']);
                    unset($data['notice_id']);
                    unset($data['notify_type']);
                    $notification_history = array(
                        'type' => $notify_type,
                        'customer_id' => $customer_id,
                        'provider_id' => $provider_id,
                        'pdf_content' => json_encode($data),
                    );

                    $history_id = $this->cms_model->master_insert($notification_history, "notification_history");
                    if ($history_id) {
                        $eviction_folder_path = $this->config->item('uploads_path') . '/eviction';
                        $folder_exist = true;
                        if (!created_directory($eviction_folder_path)) {
                            $folder_exist = false;
                            $folder_error = 'Folder could not created.';
                        }
                        $eviction_folder_sub_path = $this->config->item('uploads_path') . '/eviction/' . $history_id;
                        if (!created_directory($eviction_folder_sub_path)) {
                            $folder_exist = false;
                            $folder_error = 'Folder could not created.';
                        }
                        $eviction_file_name = 'EvictionNoticeOn_' . date('d_m_Y_h_i_s') . '.pdf';

                        $pdf_content_html = $this->load->view("eviction/content", $_POST, true);
                        require_once(APPPATH . 'libraries/Pdf.php');
                        $this->pdf = new Pdf();
                        $this->pdf->WriteHTML($pdf_content_html);
                        $this->pdf->Output($eviction_folder_sub_path . '/' . $eviction_file_name, 'F');

                        $update_data = array('pdf_file' => $eviction_file_name);
                        $condition = array('id' => $history_id);
                        $this->cms_model->master_update("notification_history", $update_data, $condition);
                        echo "1^";
                    } else {
                        echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>Notification sent, but not logged in the system.</div><br>";
                    }
                }
            } else {
                echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>SMTP detail not found.Please Enter Send Email Configuration</div><br>";
            }
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    public function delete_notification() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $notification_info = $this->cms_model->get_all_details('notification_history', array('id' => $id))->row_array();
            if (!empty($notification_info)) {
                $this->cms_model->master_delete('notification_history', array('id' => $id));
                echo '1^';
            } else {
                echo '0^';
            }
        } else {
            echo '0^';
        }
    }

    //Contract Tab

    function get_contracts($customer_id) {
        $final['recordsTotal'] = $this->customers_model->get_customer_contracts($customer_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $contracts = $this->customers_model->get_customer_contracts($customer_id, 'result')->result_array();
        foreach ($contracts as $key => $val) {
            $contracts[$key] = $val;
            $contracts[$key]['responsive'] = '';
            $contracts[$key]['start_date'] = date('Y-m-d', strtotime($val['start_date']));
            $contracts[$key]['end_date'] = date('Y-m-d', strtotime($val['end_date']));
        }
        $final['data'] = $contracts;
        echo json_encode($final);
    }

    public function load_manage_contract() {
        $contract_id = base64_decode($this->input->post('contract'));
        $this->data['contract_for'] = base64_encode($this->input->post('contract_for'));
        $this->data['contract_info'] = $contract_info = $this->cms_model->get_all_details('customer_contracts', array('id' => $contract_id))->row_array();
        if (!empty($contract_info)) {
            $this->data['contract_images'] = $this->cms_model->get_all_details('customer_contract_files', array('contract_id' => $contract_info['id']))->result_array();
        }
        $this->load->view('customers/manage_contract', $this->data);
    }

    public function save_contract() {
        $contract_id = base64_decode($this->input->post('contract'));
        $provider_id = my_provider_id();

        $this->form_validation->set_rules("name", "Name", "trim|required");
        $this->form_validation->set_rules("notification_email", "Email", "trim|required");
        $this->form_validation->set_rules("start_date", "start_date", "trim|required");
        $this->form_validation->set_rules("end_date", "end_date", "trim|required");
        $this->form_validation->set_rules("reminder_days", "Reminder Days", "trim|required|numeric");
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $this->form_validation->set_rules("image", "Contract File", "callback_file_check_extension");
        }

        if (($this->form_validation->run() == TRUE)) {
            $customer_id = base64_decode($this->input->post('contract_for'));
            $provider_id = my_provider_id();

            $input = array(
                'name' => $this->input->post('name'),
                'notification_email' => $this->input->post('notification_email'),
                'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
                'end_date' => date('Y-m-d', strtotime($this->input->post('end_date'))),
                'reminder_days' => $this->input->post('reminder_days'),
            );
            if (empty($contract_id)) {
                $input['customer_id'] = $customer_id;
                $input['provider_id'] = $provider_id;
                $contract_id = $this->cms_model->master_insert($input, "customer_contracts");
            } else {
                $condition = array('id' => $contract_id);
                $this->cms_model->master_update("customer_contracts", $input, $condition);
            }

            $path = $this->config->item('uploads_path') . '/contracts/';
            if (!file_exists($path)) {
                created_directory($path);
            }
            $folder_error = '';
            $folder_path = $path . $contract_id;
            $folder_exist = true;

            if (!file_exists($folder_path)) {
                if (!created_directory($folder_path)) {
                    $folder_exist = false;
                    $folder_error = 'Contracts Folder could not created.';
                }
            }
            if (empty($folder_error) && file_exists($folder_path) && isset($_FILES['image']) && $_FILES['image']['size'] > 0 && $contract_id > 0) {
                $file_name = upload_image('image', $folder_path, '', '', 'gif|jpg|png|jpeg|pdf');
                if (empty($file_name)) {
                    $folder_error .= $this->upload->display_errors();
                    echo "2^" . base64_encode($contract_id) . "^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . $folder_error . "</div><br>";
                    exit;
                } else {
                    $finput = array(
                        'file_name' => $file_name,
                        'contract_id' => $contract_id,
                    );
                    $this->cms_model->master_insert($finput, "customer_contract_files");
                }
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function delete_contract_img() {
        $id = base64_decode($this->input->post('id'));
        $contract_info = $this->cms_model->get_all_details('customer_contract_files', array('id' => $id))->row_array();
        if (!empty($contract_info)) {
            $contract_image = $contract_info['file_name'];
            $this->cms_model->master_delete('customer_contract_files', array('id' => $id));
            $image_path = $this->config->item('uploads_path') . '/contracts/' . $contract_info['contract_id'] . '/' . $contract_image;
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
            echo "1^";
        } else {
            echo "0^";
        }
    }

    public function delete_contract() {
        $id = base64_decode($this->input->post('id'));
        $contract_info = $this->cms_model->get_all_details('customer_contracts', array('id' => $id))->row_array();
        $contract_files = $this->cms_model->get_all_details('customer_contract_files', array('contract_id' => $id))->result_array();
        if (!empty($contract_info)) {
            $this->cms_model->master_delete('customer_contracts', array('id' => $id));
            if (!empty($contract_files)) {
                $image_path = $this->config->item('uploads_path') . '/contracts/' . $contract_info['id'];
                if (deleteDirectory($image_path)) {
                    $this->cms_model->master_delete('customer_contract_files', array('contract_id' => $id));
                }
            }
            echo "1^";
        } else {
            echo "0^";
        }
    }

    public function file_check_extension($str) {
        $not_allowed_extension = array('exe');
        $file_extesntion = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (in_array($file_extesntion, $not_allowed_extension)) {
            $this->form_validation->set_message('file_check_extension', 'Please select only pdf/doc/txtgif/jpg/png file.');
            return false;
        } else {
            return true;
        }
    }

    // Invoice Tab
    function get_invoices($customer_id) {
        $final['recordsTotal'] = $this->customers_model->get_customer_invoices($customer_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $invoices = $this->customers_model->get_customer_invoices($customer_id, 'result')->result_array();
        foreach ($invoices as $key => $val) {
            $invoices[$key] = $val;
            $invoices[$key]['responsive'] = '';
            $invoices[$key]['ccnumber'] = substr_replace(convert_enc_string($val['ccnumber']), '', 0, -4);
            $invoices[$key]['price'] = number_format($val['price'], 2);
            $invoices[$key]['created'] = date('Y-m-d H:i:s', strtotime($val['created']));
            $invoices[$key]['month'] = date('Y-F', strtotime($val['created']));
        }
        $final['data'] = $invoices;
        echo json_encode($final);
    }

    public function load_manage_invoice() {
        $invoice_id = base64_decode($this->input->post('invoice'));
        $invoice_for = $this->input->post('invoice_for');
        $this->data['invoice_for'] = base64_encode($invoice_for);
        $this->data['invoice_info'] = $this->cms_model->get_all_details('transactions', array('id' => $invoice_id))->row_array();
        $this->data['customer_info'] = $this->cms_model->get_all_details('customers', array('cid' => $invoice_for))->row_array();
        $this->data['customer_property'] = $this->cms_model->get_all_details('properties', array('id' => $this->data['customer_info']['property_id']))->row_array();
        $this->load->view('customers/manage_invoice', $this->data);
    }

    function add_manually_invoice() {
        $invoice_id = base64_decode($this->input->post('invoice'));
        $invoice_info = $this->cms_model->get_all_details('transactions', array('id' => $invoice_id))->row_array();
        if (!empty($invoice_info) && $invoice_info['type'] == 'cron') {
            $payment_status = $this->input->post('payment_status');

            if (isset($invoice_info['log_data']) && !empty($invoice_info['log_data'])) {
                $log_data = json_decode($invoice_info['log_data'], true);

                $log_data['packages']['name'] = $this->input->post('package_name');
                $log_data['packages']['price'] = $this->input->post('package_price');
                $log_data['packages']['total_price'] = $this->input->post('package_total_price');
                $log_data['packages_label'] = '';

                $log_data['lb_customer_credit'] = $this->input->post('lb_customer_credit');
                $log_data['tax'] = $this->input->post('tax');
                $log_data['price'] = $this->input->post('total_price');
                $log_data['total_price'] = $this->input->post('total_price');
                $log_data['grand_total'] = $this->input->post('grand_total');
                if (isset($log_data['addons']) && !empty($log_data['addons'])) {
                    $addon = $this->input->post('addon');
                    $log_data['addon_label'] = '';
                    $addon_arr = array();
                    foreach ($log_data['addons'] as $key => $value) {
                        $addon_arr[] = array(
                            'id' => $value['id'],
                            'name' => isset($addon[$key]['addon_name']) ? $addon[$key]['addon_name'] : 0,
                            'term' => $value['term'],
                            'price' => isset($addon[$key]['addon_price']) ? $addon[$key]['addon_price'] : 0,
                            'total_price' => isset($addon[$key]['total_price']) ? $addon[$key]['total_price'] : 0,
                        );
                    }
                    $log_data['addons'] = $addon_arr;
                }
                $input = array(
                    'detail' => $this->input->post('detail'),
                    'payment_status' => $payment_status,
                    'price' => $log_data['grand_total'],
                    'log_data' => json_encode($log_data),
                    'created' => date('Y-m-d H:i:s', strtotime($this->input->post('invoice_date'))),
                );

                $condition = array('id' => $invoice_id);
                $this->cms_model->master_update("transactions", $input, $condition);
                $provider_id = my_provider_id();
                $template = array();
                if ($payment_status == 'paid') {
                    $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Success', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
                } else {
                    $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Fail', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
                }
                if (!empty($template)) {
                    generate_pdf_invoice($invoice_id, "send", "", false);
                }
                echo "1^";
            }
        } else {
            $incude_tax = $this->input->post('include_tax');
            $payment_status = $this->input->post('payment_status');
            $customer_id = base64_decode($this->input->post('invoice_for'));
            $this->form_validation->set_rules("description", "Description", "trim|required");
            $this->form_validation->set_rules("c_price", "Price", "trim|required");
            if (($this->form_validation->run() == TRUE)) {
                $description = $this->input->post('description');
                $m_tax_per = $this->input->post('m_tax_per');
                $final_price = trim(str_replace(',', '', $this->input->post('calculated_final_price')));
                $calculated_tax = trim(str_replace(',', '', $this->input->post('calculated_tax')));
                $total_price = $grand_total = "";
                if (!empty($incude_tax)) {
                    $total_price = floatval($final_price);
                } else {
                    $total_price = floatval($final_price - $calculated_tax);
                }

                $log_data = array(
                    'type' => 'manually_invoice',
                    'price' => $total_price,
                    'total_price' => $total_price,
                    'tax' => $calculated_tax,
                    'tax_per' => !empty($m_tax_per) ? $m_tax_per : 0,
                    'grand_total' => floatval($final_price),
                    'tax_included' => !empty($incude_tax) ? 1 : 0
                );
                $transcation_input = array(
                    'customer_id' => $customer_id,
                    'payment_status' => !empty($payment_status) ? $payment_status : 'pending',
                    'type' => 'manual',
                    'payment_type' => 'Cash',
                    'price' => floatval($final_price),
                    'detail' => $description,
                    'log_data' => json_encode($log_data),
                    'created' => date('Y-m-d H:i:s', strtotime($this->input->post('invoice_date'))),
                );
                $transcation_input['log_data'] = json_encode($log_data);
                if (!empty($invoice_id)) {
                    $condition = array('id' => $invoice_id);
                    $this->cms_model->master_update("transactions", $transcation_input, $condition);
                } else {
                    $invoice_id = $this->cms_model->master_insert($transcation_input, "transactions");
                }
                $provider_id = my_provider_id();
                $template = array();
                if ($payment_status == 'paid') {
                    $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Success', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
                } else {
                    $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Fail', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
                }
                if (!empty($template)) {
                    generate_pdf_invoice($invoice_id, "send", "", false);
                }

                echo "1^";
            } else {
                echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
            }
        }
    }

    function check_invoice_exist($invoice_id) {
        $invoice_id = base64_decode($invoice_id);
        $transaction_info = $this->cms_model->get_all_details('transactions', array('id' => $invoice_id))->row_array();
        if (!empty($transaction_info)) {
            echo "1^";
        } else {
            echo "0^";
        }
        exit;
    }

    function generate_pdf_invoice($invoice_id, $action = "view", $time_stamp = "") {
        $invoice_id = base64_decode($invoice_id);
        generate_pdf_invoice($invoice_id, $action, $time_stamp);
    }

    function send_pdf_to_customer($invoice_id) {
        $provider_id = my_provider_id();
        $invoice_id = base64_decode($invoice_id);
        $send_email_config = $this->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
        if (!empty($send_email_config)) {
            $is_enable = false;
            $transaction_info = $this->cms_model->get_all_details('transactions', array('id' => $invoice_id))->row_array();
            $payment_status = $transaction_info['payment_status'];
            $template = array();
            if ($payment_status == 'pending' || $payment_status == 'fail') {
                $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Fail', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
            } else {
                $template = $this->cms_model->get_all_details('templates', array('name' => 'Invoice_Success', 'is_enable' => 1, 'provider_id' => $provider_id))->row_array();
            }
            if (!empty($template)) {
                generate_pdf_invoice($invoice_id, "send");
            } else {
                echo "3";
            }
        } else {
            echo "2";
        }
    }

    public function delete_invoice() {
        $invoice_id = base64_decode($this->input->post('id'));
        $data = array(
            'is_deleted' => '1'
        );
        $condition = array(
            'id' => $invoice_id
        );
        $this->cms_model->master_update("transactions", $data, $condition);
        echo '1';
    }

    function test() {
        $this->load->view("eviction/content", array());
    }

    public function test_pdf() {
//          $data['transactions'] = $this->cms_model->get_all_details('transactions', array('customer_id' => 1, 'type' => 'cron'), array(array('field' => 'id', 'type' => 'DESC')), array('l1' => 3, 'l2' => 0))->result_array();
        $data = array(
            'customer_id' => 1,
            'notice_id' => '',
            'notify_type' => 'eviction',
            'eviction_to' => "Mikinj's patel1",
            'eviction_from' => 'Akk'
            , 'eviction_address' => "Regant square,  Surat,  Ontario,  395009,  Canada"
            , 'rent_amount' => 2522.45
            , 'eviction_date' => '09/ 04 / 2018'
            , 'from_date' => Array
                (
                '0' => '08 / 18 / 2018'
                , '1' => '08 / 18 / 2018'
                , '2' => '08 / 18 / 2018'
            ),
            'to_date' => Array
                (
                '0' => '02 / 18 / 2019'
                , '1' => '02 / 18 / 2019'
                , '2' => '02 / 18 / 2019'
            )
            , 'rent_charged' => Array
                (
                '0' => 021
                , '1' => 1253.55
                , '2' => 1247.9
            )
            , 'rent_paid' => Array
                (
                '0' => ''
                , '1' => ''
                , '2' => ''
            ), 'rent_owing' => Array
                (
                '0' => 21.00, '1' => 1253.55, '2' => 1247.9
            )
            , 'total_rent_owing' => 2522.45,
            'signature_type' => 'landlord'
            , 's_fname' => 'Akk'
            , 's_lname' => ''
            , 's_phone' => '(123) 4 -'
            , 's_date' => '08/21/2018'
            , 's_signature' => ''
        );
        $pdf_content_html = $this->load->view("eviction/content", $data, true);
//        echo $pdf_content_html;
//        exit;
        require_once(APPPATH . 'libraries/Pdf.php');
        $this->pdf = new Pdf();
//        $this->pdf->useAdobeCJK = true;
//        $this->pdf->autoLangToFont = true;
//        $this->pdf->autoScriptToLang = true;
        $this->pdf->WriteHTML($pdf_content_html);
//        $this->pdf->Output($this->config->item('uploads_path') . '/contracts/test.pdf', 'F');
        $this->pdf->Output();
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Devices.php */