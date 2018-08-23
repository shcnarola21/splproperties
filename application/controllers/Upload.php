<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_user_logged();
        $this->load->model('cms_model');
        $this->load->model('upload_model');
        $this->load->model('device_model');
    }

    public function index() {         
        $data['title'] = 'Upload Picture';
        $this->template->load('default_front', 'upload/index', $data);        
    }

    public function view($folder_id) {
        $folder_id = base64_decode($folder_id);
        $data['title'] = 'Upload Picture';
        $data['folder'] = $folder_data = $this->cms_model->get_all_details('folders', array('id' => $folder_id))->row_array();
        $user_data = $this->session->userdata('session_info');
        $user_type = $user_data['type'];
        if ($user_type != 'admin') {
            $user_id = $user_data['user_id'];
            if ($folder_data['user_id'] != $user_id) {
                $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                redirect('/dashboard');
            }
        }

        if (!empty($folder_data)) {
            $data['folder_images'] = $this->cms_model->get_all_details('folder_images', array('folder_id' => $folder_id))->result_array();
            $data['folder_devices'] = $this->device_model->get_assigned_devices($folder_id)->result_array();
            $this->template->load('default_front', 'upload/view', $data);
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('upload');
        }
    }

    public function get() {
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];

        $final['recordsTotal'] = $this->upload_model->get_folders('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $folders = $this->upload_model->get_folders('result')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($folders as $key => $val) {
            $folders[$key] = $val;
            $folders[$key]['sr_no'] = $start++;
            $folders[$key]['responsive'] = '';
        }
        $final['data'] = $folders;
        echo json_encode($final);
    }

    public function add() {
        $data['title'] = 'Add Folder & Upload Images';
        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $data['devices'] = $this->cms_model->get_all_details('metatable', array('user_id' => $user_id))->result_array();
        $this->template->load('default_front', 'upload/add', $data);
    }

    public function edit($id) {
        $id = base64_decode($id);
        $user_data = $this->session->userdata('session_info');
        if (!empty($id)) {
            $data['folder'] = $folder_data = $this->cms_model->get_all_details('folders', array('id' => $id))->row_array();

            $user_type = $this->session->userdata('type');
            if ($user_type != 'admin') {
                $user_id = $user_data['user_id'];
                if ($folder_data['user_id'] != $user_id) {
                    $this->session->set_flashdata('error', 'You are not authorised to access this page.');
                    redirect('/dashboard');
                }
            }

            if (!empty($folder_data)) {
                $user_id = $folder_data['user_id'];
                $data['title'] = 'Edit Folder & Upload Images';
                $data['devices'] = $this->cms_model->get_all_details('metatable', array('user_id' => $user_id))->result_array();
                $data['folder_images'] = $this->cms_model->get_all_details('folder_images', array('folder_id' => $folder_data['id']))->result_array();
                $data['folder_devices'] = $this->cms_model->get_all_details('device_folder', array('user_id' => $user_id, 'folder_id' => $folder_data['id']))->result_array();
                $this->template->load('default_front', 'upload/add', $data);
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('upload');
            }
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('upload');
        }
    }

    public function save() {       
        $this->form_validation->set_rules("folder_name", "Folder Name", "trim|required");
        if (empty($_FILES['upload_imgs']['name'])) {
            $this->form_validation->set_rules("upload_imgs", "Images", "trim|required");
        }

        if (($this->form_validation->run() == TRUE)) {
            $folder_id = $this->input->post('folder_id');
            $folder_data = $this->cms_model->get_all_details('folders', array('id' => $folder_id))->row_array();
            $device_ids = $this->input->post('device_id');
            $user_id = '';
            if (empty($folder_id)) {
                $user_data = $this->session->userdata('session_info');
                $user_id = $user_data['user_id'];
            } else {
                $user_id = $folder_data['user_id'];
            }
            $input = array(
                'name' => $this->input->post('folder_name'),
                'user_id' => $user_id
            );

            if (empty($folder_id)) {
                $folder_id = $this->cms_model->master_insert($input, "folders");
            } else {
                $this->cms_model->master_update("folders", $input, array('id' => $folder_id));
            }

            if (!empty($folder_id)) {
                $this->cms_model->master_delete("device_folder", array('folder_id' => $folder_id, 'user_id' => $user_id));
                if (!empty($device_ids)) {
                    $insert_device = array();
                    foreach ($device_ids as $dv) {
                        $insert_device[] = array(
                            'device_id' => $dv,
                            'folder_id' => $folder_id,
                            'user_id' => $user_id,
                        );
                    }
                    if (!empty($insert_device)) {
                        $this->cms_model->insert_batch('device_folder', $insert_device);
                    }
                }
            }
            $errors = $folder_error = '';
            $folder_exist = true;
            $folder_path = $this->config->item('uploads_path') . '/' . $user_id . '/' . $folder_id;
            if (!file_exists($folder_path)) {
                if (!created_directory($folder_path)) {
                    $folder_exist = false;
                    $folder_error = 'Folder could not created.';
                }
            }

            if (file_exists($folder_path)) {
                $folder_images = array();
                $folder_path = $this->config->item('uploads_path') . '/' . $user_id . '/' . $folder_id;
                if (!empty($_FILES)) {
                    $count = count($_FILES['upload_imgs']['name']);
                    for ($i = 0; $i <= $count - 1; $i++) {
                        if ($_FILES['upload_imgs']['size'][$i]) {
                            $_FILES['upload_img']['name'] = $_FILES['upload_imgs']['name'][$i];
                            $_FILES['upload_img']['type'] = $_FILES['upload_imgs']['type'][$i];
                            $_FILES['upload_img']['tmp_name'] = $_FILES['upload_imgs']['tmp_name'][$i];
                            $_FILES['upload_img']['error'] = $_FILES['upload_imgs']['error'][$i];
                            $_FILES['upload_img']['size'] = $_FILES['upload_imgs']['size'][$i];

                            $image_name = upload_image('upload_img', $folder_path);
                            if (empty($image_name)) {
                                $errors .= $this->upload->display_errors();
                            } else {
                                $folder_images[] = array(
                                    'image_name' => $image_name,
                                    'folder_id' => $folder_id,
                                );
                            }
                        }
                    }
                }

                if (!empty($folder_images)) {
                    $folder_images = array_chunk($folder_images, 100);
                    if (!empty($folder_images)) {
                        foreach ($folder_images as $f) {
                            $this->cms_model->insert_batch('folder_images', $f);
                        }
                    }
                }
            }

            if (!empty($folder_error)) {
                echo '3^' . $folder_id . '^<div class="alert alert-success alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>Folder data saved successfully but ' . $folder_error . '</div>';
                exit;
            } else if (!empty($errors)) {
                echo '2^' . $folder_id . '^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . $errors . '</div>';
                exit;
            }else{
                echo '1^';                
            }
        } else {
            echo '0^<div class="alert alert-danger alert-styled-left alert-bordered"><button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>' . validation_errors() . '</div>';
        }
        exit;
    }

    public function delete($id) {
        $id = base64_decode($id);
        if (!empty($id)) {
            $folder_data = $this->cms_model->get_all_details('folders', array('id' => $id))->row_array();
            if (!empty($folder_data)) {
                $user_id = $folder_data['user_id'];
                $this->upload_model->remove_folder_images($user_id, $id);
                $this->cms_model->master_delete('device_folder', array('folder_id' => $id));
            }

            $this->session->set_flashdata('success', 'Folder deleted successfully.');
            redirect('upload');
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('upload');
        }
    }

    public function delete_folder_img() {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->upload_model->remove_folder_img($id);
            echo '1';
        } else {
            echo '0';
        }
    }

}

/* End of file Uploads.php */
/* Location: ./application/controllers/Uploads.php */