<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Camera extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Camera(s)';
        $provider_id = my_provider_id();
        $data['properties'] = $properties = $this->cms_model->get_all_details('properties', array('provider_id' => $provider_id))->result_array();
        $property_ids = array();
        if (!empty($properties)) {
            $property_ids = array_column($properties, 'id');
        }
        $cameras = $this->cms_model->get_all_details('property_camera', $property_ids, '', '', '', true, 'property_id')->result_array();
        if (!empty($cameras)) {
            foreach ($cameras as $camera) {
                $curent_property_index = array_search($camera['property_id'], $property_ids);
                if (!is_bool($curent_property_index)) {
                    $properties[$curent_property_index]['cameras'][] = $camera;
                }
            }
        }
        $data['properties'] = $properties;
        $this->template->load('default_front', 'camera/index', $data);
    }

    public function manage_cameras_view() {
        $data['property'] = $property = $this->input->post('property');
        $cameras = $this->cms_model->get_all_details('property_camera', array('property_id' => base64_decode($property)))->result_array();
        $data['cameras'] = $cameras;
        $this->load->view("camera/manage_cameras", $data);
    }

    public function manage_camera_tab() {
        $data['index'] = $index = $this->input->post('index');
        if ($index) {
            $this->load->view("camera/camera_tab_content", $data);
        }
    }

    public function manage_cameras_stream() {
        $data['stream_url'] = $stream_url = $this->input->post('stream_url');
        $camera_id = $this->input->post('camera');
        $data['camera_info'] = $this->cms_model->get_all_details('property_camera', array('id' => base64_decode($camera_id)))->row_array();
        if ($stream_url) {
            $this->load->view("camera/manage_camera_stream", $data);
        }
    }

    public function save() {
        $this->form_validation->set_rules("name[]", "Name", "trim|required|callback_custom_camera_validate");
        if (($this->form_validation->run() == TRUE)) {
            $property_id = base64_decode($this->input->post('property'));
            $names = $this->input->post('name');
            $remove_cameras = $this->input->post('remove_cameras');
            $cameras = $this->input->post('camera');
            $stream_urls = $this->input->post('url');
            $video_urls = $this->input->post('video_url');
            $inser_data = $update_data = array();
            foreach ($names as $k => $v) {
                if (isset($cameras[$k]) && !empty($cameras[$k])) {
                    $update_data[] = array('id' => base64_decode($cameras[$k]),
                        'name' => $v,
                        'stream_url' => $stream_urls[$k],
                        'video_url' => !empty($video_urls[$k]) ? $video_urls[$k] : NULL,
                    );
                } else {
                    $inser_data[] = array('name' => $v,
                        'stream_url' => $stream_urls[$k],
                        'video_url' => !empty($video_urls[$k]) ? $video_urls[$k] : NULL,
                        'property_id' => $property_id
                    );
                }
            }
            if (!empty($remove_cameras)) {
                $remove_ids = explode(',', $remove_cameras);
                foreach ($remove_ids as $val) {
                    $this->cms_model->master_delete('property_camera', array('id' => $val));
                }
            }
            if (!empty($inser_data)) {
                $splited_insert_data = array_chunk($inser_data, 50);
                foreach ($splited_insert_data as $v) {
                    $this->cms_model->insert_batch('property_camera', $v);
                }
            }
            if (!empty($update_data)) {
                $splited_update_data = array_chunk($update_data, 50);
                foreach ($splited_update_data as $v) {
                    $this->cms_model->update_batch('property_camera', $v, 'id');
                }
            }
            echo json_encode(array('status' => true));
        } else {
            $camera_error = $error = $this->form_validation->error_array();
            $display_tabs = array();
            $display_tab_errors = '';
            if (!empty($error)) {
                if (isset($error['name[]']) && !empty($error['name[]'])) {
                    $camera_error = json_decode($error['name[]'], true);
                    foreach ($camera_error as $key => $val) {
                        foreach ($camera_error[$key] as $k => $v) {
                            $curren_error = $v;
                            if (!in_array('camera_' . $k, $display_tabs)) {
                                $display_tabs[] = 'camera_' . $k;
                            }
                            $display_tab_errors .= $v . '<br/>';
                        }
                    }
                }
            }
            $errors = array(
                'status' => false,
                'tabs' => $display_tabs,
                'msg' => "<div class='alert alert-danger' style='margin-bottom:0px !important;'><button type='button' class='close' data-dismiss='alert'><span>x</span></button>" . $display_tab_errors . "</div><br>",
            );
            echo json_encode($errors);
        }
    }

    public function custom_camera_validate() {
        $camera_name_val = $_POST['name'];
        $camera_err = array();
        $name_err = array();
        $url_err = array();
        foreach ($camera_name_val as $key => $val) {
            if (!empty($val) && !empty($_POST['name'][$key]) && !empty($_POST['url'][$key])) {
                
            } else {
                if (empty($val)) {
                    $name_err['name'][$key] = 'Please enter camera ' . $key . ' name.';
//                    $this->form_validation->set_rules("name[$key]", "Name", "trim|required", array('Please enter camera ' . $key . ' stream url.'));
                }
                if (empty($_POST['url'][$key])) {
                    $url_err['url'][$key] = 'Please enter camera ' . $key . ' stream url.';
//                    $this->form_validation->set_rules("url[$key]", "Stream url", "trim|required", array('Please enter camera ' . $key . ' stream url.'));
                }

                if (!empty($name_err)) {
                    $camera_err['name'] = $name_err['name'];
                }

                if (!empty($url_err)) {
                    $camera_err['url'] = $url_err['url'];
                }
                if (!empty($camera_err))
                    $this->form_validation->set_message("custom_camera_validate", json_encode($camera_err));

//                    $this->form_validation->set_message("custom_camera_validate", json_encode($name_err['name']));
            }
        }
        return !empty($camera_err) ? FALSE : TRUE;
    }

    public function delete_camera() {
        $id = base64_decode($this->input->post('id'));
        if (!empty($id)) {
            $property_camera = $this->cms_model->get_all_details('property_camera', array('id' => $id))->row_array();
            if (!empty($property_camera)) {
                $this->cms_model->master_delete('property_camera', array('id' => $id));
                echo '1^';
            } else {
                echo '0^';
            }
        } else {
            echo '0^';
        }
    }

}
