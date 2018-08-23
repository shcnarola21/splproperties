<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('users_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Users';
        $this->template->load('default_front', 'users/index', $data);
    }
    public function agent_users() {
        $data['title'] = 'Users';
        $this->template->load('default_front', 'users/index', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Agent';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['user'] = $users = $this->cms_model->get_all_details('users', array('id' => $id))->row_array();
            $user_data = $this->session->userdata('session_info');
            $user_type = $user_data['type'];
            // if ($user_type != 'admin') {
            //     $user_id = $user_data['user_id'];
            //     if ($users['id'] != $user_id) {
            //         $this->session->set_flashdata('error', 'You are not authorised to access this page.');
            //         redirect('/dashboard');
            //     }
            // }

            if (!empty($users)) {
                $this->template->load('default_front', 'users/edit', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('users');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('users');
        }
    }

    public function add() 
    {
        $data['title'] = 'Add User';
        if($this->input->post())
        {
            $password = $this->input->post('password');
            $this->form_validation->set_rules('email_id', 'Email', 'trim|required|valid_email');

            if (!empty($password)) {
                $this->form_validation->set_rules("password", "password", "trim|matches[confirm_password]");
                $this->form_validation->set_rules("confirm_password", "confirm password", "trim|required");
            }
            if ($this->form_validation->run() == true) {
                $input = array(
                    'name' => htmlentities($this->input->post('name')),
                    'status' => htmlentities($this->input->post('status')),
                    'phone' => htmlentities($this->input->post('phone')),
                    'address' => htmlentities($this->input->post('address')),
                    'city' => htmlentities($this->input->post('city')),
                    'state' => htmlentities($this->input->post('state')),
                    'country' => htmlentities($this->input->post('country')),
                    'zip_code' => htmlentities($this->input->post('zip_code')),
                    'email' => htmlentities($this->input->post('email_id')),
                    'type' => htmlentities($this->input->post('type'))
                );
                if (!empty($password)) {
                    $salt = _create_salt();
                    $input['password'] = sha1($password . $salt);
                    $input['salt'] = $salt;
                }
                $user_data = $this->session->userdata('session_info');
               
                $user_type = $user_data['type'];
                $id = $user_data['user_id'];

                if (is_master_admin()) 
                {
                    // $parent = isset($user_data['parent']) ? $user_data['parent'] : '' ;
                    // if(!empty($parent))
                    // {
                    //     $input['parent'] = $user_data['parent']; 
                    // }else
                    // {
                        $input['parent'] = $user_data['user_id'];   
                    // }                        
                    $data = $this->cms_model->master_insert($input,'users');//insert user
                    echo '1^1';   
                }
                elseif(is_provider())
                {
                    $input['parent'] = $user_data['user_id'];
                    $input['is_provider_user'] = '1';
                    $data = $this->cms_model->master_insert($input,'users');//insert user
                    echo '1^1';
                }
                else
                {
                    echo '1^';
                }
            } 
            else 
            {
                echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
            }   
        }
        else
        {
            $this->template->load('default_front', 'users/add', $data);
        }
    }

    public function get() {
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];
        $login_user = $this->users_model->get_user_details($user_id);
        if($login_user['type'] == 'Basic')
        {
            $final['recordsTotal'] = $this->users_model->get_all_details('users',array('id' => $user_id))->num_rows();
            $final['redraw'] = 1;
            $final['recordsFiltered'] = $final['recordsTotal'];
            $devices = $this->users_model->get_all_details('users',array('id' => $user_id))->result_array();
            $start = $this->input->get('start') + 1;
            foreach ($devices as $key => $val) 
            {
                $devices[$key] = $val;
                $devices[$key]['sr_no'] = $start++;
                $devices[$key]['responsive'] = '';
                $devices[$key]['user_name'] = $val['name'];
            }
            $final['data'] = $devices;
        }
        if(is_master_admin() || is_provider())
        {
            $final['recordsTotal'] = $this->users_model->get_users('count');
            $final['redraw'] = 1;
            $final['recordsFiltered'] = $final['recordsTotal'];
            $devices = $this->users_model->get_users('result')->result_array();
            $start = $this->input->get('start') + 1;
            foreach ($devices as $key => $val) 
            {
                $devices[$key] = $val;
                $devices[$key]['sr_no'] = $start++;
                $devices[$key]['responsive'] = '';
            }
            $final['data'] = $devices;
        }
        echo json_encode($final);
    }

    public function save() {
        $id = base64_decode($this->input->post('id'));
        $password = $this->input->post('password');

        $this->form_validation->set_rules('email_id', 'Email', 'trim|required|valid_email');

        if (!empty($password)) {
            $this->form_validation->set_rules("password", "password", "trim|matches[confirm_password]");
            $this->form_validation->set_rules("confirm_password", "confirm password", "trim|required");
        }
        if ($this->form_validation->run() == true) {
            $input = array(
                'name' => htmlentities($this->input->post('name')),
                'status' => htmlentities($this->input->post('status')),
                'phone' => htmlentities($this->input->post('phone')),
                'address' => htmlentities($this->input->post('address')),
                'city' => htmlentities($this->input->post('city')),
                'state' => htmlentities($this->input->post('state')),
                'country' => htmlentities($this->input->post('country')),
                'zip_code' => htmlentities($this->input->post('zip_code')),
                'email' => htmlentities($this->input->post('email_id')),
                'type' => htmlentities($this->input->post('type'))
            );
            if (!empty($password)) {
                $salt = _create_salt();
                $input['password'] = sha1($password . $salt);
                $input['salt'] = $salt;
            }

            if(is_provider())
            {
                // if($input['type'] == 'Admin')
                // {
                //     $input['type'] = 'Provider';
                //     $this->cms_model->master_delete('users',array('id' => $id,'is_provider_user' => '1'));
                //     $data = $this->cms_model->master_insert($input,'provider');//insert user
                // }else
                // {
                    $this->cms_model->master_update('users', $input, array('id' => $id));
                // }
            }
            
            // $user_data = $this->session->userdata('session_info');
            // if (!is_master_admin()) {
            //     $data = $this->cms_model->get_all_details('providers', array('id' => $user_data['user_id']))->row_array();
            //     $ssn_data = array();
            //     $ssn_data['user_id'] = $data['id'];
            //     $ssn_data['username'] = $data['name'];
            //     $ssn_data['email'] = $data['email'];
            //     $ssn_data['type'] = $data['type'];
            //     $ssn_data['parent'] = (isset($data['parent'])) ? $data['parent'] : '';
            //     $ssn_data['is_provider_user'] = (isset($data['is_provider_user'])) ? $data['is_provider_user'] : '';
            //     if($data['type'] == 'Admin' || $data['type'] == 'admin')
            //     {
            //         $ssn_data['is_admin'] = true;
            //     }elseif($data['type'] == 'Provider' || $data['type'] == 'provider')
            //     {
            //         $ssn_data['is_provider'] = 'Provider';
            //     }else
            //     {
            //         $ssn_data['is_user'] = true;
            //     }
            //     $this->session->set_userdata('session_info', $ssn_data);
            // }
            if (is_master_admin() || is_provider()) {
                echo '1^1';                
            }else{
                echo '1^'; 
            }
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
    }

    public function autologin($id) {
        $id = base64_decode($id);
        if (!empty($id)) 
        {
            $master_data = $this->session->userdata('session_info');
           
            if (is_master_admin()) 
            {
                $this->session->set_userdata('master_id', $master_data['user_id']);
                $this->session->set_userdata('parent', $master_data['parent']);

                $this->session->set_userdata('user_id', $id);
                $user_info = $this->cms_model->get_all_details('providers', array('id' => $id))->row_array();
            }
            elseif(is_provider()) 
            {
                $this->session->unset_userdata('master_id');
                $this->session->unset_userdata('user_id', $master_data['user_id']); 
                $user_info = $this->cms_model->get_all_details('users', array('id' => $id))->row_array();
                $user_data['parent'] = (isset($user_info['parent'])) ? $user_info['parent'] : '';
                $user_data['is_provider_user'] = (isset($data['is_provider_user'])) ? $data['is_provider_user'] : '';
                if($user_info['type'] == 'Admin' || $user_info['type'] == 'admin')
                {
                    $user_data['is_admin'] = true;
                }
                elseif($user_info['type'] == 'Provider' || $user_info['type'] == 'provider')
                {
                    $user_data['is_provider'] = 'Provider';
                }else
                {
                    $user_data['is_user'] = true;
                }
            }
            $user_data['user_id'] = $user_info['id'];
            $user_data['username'] = $user_info['name'];
            $user_data['email'] = $user_info['email'];
            $user_data['type'] = $user_info['type'];
            
            $this->session->set_userdata('session_info', $user_data);
            $this->session->set_flashdata('success', 'You have successfully logged in.');
            redirect(site_url('/dashboard'));
        }
    }

    public function view($id)
    {
        $data['title'] = 'View User';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['user'] = $users = $this->cms_model->get_all_details('users', array('id' => $id))->row_array();
            $user_data = $this->session->userdata('session_info');
            $user_type = $user_data['type'];
            // if ($user_type != 'admin') {
            //     $user_id = $user_data['user_id'];
            //     if ($users['id'] != $user_id) {
            //         $this->session->set_flashdata('error', 'You are not authorised to access this page.');
            //         redirect('/dashboard');
            //     }
            // }

            if (!empty($users)) {
                $this->template->load('default_front', 'users/view', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('users');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('users');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Delete User';
        $id = base64_decode($id);
        if (!empty($id)) {
            $data['user'] = $users = $this->cms_model->get_all_details('users', array('id' => $id))->row_array();
            $user_data = $this->session->userdata('session_info');
            $user_type = $user_data['type'];
            if ($user_type == 'Basic') {
                $user_id = $user_data['user_id'];
                if ($users['id'] != $user_id) {
                    $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                    redirect('users');
                }
            }

            if (!empty($users)) 
            {
                $this->cms_model->master_delete('users',array('parent' => $id));
                $this->cms_model->master_delete('users',array('id' => $id));
                $this->session->set_flashdata('success', 'User deleted successfully.');
                 redirect('users');
                //$this->template->load('default_front', 'providers/view', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('users');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('providers');
        }
    }
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Devices.php */