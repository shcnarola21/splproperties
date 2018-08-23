<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('providers_model', 'cms_model', 'users_model'));
    }

    /**
     * Home Page
     * @param --
     * @return --
     * @author HDA [Last Edited : 04/07/2018]
     */
    public function index() {
        if ($this->session->userdata('user_logged_in')) {
            $data['user_data'] = $this->users_model->get_users_profile($this->session->userdata('u_user_id'));
        }

        $data['title'] = 'Home';
        $this->template->load('front', 'front/home', $data);
    }

    /**
     * Register new user
     * @param --
     * @return --
     * @author HDA [Last Edited : 04/07/2018]
     */
    public function register() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/'));
        }
        if ($this->input->post()) {
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('email_id', 'Email ID', 'trim|required|valid_email');
            $msg = "";
            if ($this->form_validation->run() == true) {
                $salt = _create_salt();
                $input = array(
                    'name' => htmlentities($this->input->post('first_name')) . ' ' . htmlentities($this->input->post('last_name')),
                    'email' => htmlentities($this->input->post('email_id')),
                    'phone' => htmlentities($this->input->post('contact_number')),
                    'type' => 'user',
                    'password' => sha1($this->input->post('password') . $salt),
                    'salt' => $salt,
                    'address' => htmlentities($this->input->post('address')),
                    'city' => htmlentities($this->input->post('city')),
                    'state' => htmlentities($this->input->post('state')),
                    'country' => htmlentities($this->input->post('country')),
                    'zip_code' => htmlentities($this->input->post('zip_code'))
                );

                $this->cms_model->master_insert($input, 'users');

                $msg = '<b>' . $first_name . ' ' . $last_name . '</b> has been registerd successfully.';

                $template = $this->cms_model->get_all_details('email_templates', array('email_for' => 'Registration'))->row_array();
                if ($template['is_enable']) {
                    $email_var = array(
                        'name' => $input['name']
                    );

                    $message = $this->load->view('email_template/default_header.php', $email_var, true);
                    $message .= parse_content($template['email_text'], $email_var); //$this->load->view('email_template/user_registration.php', $email_var, true);
                    $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                    $email_array = array(
                        'to' => $input['email'],
                        'subject' => $template['email_subject'],
                        'body_messages' => $message
                    );


                    if (!send_email($email_array)) {
                        $msg .= 'Something went wrong! We are not able to send you email.';
                    }
                }
            }
            $this->session->set_flashdata('success', $msg);
            redirect(site_url('/login'));
        }
        $data['title'] = 'Register';
        $this->template->load('front', 'login/register', $data);
    }

    /**
     * Login
     * @param --
     * @return --
     * @author HDA [Last Edited : 04/07/2018]
     */
    public function login() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/dashboard'));
        }
        $data['title'] = 'Login';
        if (!$this->session->userdata('user_logged_in')) {
            $remember = base64_decode(get_cookie('_user_remember_me', TRUE));
            if (!empty($remember) && $remember > 0) {
                $user_got = $this->users_model->get_user_details($remember);

                $cookie_ssn_data = array();
                $cookie_ssn_data['user_id'] = $user_got['id'];
                $cookie_ssn_data['username'] = $user_got['name'];
                $cookie_ssn_data['email'] = $user_got['email'];
                $cookie_ssn_data['type'] = $user_got['type'];
                $cookie_ssn_data['type'] = 'Provider';
                $this->session->set_userdata('session_info', $cookie_ssn_data);
                $this->session->set_userdata('user_logged_in', 1);
                redirect(site_url('/dashboard'));
            }
        }
        if ($this->input->post()) {
            $username = $this->input->post('txt_username');
            $password = $this->input->post('txt_password');

            $this->db->select('*');
            $this->db->from('users as u');
            $this->db->where('u.email', $username);
            $res = $this->db->get();
            $is_data = $res->row_array();
            if (!$is_data) {
                $this->db->select('*');
                $this->db->from('providers as p');
                $this->db->where('p.email', $username);
                $res = $this->db->get();
                $is_data = $res->row_array();
            }

            if (!empty($is_data)) {
                if (sha1($password . $is_data['salt']) == $is_data['password']) {

                    $data = $is_data;
                    if ($data['status'] == 'active') {
                        $ssn_data = array();
                        $ssn_data['user_id'] = $data['id'];
                        $ssn_data['username'] = $data['name'];
                        $ssn_data['email'] = $data['email'];
                        $ssn_data['type'] = $data['type'];
                        $ssn_data['parent'] = (isset($data['parent'])) ? $data['parent'] : '';
                        $ssn_data['is_provider_user'] = (isset($data['is_provider_user'])) ? $data['is_provider_user'] : '';
                        if (($data['type'] == 'Admin' || $data['type'] == 'admin') && empty($data['is_provider_user'])) {
                            $ssn_data['is_admin'] = true;
                        } elseif (($data['type'] == 'Admin' || $data['type'] == 'admin') && !empty($data['is_provider_user'])) {
                            $ssn_data['is_provider'] = 'Provider';
                        } elseif ($data['type'] == 'Provider' || $data['type'] == 'provider') {
                            $ssn_data['is_provider'] = 'Provider';
                        } else {
                            $ssn_data['is_user'] = true;
                        }

                        $this->session->set_userdata('session_info', $ssn_data);
                        $this->session->set_userdata('user_logged_in', 1);
                        if ($this->input->post('remember') && $this->input->post('remember') == 1) {
                            $CookieVal = array('name' => '_user_remember_me', 'value' => base64_encode($data['id']), 'expire' => 3600 * 24 * 30, 'domain' => MY_DOMAIN_NAME);
                            $this->input->set_cookie($CookieVal);
                        } else {
                            delete_cookie('_user_remember_me', MY_DOMAIN_NAME);
                        }
                        $this->session->set_flashdata('success', 'You have successfully logged in.');
                        redirect(site_url('/dashboard'));
                    } else {
                        $this->session->set_flashdata('error', 'Your account is InActive. Please contact to your administrator.');
                        redirect('/login');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Username or password did not match.');
                    redirect('/login');
                }
            } else {
                $this->session->set_flashdata('error', 'Username did not match!');
                redirect('/login');
            }
        }
        $this->template->load('front', 'login/login', $data);
    }

    /**
     * Logout
     * @param --
     * @return --
     * @author HDA [Last Edited : 04/07/2018]
     */
    public function logout() {
        delete_cookie('_user_remember_me', MY_DOMAIN_NAME);
        $this->session->sess_destroy();
        redirect('/');
    }

    /**
     * Forgot Password
     * @param --
     * @return --
     * @author HPA [Last Edited : 02/06/2018]
     */
    public function forgot_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect(site_url('/dashboard'));
        }

        $data['title'] = 'Forgot Password';
        if ($this->input->post()) {
            $email_user = $this->input->post('txt_email');
            $found = $this->users_model->find_user_by_email($email_user);
            if ($found['user_id'] > 0) {
                $verification_code = verification_code();
                $email_var = array(
                    'name' => $found['name'],
                    'user_id' => $found['user_id'],
                    'verification_code' => $verification_code
                );
                $message = $this->load->view('email_template/default_header.php', $email_var, true);
                $message .= $this->load->view('email_template/user_forgot_password.php', $email_var, true);
                $message .= $this->load->view('email_template/default_footer.php', $email_var, true);

                $email_array = array(
                    'to' => $email_user,
                    'subject' => 'Password Reset',
                    'body_messages' => $message
                );

                if (send_email($email_array)) {
                    $this->cms_model->master_update('users', array('password_verify' => $verification_code), array('id' => $found['user_id']));
                    $this->session->set_flashdata('success', 'Please check your email to reset your password.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! We are not able to send you email for reset password. Please try again later.');
                }
            } else {
                $this->session->set_flashdata('error', 'Sorry you are not able to reset this password. Please contact your system administrator.');
            }
            redirect('/login');
        }
        $this->template->load('front', 'login/forgot_password', $data);
    }

    public function reset_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('/dashboard', 'refresh');
        }

        $data['title'] = 'Reset Password';
        $user_id = base64_decode($this->input->get('q'));
        $password_verify = $this->input->get('code');
        $det = array();
        if ($password_verify != '') {
            $det = $this->users_model->get_user_details($user_id, $password_verify);
        }
        if ($det) {
            if ($this->input->post()) {
                if ($this->input->post('txt_password') == $this->input->post('txt_c_password')) {
                    $password = $this->input->post('txt_password');
                    $salt = _create_salt();
                    $encrypted_pass = sha1($this->input->post('txt_password') . $salt);

                    $update_pwd = $this->cms_model->insert_update('update', 'users', array('password' => $encrypted_pass, 'salt' => $salt, 'password_verify' => NULL), array('id' => $user_id));
                    if ($update_pwd == 1) {
                        $this->session->set_flashdata('success', 'You have successfully reset the password.');
                    } else {
                        $this->session->set_flashdata('error', 'Something went wrong! Password was not reset. Please try after some time.');
                    }
                    redirect('/login');
                } else {
                    $this->session->set_flashdata('error', 'Password doesn\'t match! Plese try again.');
                    redirect('reset_password?q=' . base64_encode($user_id) . '&code=' . $password_verify);
                }
            }
            $this->template->load('front', 'login/reset_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Invalid user!');
            redirect('/login');
        }
    }

    /**
     * It will check email is unique or not for staff
     * @param  : $id String
     * @return : Boolean (true/false)
     * @author HDA [Last Edited : 04/07/2018]
     */
    public function checkUnique_Email($id = NULL) {
        $email_id = trim($this->input->get_post('email_id'));
        $data = array('email' => $email_id);
        if (!is_null($id)) {
            $data = array_merge($data, array('id!=' => $id));
        }
        $user = $this->providers_model->check_unique_email_for_provider($data);
        if ($user > 0) {
            echo "false";
        } else {
            echo "true";
        }
        exit;
    }

    public function payment_paid() {
        $invoice_id = base64_decode($this->input->post('id'));
        $data = array(
            'payment_status' => 'paid'
        );
        $condition = array(
            'id' => $invoice_id
        );
        $this->cms_model->master_update("transactions", $data, $condition);
        echo '1';
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */