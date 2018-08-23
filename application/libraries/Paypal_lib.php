<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Code Igniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, pMachine, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * This CI library is based on the Paypal PHP class by Micah Carrick
 * See www.micahcarrick.com for the most recent version of this class
 * along with any applicable sample files and other documentaion.
 *
 * This file provides a neat and simple method to interface with paypal and
 * The paypal Instant Payment Notification (IPN) interface.  This file is
 * NOT intended to make the paypal integration "plug 'n' play". It still
 * requires the developer (that should be you) to understand the paypal
 * process and know the variables you want/need to pass to paypal to
 * achieve what you want.  
 *
 * This class handles the submission of an order to paypal as well as the
 * processing an Instant Payment Notification.
 * This class enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * The class requires the use of the PayPal_Lib config file.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */
// ------------------------------------------------------------------------

class paypal_lib {

    var $last_error;   // holds the last error encountered
    var $ipn_log;    // bool: log IPN results to text file?
    var $ipn_log_file;   // filename of the IPN log
    var $ipn_response;   // holds the IPN response from paypal	
    var $ipn_data = array(); // array contains the POST values for IPN
    var $fields = array();  // array holds the fields to submit to paypal
    var $submit_btn = '';  // Image/Form button
    var $button_path = '';  // The path of the buttons
    var $CI;

    function paypal_lib() {
        $this->CI = & get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->helper('form');
        $this->CI->load->config('paypallib_config');

        //

        if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
            $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        } else {
            $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        }


        $this->last_error = '';
        $this->ipn_response = '';

        $this->ipn_log_file = $this->CI->config->item('paypal_lib_ipn_log_file');
        $this->ipn_log = $this->CI->config->item('paypal_lib_ipn_log');

        $this->button_path = $this->CI->config->item('paypal_lib_button_path');

        // populate $fields array with a few default values.  See the paypal
        // documentation for a list of fields and their data types. These defaul
        // values can be overwritten by the calling script.
//        $this->add_field('rm', '2');     // Return method = POST
//        $this->add_field('cmd', '_xclick');
//
//
//        $this->add_field('business', $this->CI->config->item('paypal_byer_email'));
//        $this->add_field('currency_code', $this->CI->config->item('paypal_lib_currency_code'));

        $this->button('Pay Now!');
    }

    function button($value) {
        // changes the default caption of the submit button
        $this->submit_btn = form_submit('pp_submit', $value);
    }

    function image($file) {
        $this->submit_btn = '<input type="image" name="add" src="' . site_url($this->button_path . '/' . $file) . '" border="0" />';
    }

    function add_field($field, $value) {
        // adds a key=>value pair to the fields array, which is what will be 
        // sent to paypal as POST variables.  If the value is already in the 
        // array, it will be overwritten.
        $this->fields[$field] = $value;
    }

    function paypal_auto_form() {
        // this function actually generates an entire HTML page consisting of
        // a form with hidden elements which is submitted to paypal via the 
        // BODY element's onLoad attribute.  We do this so that you can validate
        // any POST vars from you custom form before submitting to paypal.  So 
        // basically, you'll have your own form which is submitted to your script
        // to validate the data, which in turn calls this function to create
        // another hidden form and submit to paypal.

        $this->button('Click here if you\'re not automatically redirected...');

        echo '<html>' . "\n";
        echo '<head><title>Processing Payment...</title></head>' . "\n";
        echo '<body style="text-align:center;" onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
        echo '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the paypal website.</p>' . "\n";
        echo $this->paypal_form('paypal_auto_form');
        echo '</body></html>';
    }

    function paypal_form($form_name = 'paypal_form') {
        $str = '';
        $str .= '<form method="post" action="' . $this->paypal_url . '" name="' . $form_name . '"/>' . "\n";
        foreach ($this->fields as $name => $value)
            $str .= form_hidden($name, $value) . "\n";
        $str .= '<p>' . $this->submit_btn . '</p>';
        $str .= form_close() . "\n";

        return $str;
    }

    function validate_ipn() {
        // parse the paypal URL
        $url_parsed = parse_url($this->paypal_url);

        // generate the post string from the _POST vars aswell as load the
        // _POST vars into an arry so we can play with them from the calling
        // script.
        $post_string = '';
        if ($this->CI->input->post()) {
            foreach ($this->CI->input->post() as $field => $value) {
                $this->ipn_data[$field] = $value;
                $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
            }
        }

        $post_string.="cmd=_notify-validate"; // append ipn command
        // open the connection to paypal
        $fp = fsockopen($url_parsed['host'], "80", $err_num, $err_str, 30);
        if (!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            $this->last_error = "fsockopen error no. $errnum: $errstr";
            $this->log_ipn_results(false);
            return false;
        } else {
            // Post the data back to paypal
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
            fputs($fp, "Host: $url_parsed[host]\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");

            // loop through the response from the server and append to variable
            while (!feof($fp))
                $this->ipn_response .= fgets($fp, 1024);

            fclose($fp); // close connection
        }

        if (preg_match("/VERIFIED/", $this->ipn_response)) {
            // Valid IPN transaction.
            $this->log_ipn_results(true);
            return true;
        } else {
            // Invalid IPN transaction.  Check the log for details.
            $this->last_error = 'IPN Validation Failed.';
            $this->log_ipn_results(false);
            return false;
        }
    }

    function log_ipn_results($success) {
        if (!$this->ipn_log)
            return;  // is logging turned off?
        // Timestamp
        $text = '[' . date('m/d/Y g:i A') . '] - ';

        // Success or failure being logged?
        if ($success)
            $text .= "SUCCESS!\n";
        else
            $text .= 'FAIL: ' . $this->last_error . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from Paypal:\n";
        foreach ($this->ipn_data as $key => $value)
            $text .= "$key=$value, ";

        // Log the response from the paypal server
        $text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;

        // Write to log
        $fp = fopen($this->ipn_log_file, 'a');
        fwrite($fp, $text . "\n\n");

        fclose($fp);  // close file
    }

    function dump() {
        // Used for debugging, this function will output all the field/value pairs
        // that are currently defined in the instance of the class using the
        // add_field() function.

        ksort($this->fields);
        echo '<h2>ppal->dump() Output:</h2>' . "\n";
        echo '<code style="font: 12px Monaco, \'Courier New\', Verdana, Sans-serif;  background: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px;">' . "\n";
        foreach ($this->fields as $key => $value)
            echo '<strong>' . $key . '</strong>:	' . urldecode($value) . '<br/>';
        echo "</code>\n";
    }

    function curlPost($paypalurl, $paypalreturnarr) {

        $req = 'cmd=_notify-validate';
        foreach ($paypalreturnarr as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        $ipnsiteurl = $paypalurl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ipnsiteurl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    function paypalPayment($methodName_, $PayPalReturnURL, $PayPalCancelURL, $TotalTaxAmount = 0, $ShippinCost = 0, $HandalingCost = 0, $ShippinDiscount = 0, $InsuranceCost = 0) {


        $curcode = ($this->CI->config->item('paypal_lib_currency_code') ? $this->CI->config->item('paypal_lib_currency_code') : 'CAD');

        //$payemtquery = "";
        $payemtquery = '&L_PAYMENTREQUEST_0_NAME0=' . urlencode($this->fields['item_name'])
                . '&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($this->fields['item_number'])
                . '&L_PAYMENTREQUEST_0_DESC0=' . urlencode($this->fields['item_desc'])
                . '&L_PAYMENTREQUEST_0_AMT0=' . urlencode($this->fields['item_amount'])
                . '&PAYMENTREQUEST_0_ITEMAMT0=' . urlencode(($this->fields['item_amount'] * $this->fields['item_qty']))
                . '&L_PAYMENTREQUEST_0_QTY0=' . urlencode($this->fields['item_qty']);
        $_SESSION['paypaldata']['item_name'] = $this->fields['item_name'];
        $_SESSION['paypaldata']['item_qty'] = $this->fields['item_qty'];
        $_SESSION['paypaldata']['item_number'] = $this->fields['item_number'];
        $_SESSION['paypaldata']['item_desc'] = $this->fields['item_desc'];
        $_SESSION['paypaldata']['item_amount'] = $this->fields['item_amount'];

        $grandTotal = $this->fields['item_amount'] * $this->fields['item_qty'];

        $nvpStr_ = '&RETURNURL=' . urlencode($PayPalReturnURL) .
                '&CANCELURL=' . urlencode($PayPalCancelURL) .
                '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") . $payemtquery .
                '&NOSHIPPING=0' .
                '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($TotalTaxAmount) .
                '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($ShippinCost) .
                '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode($HandalingCost) .
                '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode($ShippinDiscount) .
                '&PAYMENTREQUEST_0_INSURANCEAMT=' . urlencode($InsuranceCost) .
                '&PAYMENTREQUEST_0_AMT=' . urlencode($grandTotal) .
                '&PAYMENTREQUEST_0_CURRENCYCODE=' . $curcode .
                '&LOCALECODE=GB' .
                '&CARTBORDERCOLOR=FFFFFF' .
                '&ALLOWNOTE=1';
        $API_UserName = urlencode($this->CI->config->item('paypal_api_username'));
        $API_Password = urlencode($this->CI->config->item('paypal_api_password'));
        $API_Signature = urlencode($this->CI->config->item('paypal_api_signature'));

        if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
            $paypalmode = '.sandbox';
        } else {
            $paypalmode = '';
        }


        echo $API_Endpoint = "https://api-3t" . $paypalmode . ".paypal.com/nvp";
        $version = urlencode('109.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

            //Redirect user to PayPal store with Token received.
            $paypalurl = 'https://www' . $paypalmode . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $httpParsedResponseAr["TOKEN"] . '';
            header('Location: ' . $paypalurl);
        } else {
            //Show error message
        }
    }

    function DirectCreditPayment($nvpStr,$API_UserName,$API_Password,$API_Signature) {
        $nvpStr_ = "";
        foreach ($nvpStr as $key => $val) {
            $nvpStr_ .= "&" . $key . "=" . $val;
        }
        // Set up your API credentials, PayPal end point, and API version.
//        $API_UserName = urlencode($this->CI->config->item('paypal_api_username'));
//        $API_Password = urlencode($this->CI->config->item('paypal_api_password'));
//        $API_Signature = urlencode($this->CI->config->item('paypal_api_signature'));

        if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
            $paypalmode = '.sandbox';
        } else {
            $paypalmode = '';
        }
        $curcode = ($this->CI->config->item('paypal_lib_currency_code') ? $this->CI->config->item('paypal_lib_currency_code') : 'USD');


        $API_Endpoint = "https://api-3t" . $paypalmode . ".paypal.com/nvp";
        $version = urlencode('109.0');
        $add = urlencode('127.0.0.1');


        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=DoDirectPayment&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&IPADDRESS=$add&PAYMENTACTION=Sale&CURRENCYCODE=$curcode$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }

    function refundPayment($TransID, $REFUNDTYPE = "Full", $amount = 1) {

        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode($this->CI->config->item('paypal_api_username'));
        $API_Password = urlencode($this->CI->config->item('paypal_api_password'));
        $API_Signature = urlencode($this->CI->config->item('paypal_api_signature'));

        if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
            $paypalmode = '.sandbox';
        } else {
            $paypalmode = '';
        }
        $curcode = ($this->CI->config->item('paypal_lib_currency_code') ? $this->CI->config->item('paypal_lib_currency_code') : 'USD');


        $API_Endpoint = "https://api-3t" . $paypalmode . ".paypal.com/nvp";
        $version = urlencode('109.0');
        $nvpStr_ = "&PAYERID=" . $TransID;
        if ($REFUNDTYPE == "Full") {
            $nvpStr_ .= "&REFUNDTYPE=Full&AMT=.01";
        } else {
            $nvpStr_ .= "&REFUNDTYPE=Partial&AMT=" . $amount;
        }

        echo $nvpStr_;
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=RefundTransaction&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&CURRENCYCODE=$curcode$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }

    public function doPayment($token, $payerid) {
        //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.

        $data = $_SESSION['paypaldata'];
        if ($data) {
            $payemtquery = '&L_PAYMENTREQUEST_0_NAME0=' . urlencode($data['item_name'])
                    . '&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($data['item_number'])
                    . '&L_PAYMENTREQUEST_0_DESC0=' . urlencode($data['item_desc'])
                    . '&L_PAYMENTREQUEST_0_AMT0=' . urlencode($data['item_amount'])
                    . '&PAYMENTREQUEST_0_ITEMAMT0=' . urlencode(($data['item_amount'] * $data['item_qty']))
                    . '&L_PAYMENTREQUEST_0_QTY0=' . urlencode($data['item_qty']);

            $grandTotal = $data['item_amount'] * $data['item_qty'];


            $nvpStr_ = '&TOKEN=' . urlencode($token) .
                    '&PAYERID=' . urlencode($payerid) .
                    '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
                    //set item info here, otherwise we won't see product details later	
                    $payemtquery .
                    '&PAYMENTREQUEST_0_TAXAMT=' . urlencode($TotalTaxAmount) .
                    '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($ShippinCost) .
                    '&PAYMENTREQUEST_0_HANDLINGAMT=' . urlencode($HandalingCost) .
                    '&PAYMENTREQUEST_0_SHIPDISCAMT=' . urlencode($ShippinDiscount) .
                    '&PAYMENTREQUEST_0_INSURANCEAMT=' . urlencode($InsuranceCost) .
                    '&PAYMENTREQUEST_0_AMT=' . urlencode($grandTotal) .
                    '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($curcode);

            $API_UserName = urlencode($this->CI->config->item('paypal_api_username'));
            $API_Password = urlencode($this->CI->config->item('paypal_api_password'));
            $API_Signature = urlencode($this->CI->config->item('paypal_api_signature'));

            if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
                $paypalmode = '.sandbox';
            } else {
                $paypalmode = '';
            }


            echo $API_Endpoint = "https://api-3t" . $paypalmode . ".paypal.com/nvp";
            $version = urlencode('109.0');

            // Set the curl parameters.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);

            // Turn off the server and peer verification (TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            
            // Set the API operation, version, and API signature in the request.
            $nvpreq = "METHOD=DoExpressCheckoutPayment&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
            // Set the request as a POST FIELD for curl.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

            // Get response from the server.
            $httpParsedResponseAr = curl_exec($ch);
            print_r($httpParsedResponseAr);
            exit;
            if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

                echo '<h2>Success</h2>';
                echo 'Your Transaction ID : ' . urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

                /*
                  //Sometimes Payment are kept pending even when transaction is complete.
                  //hence we need to notify user about it and ask him manually approve the transiction
                 */

                if ('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                    echo '<div style="color:green">Payment Received!</div>';
                } elseif ('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
                    echo '<div style="color:red">Transaction Complete, but payment is still pending! ' .
                    'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
                }
            } else {
                echo '<div style="color:red"><b>GetTransactionDetails failed:</b>' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
                echo '<pre>';
                print_r($httpParsedResponseAr);
                echo '</pre>';
            }
        }
    }
    
     function DirectCreditPaymentManual($nvpStr,$API_UserName,$API_Password,$API_Signature) {
        $nvpStr_ = "";
        foreach ($nvpStr as $key => $val) {
            $nvpStr_ .= "&" . $key . "=" . $val;
        }
        // Set up your API credentials, PayPal end point, and API version.
//        $API_UserName = urlencode($this->CI->config->item('paypal_api_username'));
//        $API_Password = urlencode($this->CI->config->item('paypal_api_password'));
//        $API_Signature = urlencode($this->CI->config->item('paypal_api_signature'));

        if ($this->CI->config->item('paypal_lib_sandbox_mode')) {
            $paypalmode = '.sandbox';
        } else {
            $paypalmode = '';
        }
        $curcode = ($this->CI->config->item('paypal_lib_currency_code') ? $this->CI->config->item('paypal_lib_currency_code') : 'USD');


        $API_Endpoint = "https://api-3t" . $paypalmode . ".paypal.com/nvp";
        $version = urlencode('109.0');
        $add = urlencode('127.0.0.1');


        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=DoDirectPayment&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&IPADDRESS=$add&PAYMENTACTION=Sale&CURRENCYCODE=$curcode$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }

        return $httpParsedResponseAr;
    }
//unset($_SESSION['paypaldata']);
}

?>
