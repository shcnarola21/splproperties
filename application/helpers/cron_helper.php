<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function auto_increase_package_price_now($config, $is_now) {
    $CI = & get_instance();
    $CI->load->model('customers_model');
    $CI->load->model('cms_model');
    if ($is_now) {
        $provider_id = $config['provider_id'];
        $percentage = $config['percentage'];
        if (!empty($provider_id)) {

            $send_email_config = $CI->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();
            $email_tempalate = $CI->cms_model->get_all_details('templates', array('provider_id' => $provider_id, 'name' => 'Auto_Increase_Package_Price', 'is_enable' => 1))->row_array();

            $sql = "SELECT p.*, (p.price + round(( (p.price * " . $percentage . ") /100 ),2)) as new_price, round(( (p.price * " . $percentage . ") /100 ),2) AS percentage ";
            $sql .= "FROM packages p ";
            $sql .= "JOIN services s ON FIND_IN_SET(s.id,p.services) > 0 AND s.service_name LIKE '%Rental%' WHERE provider_id = " . $provider_id;

            $query = $CI->db->query($sql);
            $packages = $query->result_array();
            $customer_detail = array();
            if (!empty($packages)) {
                $update_array = $update_package = $email_customer_arr = array();
                foreach ($packages as $p) {
                    $customer_ids = $CI->customers_model->get_package_addon_customers($p['id'], $p['type'], $p['provider_id']);
                    if (isset($customer_ids['cids']) && !empty($customer_ids['cids'])) {
                        $customers = explode(',', $customer_ids['cids']);
                        if (!empty($customers)) {
                            foreach ($customers as $c) {
                                $temp = array();
                                $temp[$c] = array(
                                    'package_id' => $p['id'],
                                    'name' => $p['name'],
                                    'percentage' => $percentage,
                                    'org_price' => $p['price'],
                                    'new_price' => $p['new_price'],
                                );

                                $customer_detail[$c] = array('customer_id' => isset($cus_email[0]) ? $cus_email[0] : '', 'customer_name' => isset($cus_email[2]) ? $cus_email[2] : '');

                                $email = explode(',', $customer_ids['emails']);
                                if (!empty($email)) {
                                    foreach ($email as $e) {
                                        $cus_email = explode('^', $e);
                                        $email_customer_arr[$c]['email'] = $cus_email[1];
                                    }
                                }

                                $email_customer_arr[$c]['packages'][] = $temp[$c];
                            }
                        }
                    }
                    $update_array[] = array(
                        'package_id' => $p['id'],
                        'package_name' => $p['name'],
                        'percentage' => $percentage,
                        'org_price' => $p['price'],
                        'new_price' => $p['new_price'],
                        'customer_ids' => (isset($customer_ids['cids']) && !empty($customer_ids['cids'])) ? $customer_ids['cids'] : ''
                    );
                    $update_package[] = array(
                        'id' => $p['id'],
                        'price' => $p['new_price']
                    );
                }

                //update package
                if (!empty($update_package)) {
                    $update_package = array_chunk($update_package, 100);
                    if (!empty($update_package)) {
                        foreach ($update_package as $u) {
                            $CI->cms_model->update_batch("packages", $u, 'id');
                        }
                    }
                }

                //insert in history table
                if (!empty($update_array)) {
                    $update_array = array_chunk($update_array, 100);
                    if (!empty($update_array)) {
                        foreach ($update_array as $p) {
                            $CI->cms_model->insert_batch('history_auto_increase_package_prices', $p);
                        }
                    }
                }

                //update provider fields
                $data = array(
                    'yearly_auto_increase_specific_time' => null,
                    'yearly_auto_increase_date' => null
                );
                $CI->cms_model->master_update('providers', $data, array('id' => $provider_id));

                //send email to customers
                if (!empty($email_customer_arr) && !empty($email_tempalate)) {
                    foreach ($email_customer_arr as $key => $value) {

                        $message = $CI->load->view('email_template/default_header.php', array(), true);
                        $email = $value['email'];
                        $packages = $value['packages'];

                        $message_body = $email_tempalate['html_content'];

                        $package_str = '';
                        if (!empty($packages)) {
                            $package_str = generate_string($value['packages']);
                        }

                        $email_arr = array(
                            'customer_id' => $customer_detail[$key]['customer_id'],
                            'customer_name' => $customer_detail[$key]['customer_name'],
                            'package_detail' => $package_str,
                        );

                        $message_body = parse_content($email_arr, $message_body);

                        $message .= $message_body;
                        $message .= $CI->load->view('email_template/default_footer.php', array(), true);

                        $email_array = array(
                            'to' => $email,
                            'from' => $email_tempalate['from_email'],
                            'subject' => 'Yearly Auto Increase Package Prices',
                            'body_messages' => $message
                        );
                        send_email($email_array, $provider_id);
                    }
                }
            }
        }
    }
}

function generate_string($data_arr) {
    $str = "<table class='table'>";
    $str .= "<tr>";
    $str .= "<th>Package </th>";
    $str .= "<th>Old Package</th>";
    $str .= "<th>Updated Price</th>";
    $str .= "</tr>";

    foreach ($data_arr as $d) {
        $str .= "<tr>";
        $str .= "<td>" . $d['name'] . "</td>";
        $str .= "<td>" . $d['org_price'] . "</td>";
        $str .= "<td>" . $d['new_price'] . "</td>";
        $str .= "</tr>";
    }
    $str .= "</table>";
    return $str;
}

function make_payment($provider_id, $customer_id, $price, $paypal_description) {
    $CI = & get_instance();
    $CI->load->model(array('cms_model','template_model'));    
    $CI->load->library('paypal_lib');

    $provider_info = $CI->cms_model->get_all_details('payment_system', array('provider_id' => $provider_id))->row_array();
    $customer_info = $CI->cms_model->get_all_details('customers', array('cid' => $customer_id))->row_array();

    $apikey = $provider_info['payment_system_user'];
    $apipass = $provider_info['payment_system_password'];
    $apisign = $provider_info['payment_system_signature'];

    if (!empty($apikey) && !empty($apipass) && !empty($apisign)) {
        $arr = explode(' ', $customer_info['name'], 2);

        if (count($arr) <= 1) {
            $lastname = array_pop($arr);
            $firsname = $lastname;
        } else {
            $lastname = $arr[1];
            $firsname = $arr[0];
        }
        $card_type = $customer_info['card_type'];
        $ccmonth = $customer_info['ccmonth'];
        $ccyear = $customer_info['ccyear'];
        $ccvc = convert_enc_string($customer_info['ccvc']);

        $paydetail = array(
            'AMT' => round($price, 2),
            'ACCT' => urlencode(convert_enc_string($customer_info['ccnumber'])),
            'CREDITCARDTYPE' => urlencode($card_type),
            'EXPDATE' => urlencode($ccmonth) . urlencode($ccyear),
            'CVV2' => urlencode($ccvc),
            'FIRSTNAME' => urlencode($firsname),
            'LASTNAME' => urlencode($lastname),
            'STREET' => urlencode($customer_info['billing_address']),
            'CITY' => urlencode($customer_info['billing_city']),
            'ZIP' => urlencode($customer_info['billing_zipcode']),
            'DESC' => isset($paypal_description) ? urlencode($paypal_description) : '',
            'COUNTRYCODE' => urlencode('US'),
        );

        $sessiondata = array(
            'ccname' => $customer_info['namecard'],
            'ccnumber' => convert_enc_string($customer_info['ccnumber']),
            'cctype' => $card_type,
            'ccmonth' => $ccmonth,
            'ccyear' => $ccyear,
            'ccvc' => _encrypt($ccvc),
            'payer_id' => $customer_id,
            'provider_id' => $provider_id,
            'transaction_amount' => $price,
            'description' => 'Customer Renewal',
            'transaction_date' => date('Y-m-d'),
        );

        $msg = "No Response from api.";
        $sessiondata['transaction_status'] = 'fail';
        $sessiondata['failture_reason'] = 'No Response from api.';
        $sessiondata['message'] = $msg;
        $invoice_id = $CI->cms_model->master_insert($sessiondata, "payment_logs");
        return json_encode(array('status' => 'fail', 'msg' => $msg, 'invoice_id' => $invoice_id));


        if (!empty($card_type) && !empty($ccmonth) && !empty($ccyear) && !empty($ccvc) && !empty($customer_info['ccnumber'])) {
            $response = $CI->paypal_lib->DirectCreditPaymentManual($paydetail, $apikey, $apipass, $apisign);

            if (!empty($response)) {
                if ($response['ACK'] == "Success") {
                    $sessiondata['transaction_status'] = 'success';
                    $sessiondata['transaction_id'] = $response['TRANSACTIONID'];
                    $sessiondata['AVSCODE'] = $response['AVSCODE'];
                    $invoice_id = $CI->cms_model->master_insert($sessiondata, "payment_logs");

                    $result = array('status' => 'success', 'transaction_id' => $response['TRANSACTIONID'], 'invoice_id' => $invoice_id);
                    return json_encode($result);
                } else {
                    $sessiondata['transaction_status'] = 'error';
                    $sessiondata['failture_reason'] = urldecode($response['L_SHORTMESSAGE0']);
                    $sessiondata['message'] = urldecode($response['L_LONGMESSAGE0']);
                    $result = $response['L_LONGMESSAGE0'];

                    $invoice_id = $CI->cms_model->master_insert($sessiondata, "payment_logs");
                    return json_encode(array('status' => 'error', 'msg' => $result, 'invoice_id' => $invoice_id));
                }
            } else {
                $msg = "No Response from api.";
                $sessiondata['transaction_status'] = 'fail';
                $sessiondata['failture_reason'] = 'No Response from api.';
                $sessiondata['message'] = $msg;
                $invoice_id = $CI->cms_model->master_insert($sessiondata, "payment_logs");
                return json_encode(array('status' => 'fail', 'msg' => $msg, 'invoice_id' => $invoice_id));
            }
        } else {
            $msg = "Creditcard detail cannot be empty.";
            $sessiondata['transaction_status'] = 'fail';
            $sessiondata['failture_reason'] = 'Invalid Creditcard Detail';
            $sessiondata['message'] = $msg;
            $invoice_id = $CI->cms_model->master_insert($sessiondata, "payment_logs");
            return json_encode(array('status' => 'fail', 'msg' => $msg, 'invoice_id' => $invoice_id));
        }
    }
}
