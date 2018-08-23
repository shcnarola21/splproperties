<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Providers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('providers_model');
        $this->load->model('template_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Providers';
        $this->template->load('default_front', 'providers/index', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Provider';
        $data['services'] = $this->providers_model->get_info(null, 'services');
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['provider'] = $provider = $this->cms_model->get_all_details('providers', array('id' => $id))->row_array();
            if (!empty($provider)) {
                $this->template->load('default_front', 'providers/add', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('providers');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('providers');
        }
    }

    public function add() {
        $data['title'] = 'Add Provider';
        $data['services'] = $users = $this->providers_model->get_info(null, 'services');
        $this->template->load('default_front', 'providers/add', $data);
    }

    public function get() {
        $user_data = $this->session->userdata('session_info');
        $final['recordsTotal'] = $this->providers_model->get_providers('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $devices = $this->providers_model->get_providers('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($devices as $key => $val) {
            $devices[$key] = $val;
            $devices[$key]['sr_no'] = $start++;
            $devices[$key]['responsive'] = '';
        }
        $final['data'] = $devices;
        echo json_encode($final);
    }

    public function save() {
        $id = "";
        $req_id = $this->input->post('id');
        if (!empty($req_id)) {
            $id = base64_decode($this->input->post('id'));
        }
        $password = $this->input->post('password');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if (!empty($id)) {
            $is_unique = '';
            $provider_email = $this->db->query("SELECT email FROM providers WHERE id = " . $id)->row()->email;
            if ($this->input->post('email_id') != $provider_email) {
                $is_unique = '|is_unique[providers.email]';
            }
        } else {
            $is_unique = '|is_unique[providers.email]';
        }

        $this->form_validation->set_rules('email_id', 'Email', 'trim|required|valid_email|is_unique[users.email]' . $is_unique);
        if (!empty($password)) {
            $this->form_validation->set_rules("password", "password", "trim|required");
            $this->form_validation->set_rules("confirm_password", "confirm password", "trim|required|matches[password]");
        }

        $this->form_validation->set_rules('services[]', 'Service', 'trim|required');

        if ($this->form_validation->run() == true) {
            $input = array(
                'name' => htmlentities($this->input->post('name')),
                'status' => htmlentities($this->input->post('status')),
                'services' => implode(",", $this->input->post('services')),
                'phone' => htmlentities($this->input->post('phone')),
                'address' => htmlentities($this->input->post('address')),
                'city' => htmlentities($this->input->post('city')),
                'state' => htmlentities($this->input->post('state')),
                'country' => htmlentities($this->input->post('country')),
                'zip_code' => htmlentities($this->input->post('zip_code')),
                'email' => htmlentities($this->input->post('email_id')),
            );

            if (!empty($password)) {
                $salt = _create_salt();
                $input['password'] = sha1($password . $salt);
                $input['salt'] = $salt;
            }

            if (empty($id)) {
                $id = $this->cms_model->master_insert($input, 'providers');
            } else {
                $this->cms_model->master_update('providers', $input, array('id' => $id));
            }
            $this->template_model->insert_default_templates($id, $this->input->post('email_id'));
            echo json_encode(array('status' => true));
        } else {
            $errors = array(
                'status' => false,
                'tabs' => get_validate_fields('provider', array_keys($this->form_validation->error_array())),
                'msg' => "<div class='alert alert-danger' style='margin-bottom:0px !important;'><button type='button' class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div><br>",
            );
            echo json_encode($errors);
        }
    }

    public function autologin($id) {
        $id = base64_decode($id);

        if (!empty($id)) {
            $master_data = $this->session->userdata('session_info');
            if ($master_data['type'] == "admin") {
                $this->session->set_userdata('master_id', $master_data['user_id']);
                $this->session->set_userdata('user_id', $id);
            } elseif ($master_data['type'] == "provider") {
                if ($master_data['is_user'] == "1") {
                    $this->session->unset_userdata('master_id');
                    $this->session->unset_userdata('user_id', $master_data['user_id']);
                }
            }

            $user_info = $this->cms_model->get_all_details('providers', array('id' => $id))->row_array();

            $user_data['user_id'] = $user_info['id'];
            $user_data['username'] = $user_info['name'];
            $user_data['email'] = $user_info['email'];
            $user_data['type'] = $user_info['type'];
            $user_data['parent'] = $user_info['parent'];

            if ($user_info['type'] == 'user') {
                $parent = $this->providers_model->get_provider_details($user_info['parent']);
                $user_data['type'] = $parent['type'];

                if ($user_data['type'] == 'admin') {
                    $user_data['is_admin'] = true;
                }
                if ($user_data['type'] == 'provider') {
                    $user_data['is_user'] = true;
                }
            } else {
                if ($user_info['type'] == 'admin') {
                    $user_data['is_admin'] = true;
                }
                if ($user_info['type'] == 'provider') {
                    $user_data['is_user'] = true;
                }
            }

            $this->session->set_userdata('session_info', $user_data);
            $this->session->set_flashdata('success', 'You have successfully logged in.');
            redirect(site_url('/dashboard'));
        }
    }

    public function view($id) {
        $data['title'] = 'View Provider';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['user'] = $users = $this->cms_model->get_all_details('providers', array('id' => $id))->row_array();
            $user_data = $this->session->userdata('session_info');
            $user_type = $user_data['type'];
            if (!is_master_admin()) {
                $user_id = $user_data['user_id'];
                if ($users['id'] != $user_id) {
                    $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                    redirect('/dashboard');
                }
            }

            if (!empty($users)) {
                $this->template->load('default_front', 'providers/view', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('providers');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('providers');
        }
    }

    public function delete($id) {
        $data['title'] = 'Delete Provider';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['user'] = $users = $this->cms_model->get_all_details('providers', array('id' => $id))->row_array();
            $user_data = $this->session->userdata('session_info');
            $user_type = $user_data['type'];
            if (!is_master_admin()) {
                $user_id = $user_data['user_id'];
                if ($users['id'] != $user_id) {
                    $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                    redirect('/dashboard');
                }
            }

            if (!empty($users)) {
                $this->cms_model->master_delete('users', array('parent' => $id, 'is_provider_user' => '1'));
                $this->cms_model->master_delete('providers', array('id' => $id));
                $this->cms_model->master_delete('templates', array('provider_id' => $id));
                $this->session->set_flashdata('success', 'Provider deleted successfully.');
                redirect('providers');
                //$this->template->load('default_front', 'providers/view', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('providers');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('providers');
        }
    }

}
