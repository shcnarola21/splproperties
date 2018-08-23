<?php

class Permissions {

    function permissions() {
        $CI = & get_instance();
        $user_data = $CI->session->userdata('session_info');
        $user_id = $CI->session->userdata('user_id');
        $user_type = $CI->session->userdata('type');
        $parent = my_provider_id();
        $is_master_admin = is_master_admin();
        $is_provider = is_provider();
        $is_rental_service_available = is_rental_service_available();
        $is_provider_login = is_provider_login();

        $allowed = 0;

        $segment = $CI->uri->segment(1);
        $segment1 = $CI->uri->segment(2);
        $segment2 = $CI->uri->segment(3);
        $segment4 = $CI->uri->segment(4);
        $segment5 = $CI->uri->segment(5);

        $controller = $CI->router->fetch_class();
        $action = $CI->router->fetch_method();
        $msg = "You are not authorised to access this page.";
//        $controllers = array('cron',
//            'providers',
//            'customers',
//            'packages',
//            'messaging',
//            'todolist',
//            'camera',
//            'keyless',
//            'users',
//            'services',
//            'properties',
//            'canned_responses',
//            'tax_setting',
//            'templates',
//            'send_email',
//            'payment_system'
//        );
        $allowed_action = array();
        $ignore_action = array('home', 'login', 'cron');
        
        if ($is_master_admin) {
            $allowed_action = array('providers', 'services');
        }
        if ($is_provider) {
            $allowed_action = array('customers', 'packages', 'messaging', 'payment_system', 'send_email', 'templates');
            if ($is_rental_service_available) {
                array_push($allowed_action, 'todolist');
                array_push($allowed_action, 'camera');
                array_push($allowed_action, 'keylessaccess');
                array_push($allowed_action, 'properties');
                array_push($allowed_action, 'canned_responses');
                array_push($allowed_action, 'tax_setting');
            }
        }

        array_push($allowed_action, 'users');
        array_push($allowed_action, 'dashboard');
        $path = $controller;
        
        if (!in_array($path, $ignore_action)) {
            if (!in_array($path, $allowed_action)) {
                if (empty($user_data)) {
                    pr("in here", 1);
                    redirect('login');
                } else {
                    $CI->session->set_flashdata('msg', $msg);
                    redirect('dashboard');
                }
            }
        } 
    }

}

?>
