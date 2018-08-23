<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Print array/string.
 * @param array $data - data which is going to be printed
 * @param boolean $is_die - if set to true then excecution will stop after print. 
 */
function pr($data, $is_die = false) {
    if (is_array($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }

    if ($is_die)
        die;
}

/**
 * Print last executed query
 * @param boolean $bool - if set to true then excecution will stop after print
 */
function qry($bool = false) {
    $CI = & get_instance();
    echo $CI->db->last_query();
    if ($bool)
        die;
}

/**
 * Return verfication code with check already exit or not for business user signup
 */
function verification_code() {
    $CI = & get_instance();
    $CI->load->model('cms_model');
    for ($i = 0; $i < 1; $i++) {
        $verification_string = 'abcdefghijk123' . time();
        $verification_code = str_shuffle($verification_string);
        $check_code = $CI->cms_model->get_all_details('users', array('password_verify' => $verification_code))->num_rows();
        if ($check_code > 0) {
            $i--;
        } else {
            return $verification_code;
        }
    }
}

function send_email($email_array = array(), $req_provider_id = "") {
    $CI = & get_instance();
    $CI->load->model('cms_model');
    $provider_id = $req_provider_id;
    if (empty($req_provider_id)) {
        $provider_id = my_provider_id();
    }
    $send_email_config = $CI->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();

    $CI->load->library('My_PHPMailer');
    $mail = new PHPMailer();
    $mail->ClearAddresses();
    $mail->ClearAttachments();
    $mail->clearAllRecipients();
    $mail->clearQueuedAddresses('to');

    $mail->IsSMTP();
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "ssl";
    $mail->SingleTo = true;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //$mail->SMTPDebug = 2; 
    $mail->Host = $send_email_config['smtp_host'];
    $mail->Port = $send_email_config['smtp_port'];
    $mail->Username = $send_email_config['smtp_username'];
    $mail->Password = $send_email_config['smtp_password'];

    if (isset($email_array['from']) && !empty($email_array['from'])) {
        $mail->SetFrom($email_array['from'], 'Splproperties');
    } else {
        $mail->SetFrom('noreply@ls3digital.ca', 'ls3digital');
    }

    if (isset($email_array['attachment']) && !empty($email_array['attachment'])) {
        if (is_array($email_array['attachment'])) {
            foreach ($email_array['attachment'] as $a) {
                $mail->AddAttachment($a);
            }
        }
    }

    $mail->Subject = $email_array['subject'];

    $client_message = trim($email_array['body_messages']);
    $mail->MsgHTML($client_message);
    if (is_array($email_array['to'])) {
        $email_array['to'] = array_unique($email_array['to']);
        foreach ($email_array['to'] as $to) {
            $mail->AddAddress($to);
        }
    } else {
        $mail->AddAddress($email_array['to']);
    }

    if (!$mail->Send()) {
        if (PHP_SAPI === 'cli') {
            return TRUE;
        } else {
            return $mail->ErrorInfo;
        }
    } else {
        return true;
    }
}

function parse_content($req_arr, $body) {
    if (strstr($body, '||customer_name||'))
        $body = str_replace('||customer_name||', ucfirst($req_arr['customer_name']), $body);

    if (strstr($body, '||customer_id||'))
        $body = str_replace('||customer_id||', $req_arr['customer_id'], $body);

    if (strstr($body, '||package_detail||'))
        $body = str_replace('||package_detail||', $req_arr['package_detail'], $body);

    if (strstr($body, '||user_name||'))
        $body = str_replace('||user_name||', $req_arr['name'], $body);

    return $body;
}

/**
 * This function is used to get all fired quiries
 * @param - $is_die - boolean(true/false)
 * @return --
 */
function get_all_queries($is_die = false) {
    $CI = & get_instance();
    echo "<pre>";
    print_r($CI->db->queries);
    echo "</pre>";
    if ($is_die)
        die;
}

function _create_salt() {
    $CI = & get_instance();
    $CI->load->helper('string');
    return sha1(random_string('alnum', 32));
}

function is_user_logged() {
    $CI = & get_instance();
    if ((!$CI->session->userdata('user_logged_in'))) {
        $CI->session->set_flashdata('error', 'Please login to continue!');
        redirect('/login', 'refresh');
        return true;
    }
}

function created_directory($path) {
    if (!is_dir($path)) {
        @mkdir($path, 0755, TRUE);
    }
    if (is_dir($path)) {
        return true;
    } else {
        return false;
    }
}

function check_filename($file_name) {
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $file_name)) {
        return false;
    } else {
        return true;
    }
}

function rename_filename($file_name) {
    return preg_replace('/[^A-Za-z0-9]/', '', $file_name); // Removes special chars.
}

function upload_image($image_name, $image_path, $width = "", $height = "", $allowed_ext = "", $file_name = "") {
    $CI = & get_instance();
    $extension = substr(strrchr($_FILES[$image_name]['name'], '.'), 1);
    $randname = bin2hex(openssl_random_pseudo_bytes(64)) . '.' . $extension;
    if ($file_name != '') {
        $randname = $file_name . '_' . date('his') . '.' . $extension;
    }
    //$randname = uniqid() . '.' . $extension;
    $config = array('upload_path' => $image_path,
        'max_size' => "5120KB",
        // 'max_height'      => "768",
        // 'max_width'       => "1024" ,
        'file_name' => $randname
    );
    if (!empty($allowed_ext)) {
        $config['allowed_types'] = $allowed_ext;
    } else {
        $config['allowed_types'] = "gif|jpg|png|jpeg";
    }
    if (!empty($width) && !empty($height)) {
        $config['max_width'] = $width;
        $config['max_height'] = $height;
    }
    #Load the upload library
    $CI->load->library('upload');
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($image_name)) {
        $img_data = $CI->upload->data();
        $imgname = $img_data['file_name'];
    } //if
    else {
        $imgname = '';
    }
    return $imgname;
}

function is_master_admin() {
    $CI = & get_instance();
    $user_data = $CI->session->userdata('session_info');
    //$parent = isset($user_data['parent']) ? $user_data['parent'] : '' ;
    $type = $user_data['type'];
    if ($type == 'Admin' && empty($user_data['is_provider_user'])) {
        return true;
    } else {
        return false;
    }
}

function is_provider() {
    $CI = & get_instance();
    $user_data = $CI->session->userdata('session_info');
    $type = $user_data['type'];
    $is_provider_user = isset($user_data['is_provider_user']) ? $user_data['is_provider_user'] : '';
    if (($type == 'Provider' || $type == 'provider') || ($type == 'Admin' && !empty($is_provider_user))) {
        return true;
    } else {
        return false;
    }
}

function is_provider_login() {
    $CI = & get_instance();
    $data = $CI->session->userdata('session_info');
    $is_provider = isset($data['is_provider']) ? $data['is_provider'] : '';
    if ($is_provider == 'Provider' || $is_provider == 'provider') {
        return true;
    } else {
        return false;
    }
}

function is_user_login() {
    $CI = & get_instance();
    $data = $CI->session->userdata('session_info');
    $is_user = isset($data['is_user']) ? $data['is_user'] : '';
    if (!empty($is_user)) {
        return true;
    } else {
        return false;
    }
}

function my_provider_id() {
    $CI = & get_instance();
    $user_data = $CI->session->userdata('session_info');
    $provider_id = $user_data['user_id'];
    if (($user_data['type'] == 'Admin') && (isset($user_data['is_provider']) && $user_data['is_provider'] == 'Provider')) {
        $provider_id = $user_data['parent'];
    }
    return $provider_id;
}

function is_rental_service_available() {
    $CI = & get_instance();
    $provider_id = my_provider_id();
    $CI->load->model('package_model');
    $service = $CI->package_model->is_rental_service_available($provider_id);
    if ($service > 0) {
        return true;
    }
    return false;
}

function get_validate_fields($page = "", $validate_fields = array()) {
    $tabs = array();
    if ($page == 'provider') {
        $tabs = array(
            'provider_info' => array('name', 'services[]'),
            'login_info' => array('email_id', 'password', 'confirm_password'),
        );
    } else if ($page == 'packages') {
        $tabs = array(
            'package_info' => array('name', 'price', 'term'),
            'service_info' => array('package_services[]'),
            'setupfee_info' => array('setup_fee_id'),
        );
    } else if ($page == 'customer') {
        $tabs = array(
            'customer_tab' => array('customer_name', 'email', 'country', 'timezone', 'billing_name', 'billing_email', 'billing_country'),
            'package_tab' => array('customer_package'),
        );
    }
    $error_tabs = array();
    if (!empty($validate_fields)) {
        foreach ($tabs as $tk => $tv) {
            foreach ($validate_fields as $v) {
                if (in_array($v, $tv) && !in_array($tk, $error_tabs)) {
                    $error_tabs[] = $tk;
                }
            }
        }
    }
    return !empty($error_tabs) ? $error_tabs : '';
}

function customer_states() {
    $states = array(
        'Alberta',
        'British Columbia',
        'Manitoba',
        'New Brunswick',
        'Newfoundland and Labrador',
        'Nova Scotia',
        'Ontario',
        'Prince Edward Island',
        'Quebec',
        'Saskatchewan',
        'Northwest Territories',
        'Nunavut',
        'Yukon',
    );
    return $states;
}

function customer_types() {
    $customer_type_arr = array(
        'residential' => 'Residential',
        'comercial' => 'Comercial',
        'hotel' => 'Hotel',
        'multi_unit' => 'Multi Unit'
    );
    return $customer_type_arr;
}

function get_countries() {
    $CI = get_instance();
    $CI->db->select('*');
    $CI->db->from('countries');
    $query = $CI->db->get();
    $result = $query->result();
    return $result;
}

function _encrypt($text) {
    $CI = & get_instance();
    $salt = $CI->config->item('pay_key_iptv');
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, pad_key($salt), $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function _decrypt($text) {
    $CI = & get_instance();
    $salt = $CI->config->item('pay_key_iptv');
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, pad_key($salt), base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function pad_key($key) {
    if (strlen($key) > 32)
        return false;
    $sizes = array(16, 24, 32);
    foreach ($sizes as $s) {
        while (strlen($key) < $s)
            $key = $key . "\0";
        if (strlen($key) == $s)
            break; // finish if the key matches a size
    }
    return $key;
}

function convert_enc_string($string) {
    if (empty($string)) {
        return '';
    }
    return _decrypt($string);
}

function get_customer_total_credit($customer_id, $only_general = false) {
    $CI = & get_instance();
    $CI->load->model('customers_model');
    return $CI->customers_model->get_customer_total_credit($customer_id, $only_general);
}

function deleteDirectory($dir) {
    $CI = & get_instance();
    if (!is_dir($dir) || is_link($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . "/" . $item, false)) {
            chmod($dir . "/" . $item, 0777);
            if (!deleteDirectory($dir . "/" . $item, false))
                return false;
        };
    }
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

function generate_pdf_invoice($invoice_id, $action = "view", $time_stamp = "", $return_res = true) {
    $CI = & get_instance();
    $CI->load->model(array('cms_model', 'template_model'));
    $data = array();
    if (!empty($time_stamp) && $action == "download") {
        $invoice_id = base64_decode($invoice_id);
    }
    if (!empty($invoice_id)) {
        $transaction_info = $CI->cms_model->get_all_details('transactions', array('id' => $invoice_id))->result_array();
        $customer_id = $transaction_info[0]['customer_id'];
        $customer = $CI->cms_model->get_all_details('customers', array('cid' => $customer_id))->result_array();
        $provider_id = $customer[0]['provider_id'];
        $data['customer'] = $customer;

        $property_id = $customer[0]['property_id'];
        if (!empty($property_id)) {
            $property_info = $CI->cms_model->get_all_details('properties', array('id' => $property_id))->row_array();
            $data['property_info'] = $property_info;
        }

        $data['transaction_info'] = $transaction_info[0];
        $invoice_created_date = date('F d, Y', strtotime($transaction_info[0]['created']));
        $invoice_data = array(
            'customer_name' => $customer[0]['name'],
            'customer_id' => $customer[0]['cid'],
            'invoice_created_date' => $invoice_created_date,
            'invoice_number' => $transaction_info[0]['id']
        );

        $is_exist_PDF_format = $CI->template_model->get_template_info_by_name($provider_id, 'PDF_Format_Invoice');
        if (!empty($is_exist_PDF_format) && $is_exist_PDF_format['is_enable'] == "1") {

            $template_image = $is_exist_PDF_format['image'];
            $template_id = $is_exist_PDF_format['id'];
            $template_header_info = parse_content_invoice($is_exist_PDF_format['header_box'], $invoice_data);
            $footer_box = parse_content_invoice($is_exist_PDF_format['footer_box'], $invoice_data);
            $t_img = $CI->config->item('uploads_path') . 'templates/' . $template_id . '/' . $template_image;
            $data['template_image'] = file_exists($t_img) ? $template_image : '';
            $data['template_header_info'] = $template_header_info;
            $data['footer_box'] = $footer_box;
            $data['templates_info'] = $is_exist_PDF_format;
        }

        $pdf_html_view = 'email_template/invoice_pdf';
        require_once(APPPATH . 'libraries/Pdf.php');
        $status_array = array('fail', 'paid', 'pending');
        if (isset($transaction_info[0]) && !empty($transaction_info) && in_array($transaction_info[0]['payment_status'], $status_array)) {
            $CI->pdf = new Pdf('utf-8', 'A4', '', '', '10', '10', '2', '18');
        } else {
            $CI->pdf = new Pdf();
        }

        $pdf_content_html = $CI->load->view($pdf_html_view, $data, true);
        $CI->pdf->useAdobeCJK = true;
        $CI->pdf->autoLangToFont = true;
        $CI->pdf->autoScriptToLang = true;
        $CI->pdf->showImageErrors = true;
        $CI->pdf->setAutoBottomMargin = 'stretch';
        $CI->pdf->WriteHTML($pdf_content_html);
        $pdf_name = $customer[0]['cid'] . '_' . date('YmdHis') . '.pdf';
        if ($action == "send") {
            $attachment_path = "";
            $folder_path = $CI->config->item('uploads_path') . '/invoice_pdf';
            if (!file_exists($folder_path)) {
                created_directory($folder_path);
            }

            $file_to_save = $CI->config->item('uploads_path') . '/invoice_pdf/' . $pdf_name;
            $CI->pdf->Output($file_to_save, "F");
            if (file_exists($file_to_save)) {
                $attachment_path = $CI->config->item('uploads_path') . '/invoice_pdf/' . $pdf_name;
            }

            $to = $customer[0]['email'];
            $additional_email = $customer[0]['additional_email'];
            $to_email[] = $to;
            if (!empty($additional_email)) {
                $a_email = explode(',', $additional_email);
                $to_email = array_merge($to_email, $a_email);
            }
            $payment_status = $transaction_info[0]['payment_status'];

            $template_name = "";
            if ($payment_status == "paid") {
                $template_name = "Invoice_Success";
            } elseif ($payment_status == "fail" || $payment_status == "pending") {
                $template_name = "Invoice_Fail";
            }
            $link_expiration_hour = $CI->config->item('link_expiration_hour');
            $end_time_hour = base64_encode(strtotime(date("Y-m-d H:i:s", strtotime('+' . $link_expiration_hour . ' hours'))));
            //$end_time_hour = base64_encode(strtotime(date("Y-m-d H:i:s", strtotime('+1 minutes'))));

            $enc_data = $attachment_path . '|' . $end_time_hour;
            $invoice_url = $CI->config->item('PDF_base_url') . 'uploads/generate_pdf_invoice.php?dl=' . base64_encode($enc_data);

            $invoice_data['invoice_url'] = $invoice_url;
            $emailConfigs = $CI->cms_model->get_all_details('send_email_settings', array('provider_id' => $provider_id))->row_array();

            $is_valid_email_config = true;
            if (empty($emailConfigs) && $action == "send") {
                $is_valid_email_config = false;
            }
            $template_exist = $CI->template_model->get_template($template_name, $provider_id);
            if (!empty($template_exist) && isset($template_exist[0]['is_enable']) && $template_exist[0]['is_enable'] == "1") {

                $body_messages = $CI->load->view('email_template/default_header.php', array(), true);
                $body_messages .= parse_content_invoice($template_exist[0]['html_content'], $invoice_data);
                $body_messages .= $CI->load->view('email_template/default_footer.php', array(), true);

                $email_array['from'] = $template_exist[0]['from_email'];
                $email_array['subject'] = $template_exist[0]['subject'];
                $email_array['body_messages'] = $body_messages;
                $email_array['attachment'] = array($attachment_path);
                $email_array['to'] = $to_email;
                print_r("PHP_SAPI :",PHP_SAPI);
                if (send_email($email_array, $provider_id)) {
                    if (PHP_SAPI !== 'cli' && $return_res) {
                        echo "1";
                    }
                }
            } else {
                if (PHP_SAPI !== 'cli' && $return_res) {
                    echo "2";
                }
            }
        } elseif ($action == "download") {
            if (!empty($time_stamp)) {
                $expiretime = base64_decode($time_stamp);
                $current_time = strtotime(date('Y-m-d H:i:s'));
                if ($current_time > $expiretime) {
                    $url = $CI->config->item('PDF_base_url') . 'expiration.php';
                    redirect($url);
                } else {
                    $CI->pdf->Output($pdf_name, "D");
                }
            } else {
                $CI->pdf->Output($pdf_name, "D");
            }
        } elseif ($action == "print") {
            $CI->pdf->SetJS('print();');
            $CI->pdf->Output($pdf_name, "I");
        } elseif ($action == "store") {
            $attachment_path = "";
            $file_to_save = $CI->config->item('uploads_path') . 'invoice_pdf/' . $pdf_name;
            $CI->pdf->Output($file_to_save, "F");
            if (file_exists($file_to_save)) {
                $attachment_path = $CI->config->item('uploads_path') . 'invoice_pdf/' . $pdf_name;
            }
            return $attachment_path;
        } else {
            $CI->pdf->Output($pdf_name, "I");
        }
    }
}

function parse_content_invoice($body, $invoice_data) {
    if (strstr($body, '||customer_name||'))
        $body = str_replace('||customer_name||', ucfirst($invoice_data['customer_name']), $body);

    if (strstr($body, '||customer_id||'))
        $body = str_replace('||customer_id||', ucfirst($invoice_data['customer_id']), $body);

    if (strstr($body, '||invoice_created_date||'))
        $body = str_replace('||invoice_created_date||', $invoice_data['invoice_created_date'], $body);

    if (strstr($body, '||invoice_number||'))
        $body = str_replace('||invoice_number||', $invoice_data['invoice_number'], $body);

    if (strstr($body, '||description||'))
        $body = str_replace('||description||', $invoice_data['description'], $body);

    if (strstr($body, '||price||'))
        $body = str_replace('||price||', $invoice_data['price'], $body);

    if (strstr($body, '||payment_type||'))
        $body = str_replace('||payment_type||', $invoice_data['payment_type'], $body);

    if (strstr($body, '||payment_status||'))
        $body = str_replace('||payment_status||', $invoice_data['payment_status'], $body);

    if (strstr($body, '||failture_reason||'))
        $body = str_replace('||failture_reason||', $invoice_data['failture_reason'], $body);

    return $body;
}

function parse_property_content($req_arr, $body) {
    if (strstr($body, '||address||'))
        $body = str_replace('||address||', ucfirst($req_arr['address']), $body);

    if (strstr($body, '||city||'))
        $body = str_replace('||city||', ucfirst($req_arr['city']), $body);

    if (strstr($body, '||state||'))
        $body = str_replace('||state||', ucfirst($req_arr['state']), $body);

    if (strstr($body, '||zip_code||'))
        $body = str_replace('||zip_code||', ucfirst($req_arr['zip_code']), $body);

    if (strstr($body, '||country||'))
        $body = str_replace('||country||', ucfirst($req_arr['country']), $body);

    return $body;
}

function phone_formatter($data) {
    if (preg_match('/(\d{0,3})(\d{0,3})(\d{0,4})$/', $data, $matches)) {
        $result = '(' . $matches[1] . ') ' . $matches[2] . ' - ' . $matches[3];
        return $result;
    } else {
        return $data;
    }
}
