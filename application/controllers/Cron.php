<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model', 'setting_model', 'customers_model', 'package_model'));
    }

    public function auto_increase_package_price() {
        $date = date('Y-m-d');
        $providers = $this->cms_model->get_all_details('providers', array('yearly_auto_increase_date' => $date, 'yearly_auto_increase_specific_time' => 'yes'))->result_array();
        if (!empty($providers)) {
            foreach ($providers as $p) {
                if (!empty($p['yearly_auto_increase_value']) && $p['yearly_auto_increase_value'] > 0) {
                    $config = array(
                        'provider_id' => $p['id'],
                        'percentage' => $p['id'],
                    );
                    auto_increase_package_price_now($config, true);
                }
            }
        }
    }

    public function terminate_customers() {
        $terminmate_customers = $this->customers_model->get_termination_customer_list();
        if (!empty($terminmate_customers)) {
            foreach ($terminmate_customers as $t_customer) {

                //update invoice status to "Cancel"
                $invoice_data = array(
                    'payment_status' => 'canceled'
                );
                $invoice_condition = array('cid' => $t_customer['cid'], 'payment_status !=' => 'paid');
                $this->cms_model->master_update('transactions', $invoice_data, $invoice_condition);

                //update customer status
                $fields = array(
                    'status' => 'terminate',
                    'termination_event' => NULL,
                    'termination_specific_date' => NULL,
                    'last_terminated_on' => date('Y-m-d H:i:s')
                );
                $this->cms_model->master_update('customers', $fields, array('cid' => $t_customer['cid']));
            }
        }
    }

    public function renew_account() {
        $this->db->db_debug = FALSE;

        // terminate customers - on specific date or on renewal
        //   $this->terminate_customers();
        //get customers
        $customers = $this->customers_model->get_renewal_customers_list();

        if (!empty($customers)) {
            foreach ($customers as $customer) {
                // if ($customer['cid'] == '1') {
                $log_data = array();
                $log_data['type'] = "renewal_customer";

                $provider_id = $customer['provider_id'];
                $package_id_basic = $customer['basic_package'];
                $package_addon = $customer['basic_addon'];
                $customer_id = $customer['cid'];
                $customer_renewal_date = $customer['renewal_date'];
                $property_id = $customer['property_id'];

                $provider_info = $this->cms_model->get_all_details('providers', array('id' => $provider_id))->row_array();
                $package_info = $this->cms_model->get_all_details('packages', array('id' => $package_id_basic))->row_array();

                $property_info = $this->cms_model->get_all_details('properties', array('id' => $property_id))->row_array();
                $price = 0;
                $paypal_description = "";

                $term = $package_info['term'];

                //get amount                
                $price_array = $this->calculate_price($package_id_basic, $package_addon);
                if (isset($price_array['log_arr']['package'])) {
                    $log_data['packages'] = $price_array['log_arr']['package'];
                    $log_data['packages_label'] = 'Package : ';
                }
                if (isset($price_array['log_arr']['addon'])) {
                    $log_data['addons'] = $price_array['log_arr']['addon'];
                    $log_data['addon_label'] = 'Addon : ';
                }
                $package_description = $price_array['description'];

                $price = $total_package_addon_price = $price_array['total_price'];
                if (!empty($total_package_addon_price)) {

                    //check customer_available credit                            
                    $customer_available_credit = get_customer_total_credit($customer['cid'], true);

                    $remaining_credit = 0;
                    $credit_used_amount = 0;
                    $inserted_customer_credit_id = "";
                    if ($customer_available_credit > 0) {
                        if ($customer_available_credit >= $price) {
                            $remaining_credit = $customer_available_credit - $price;
                            $credit_used_amount = $price;
                            $price = 0;
                        } else if ($customer_available_credit < $price) {
                            $price = $price - $customer_available_credit;
                            $credit_used_amount = $customer_available_credit;
                        }

                        if ($credit_used_amount > 0) {
                            $customer_credit_data = array(
                                'provider_id' => $customer['provider_id'],
                                'customer_id' => $customer['cid'],
                                'amount' => $credit_used_amount * -1,
                                'description' => 'Credit used for customer while renewal.'
                            );
                            $inserted_customer_credit_id = $this->cms_model->master_insert($customer_credit_data, "customer_credits");
                            $get_customer_total_credit = get_customer_total_credit($customer['cid']);
                            $log_data['customer_credit_used'] = number_format($credit_used_amount * -1, 2);
                            $log_data['customer_credit_available'] = $get_customer_total_credit;
                            $log_data['lb_customer_credit'] = 'Credit Used( Available : $' . $get_customer_total_credit . ')';
                        }
                    }

                    //calculate tax                    
                    $tax = 0;
                    if (!empty($property_info)) {
                        if ($property_info['country'] == "Canada" && $property_info['state'] == "Quebec") {
                            $tax = 5;
                        } elseif ($property_info['country'] == "Canada" && $property_info['state'] != "Quebec") {
                            $tax = 13;
                        }
                    } else {
                        if ($customer['country'] == "Canada" && $customer['billing_state'] == "Quebec") {
                            $tax = 5;
                        } elseif ($customer['country'] == "Canada" && $customer['billing_state'] != "Quebec") {
                            $tax = 13;
                        }
                    }

                    $log_data['tax_per'] = $tax;

                    $total = $price;
                    $total_tax = $total * $tax / 100;
                    $final_total = $total + $total_tax;

                    $log_data['tax_included'] = 0;
                    $log_data['price'] = number_format($total, 2);
                    $log_data['price'] = number_format($total, 2);
                    $log_data['total_price'] = number_format($total, 2);
                    $log_data['tax'] = number_format($total_tax, 2);

                    $grand_total = ($final_total > 0) ? $final_total : 0;

                    $log_data['grand_total'] = number_format($grand_total, 2);
                    $payment_status = $invoice_id = "";
                    $suspend_fail = false;
                    if ($provider_info['payment_system'] == "1" && $customer['payment_type'] == "Credit" && $grand_total > 0) {
                        $paypal_description = "Renew Customer : " . $customer['name'] . '(' . $customer['cid'] . ')';
                        $result = make_payment($provider_id, $customer['cid'], $grand_total, $paypal_description);
                        $payment_result = json_decode($result);

                        $invoice_id = $payment_result->invoice_id;
                        if (!empty($payment_result) && $payment_result->status == "success") {
                            $payment_status = "paid";
                        } else {
                            $payment_status = "fail";
                            $suspend_fail = true;
                        }
                    } elseif ($customer['payment_type'] == "Cash" && $grand_total > 0) {
                        $payment_status = "pending";
                        $suspend_fail = true;
                    } elseif ($grand_total == 0) {
                        $payment_status = "paid";
                    } else {
                        $payment_status = "fail";
                    }

                    //update customer status to suspen
                    if ($suspend_fail) {
                        $condition = array('cid' => $customer['cid']);
                        $cdata = array('status' => 'suspended');
                        $this->cms_model->master_update('customers', $cdata, $condition);
                    }

                    //Update Customer renewal_date                    
                    $next_renewal_date = date('Y-m-d', strtotime("+" . $term . " month", strtotime($customer_renewal_date)));
                    $this->cms_model->master_update('customers', array('renewal_date' => $next_renewal_date), array('cid' => $customer['cid']));

                    //Transaction
                    //generate invoices and stored in Transaction table
                    $package_str = $customer['basic_package'];
                    if (!empty($package_addon)) {
                        $package_str .= ',' . $customer['basic_addon'];
                    }

                    $billing_address = $customer['billing_address'];
                    $billing_city = $customer['billing_city'];
                    $billing_state = $customer['billing_state'];
                    $billing_country = $customer['billing_country'];
                    $billing_zipcode = $customer['billing_zipcode'];
                    if ($customer['billing_check'] == 'yes' && !empty($property_info)) {
                        $property_info = $this->cms_model->get_all_details('properties', array('id' => $customer['property_id']))->row_array();
                        if (!empty($property_info)) {
                            $billing_address = $property_info['address'];
                            $billing_city = $property_info['city'];
                            $billing_state = $property_info['state'];
                            $billing_country = $property_info['country'];
                            $billing_zipcode = $property_info['zip_code'];
                        }
                    }
                    $transcation_input = array(
                        'customer_id' => $customer_id,
                        'invoice_id' => !empty($invoice_id) ? $invoice_id : NULL,
                        'package_id' => $package_str,
                        'payment_status' => $payment_status,
                        'next_pay_date' => $next_renewal_date,
                        'payment_type' => $customer['payment_type'],
                        'billing_name' => $customer['billing_name'],
                        'billing_address' => $billing_address,
                        'billing_city' => $billing_city,
                        'billing_state' => $billing_state,
                        'billing_country' => $billing_country,
                        'billing_zipcode' => $billing_zipcode,
                        'billing_phone' => $customer['billing_phone'],
                        'billing_email' => $customer['billing_email'],
                        'price' => $grand_total,
                        'detail' => $package_description,
                        'log_data' => json_encode($log_data)
                    );

                    if ($provider_info['payment_system'] == "1" && $customer['payment_type'] == "Credit") {
                        $card_type = (strlen($customer['card_type']) > 11) ? convert_enc_string($customer['card_type']) : $customer['card_type'];
                        $ccmonth = (strlen($customer['ccmonth']) > 2) ? convert_enc_string($customer['ccmonth']) : $customer['ccmonth'];
                        $ccyear = (strlen($customer['ccyear']) > 4) ? convert_enc_string($customer['ccyear']) : $customer['ccyear'];

                        $transcation_input['namecard'] = $customer['namecard'];
                        $transcation_input['ccnumber'] = $customer['ccnumber'];
                        $transcation_input['card_type'] = $card_type;
                        $transcation_input['ccmonth'] = $ccmonth;
                        $transcation_input['ccyear'] = $ccyear;
                        $transcation_input['ccvc'] = $customer['ccvc'];
                    }
                    $transaction_id = $this->cms_model->master_insert($transcation_input, "transactions");
                    if (!empty($inserted_customer_credit_id) && $inserted_customer_credit_id > 0) {
                        $customer_credit_desc_str = 'Credit used for customer renewal.';
                        if (!empty($transaction_id) && $transaction_id > 0) {
                            $customer_credit_desc_str .= ' Invoice ID :' . $transaction_id;
                        }
                        $customer_credit_desc = array(
                            'description' => $customer_credit_desc_str
                        );
                        $this->cms_model->master_update('customer_credits', $customer_credit_desc, array('id' => $inserted_customer_credit_id));
                    }
                    generate_pdf_invoice($transaction_id, "send", "", false);
                }
                //}
            }
        }
    }

    function calculate_price($package_id_basic, $package_addon = '') {
        $log_arr = array();
        $addon_arr = array();
        $package_str = $package_id_basic;
        if (!empty($package_addon)) {
            $package_str .= ',' . $package_addon;
        }
        $total_package_price = 0;
        $packages = $this->package_model->get_package_info($package_str);

        if (!empty($packages)) {
            foreach ($packages as $p) {
                $total = $p['price'] * $p['term'];
                $term_string = $p['term'] > 1 ? $p['term'] . " Months" : $p['term'] . " Month";
                if ($p['type'] == 'Basic') {
                    $description = "Renewal :" . $p['name'] . " (Term : " . $term_string . ")";
                    $log_arr['package'] = array(
                        'id' => $p['id'],
                        'name' => $p['name'],
                        'term' => $p['term'],
                        'price' => $p['price'],
                        'total_price' => $total,
                    );
                } else {
                    $addon_arr[] = array(
                        'id' => $p['id'],
                        'name' => $p['name'],
                        'term' => $p['term'],
                        'price' => $p['price'],
                        'total_price' => $total,
                    );
                }
                $total_package_price = $total_package_price + $total;
            }
            if (!empty($addon_arr)) {
                $log_arr['addon'] = $addon_arr;
            }
        }
        $data = array('total_price' => $total_package_price, 'log_arr' => $log_arr, 'description' => $description);
        return $data;
    }

}
