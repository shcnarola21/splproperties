<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal IPN Class
// ------------------------------------------------------------------------
// If (and where) to log ipn to file
$config['paypal_lib_ipn_log_file'] = BASEPATH . 'logs/paypal_ipn.log';
$config['paypal_lib_ipn_log'] = TRUE;

// Where are the buttons located at 
$config['paypal_lib_button_path'] = 'buttons';

// What is the default currency?
$config['paypal_lib_currency_code'] = 'CAD';

$config['paypal_lib_sandbox_mode'] = FALSE;

$config['paypal_byer_email'] = "psrsolutionsinc@gmail.com";
//$config['paypal_api_username'] = "psrsolutionsinc-facilitator_api1.gmail.com";
//$config['paypal_api_password'] = "R5KBSMBC859TX5EU";
//$config['paypal_api_signature'] = "AFcWxV21C7fd0v3bYYYRCpSSRl31A5zTTewDVvyCw9Xe8kHKxZOH61ii";

$config['paypal_api_username'] = "psrsolutionsinc_api1.gmail.com";
$config['paypal_api_password'] = "WU6YYKFRELNMS285";
$config['paypal_api_signature'] = "AFcWxV21C7fd0v3bYYYRCpSSRl31AXiervbHvHb0FDOw48IxY6dQBjfV";

//$config['paypal_byer_email'] = "lp.narola-facilitator@narolainfotech.com";
//$config['paypal_api_username'] = "lp.narola-facilitator_api1.narolainfotech.com";
//$config['paypal_api_password'] = "1395220311";
//$config['paypal_api_signature'] = "AseOx.SbqlEOCTAqdOwcxjwwdMLJAVy5QinGLzlo47ufVR-VYd0TEPF3";
