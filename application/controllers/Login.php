<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('providers_model'));
    }

    public function show_404() {
        $this->load->view('Templates/show_404');
    }

    public function logout() {
        delete_cookie('_remember_me', MY_DOMAIN_NAME);
        $this->session->sess_destroy();
        redirect(base_url());
    }

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */