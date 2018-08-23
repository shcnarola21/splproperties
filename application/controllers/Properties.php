<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('cms_model', 'properties_model'));
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Properties';
        $this->template->load('default_front', 'properties/index', $data);
    }

    public function add() {
        $data = array();
        $data['title'] = 'Add Property';
        $data['countries'] = get_countries();
        $this->template->load('default_front', 'properties/add', $data);
    }

    public function edit($property_id = null) {
        $id = base64_decode($property_id);
        $data = array();
        $data['title'] = 'Edit Property';
        $data['countries'] = get_countries();
        if (is_numeric($id)) {
            $property_arr = $this->cms_model->get_info($id, 'properties', 'id');
            if (!empty($property_arr) && isset($property_arr[0])) {
                $data['property_arr'] = $property_arr[0];
                $data['property_images_arr'] = $this->cms_model->get_all_details('property_images', array('property_id' => $id, 'type' => 'image'))->result_array();
                $data['property_agreements_arr'] = $this->cms_model->get_all_details('property_images', array('property_id' => $id, 'type' => 'agreement'))->result_array();
            } else {
                $this->session->set_flashdata('error', 'Id not exist.');
                redirect('properties');
            }
        }
        $this->template->load('default_front', 'properties/add', $data);
    }

    public function get() {
        $final['recordsTotal'] = $this->properties_model->get_all_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $properties = $this->properties_model->get_all_details('results')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($properties as $key => $val) {
            $properties[$key] = $val;
            $properties[$key]['sr_no'] = $start++;
            $properties[$key]['responsive'] = '';
        }
        $final['data'] = $properties;
        echo json_encode($final);
    }

    public function save() {
        $id = base64_decode($this->input->post('property_id'));
        $address = $this->input->post('address');
        $units = $this->input->post('units');
        $this->form_validation->set_rules('address', 'Property Address', 'trim|required');
        $this->form_validation->set_rules('units', 'Property Unit', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $op_state = $this->input->post('state_op');
            $state = $this->input->post('state');
            if (!empty($state)) {
                $op_state = $state;
            }
            $fields = array(
                'address' => $address,
                'city' => $this->input->post('city'),
                'state' => $op_state,
                'country' => $this->input->post('country'),
                'zip_code' => $this->input->post('zip_code'),
                'units' => $units,
                'description' => $this->input->post('description'),
                'provider_id' => my_provider_id(),
            );

            if (!empty($id)) {
                $this->cms_model->master_update('properties', $fields, array('id' => $id));
            } else {
                $id = $this->cms_model->master_insert($fields, 'properties');
            }

            $folder_error = '';
            if (!empty($id)) {
                $property_id = $id;
                $folder_path = $this->config->item('uploads_path') . '/properties/' . $property_id;
                $folder_exist = true;
                if (!file_exists($folder_path)) {
                    if (!created_directory($folder_path)) {
                        $folder_exist = false;
                        $folder_error = 'Folder could not created.';
                    }
                }
            }

            if (empty($folder_error) && file_exists($folder_path) && isset($_FILES['all_files']['name'][0]) && !empty($_FILES['all_files']['name'][0])) {
                $count = count($_FILES['all_files']['name']);
                for ($i = 0; $i <= $count - 1; $i++) {
                    if ($_FILES['all_files']['size'][$i]) {
                        $_FILES['upload_img']['name'] = $_FILES['all_files']['name'][$i];
                        $_FILES['upload_img']['type'] = $_FILES['all_files']['type'][$i];
                        $_FILES['upload_img']['tmp_name'] = $_FILES['all_files']['tmp_name'][$i];
                        $_FILES['upload_img']['error'] = $_FILES['all_files']['error'][$i];
                        $_FILES['upload_img']['size'] = $_FILES['all_files']['size'][$i];

                        $image_name = upload_image('upload_img', $folder_path);
                        if (empty($image_name)) {
                            $folder_error .= $this->upload->display_errors();
                        } else {
                            $property_images = array(
                                'type' => 'image',
                                'image_name' => $image_name,
                                'property_id' => $property_id,
                            );
                            $this->cms_model->master_insert($property_images, 'property_images');
                        }
                    }
                }
            } else {
                echo "2^";
                exit;
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    public function delete() {
        $pid = $this->input->post('pid');
        $pid = base64_decode($pid);
        $property_info = $this->cms_model->get_info($pid, 'properties', 'id');
        $result = array();
        if (!empty($property_info)) {
            $this->properties_model->remove_property_images($pid);
            $this->cms_model->master_delete('properties', array('id' => $pid));
            $result['status'] = true;
        } else {
            $result['status'] = false;
        }
        echo json_encode($result);
    }

    public function rotate_img($id) {
        $degrees = $this->input->post('degree');
        $property_image_data = $this->cms_model->get_all_details('property_images', array('id' => $id))->row_array();

        $provider_id = my_provider_id();
        $folder_path = $this->config->item('uploads_path') . '/properties/' . $property_image_data['property_id'];
        $filename = $folder_path . '/' . $property_image_data['image_name'];

        $mime_type = mime_content_type($filename);
        $source = "";
        if ($mime_type != 'image/gif') {
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg') {
                header('Content-type: image/jpeg');
                $source = imagecreatefromjpeg($filename);
                $rotate = imagerotate($source, 270, 0);
                imagejpeg($rotate, $filename);
            } elseif ($mime_type == 'image/gif') {
                /* header('Content-type: image/gif');
                  $source = imagecreatefromgif($filename);
                  $rotate = imagerotate($source, 270, 0);
                  imagegif($rotate, $filename); */
            } elseif ($mime_type == 'image/png') {
                header('Content-type: image/png');
                $source = imagecreatefrompng($filename);
                $rotate = imagerotate($source, 270, 0);
                imagepng($rotate, $filename);
            }
            imagedestroy($source);
            imagedestroy($rotate);
        }
    }

    public function delete_property_img() {
        $id = $this->input->post('id');
        $result = "0";
        if (!empty($id)) {
            $this->properties_model->remove_property_img($id);
            $result = "1";
        }
        echo $result;
    }

    public function upload_file() {
//        echo "<pre>";
//        print_r($_POST);
//        print_r($_FILES);
//        echo "</pre>";
//        exit;
        $property_id = $this->input->post('property_id');
        $property_id = base64_decode($property_id);
        $type = $this->input->post('file_type');
        $property_data = $this->cms_model->get_all_details('properties', array('id' => $property_id))->row_array();
        $folder_error = '';
        $folder_exist = true;
        $folder_path = $this->config->item('uploads_path') . '/properties/' . $property_id;
        if ($type == 'agreement') {
            $folder_path = $this->config->item('uploads_path') . '/properties/' . $property_id . '/agreements';
        }

        if (!file_exists($folder_path)) {
            if (!created_directory($folder_path)) {
                $folder_exist = false;
                $folder_error = 'Folder could not created.';
            }
        }
        $json = array();
        $html = "";
        if (file_exists($folder_path)) {
            if (!empty($_FILES)) {
                if (isset($_FILES['upload_file_view']['name'][0]) && !empty($_FILES['upload_file_view']['name'][0])) {
                    $count = count($_FILES['upload_file_view']['name']);
                    for ($i = 0; $i <= $count - 1; $i++) {
                        if ($_FILES['upload_file_view']['size'][$i]) {
                            $_FILES['upload_img']['name'] = $_FILES['upload_file_view']['name'][$i];
                            $_FILES['upload_img']['type'] = $_FILES['upload_file_view']['type'][$i];
                            $_FILES['upload_img']['tmp_name'] = $_FILES['upload_file_view']['tmp_name'][$i];
                            $_FILES['upload_img']['error'] = $_FILES['upload_file_view']['error'][$i];
                            $_FILES['upload_img']['size'] = $_FILES['upload_file_view']['size'][$i];

                            $image_name = upload_image('upload_img', $folder_path);
                            if (empty($image_name)) {
                                $folder_error .= $this->upload->display_errors();
                            } else {
                                $property_images = array(
                                    'type' => 'image',
                                    'image_name' => $image_name,
                                    'property_id' => $property_id,
                                );

                                $last_inserted_id = $this->cms_model->master_insert($property_images, 'property_images');
                                $html .= $this->load->view('properties/img_block', array('property_id' => $property_id, 'id' => $last_inserted_id, 'image_name' => $image_name), true);
                            }
                        }
                    }
                }
                if (isset($_FILES['general_agreement']['name'][0]) && !empty($_FILES['general_agreement']['name'][0])) {
                    $count = count($_FILES['general_agreement']['name']);
                    for ($i = 0; $i <= $count - 1; $i++) {
                        if ($_FILES['general_agreement']['size'][$i]) {
                            $_FILES['upload_img']['name'] = $_FILES['general_agreement']['name'][$i];
                            $_FILES['upload_img']['type'] = $_FILES['general_agreement']['type'][$i];
                            $_FILES['upload_img']['tmp_name'] = $_FILES['general_agreement']['tmp_name'][$i];
                            $_FILES['upload_img']['error'] = $_FILES['general_agreement']['error'][$i];
                            $_FILES['upload_img']['size'] = $_FILES['general_agreement']['size'][$i];

                            $image_name = upload_image('upload_img', $folder_path, '', '', 'pdf', $_FILES['upload_img']['name']);
                            if (empty($image_name)) {
                                $folder_error .= $this->upload->display_errors();
                            } else {
                                $property_images = array(
                                    'type' => 'agreement',
                                    'image_name' => $image_name,
                                    'property_id' => $property_id,
                                );

                                $last_inserted_id = $this->cms_model->master_insert($property_images, 'property_images');
                                $html .= $this->load->view('properties/img_block', array('property_id' => $property_id, 'id' => $last_inserted_id, 'image_name' => $image_name), true);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($folder_error)) {
            $json = array(
                'status' => false,
                'result' => 1,
                'msg' => strip_tags($folder_error)
            );
        } else {
            $json = array(
                'status' => TRUE,
                'property_id' => $property_id,
                'html' => $html
            );
        }
        echo json_encode($json);
        exit;
    }

    //For Property Zones

    public function get_zone($property_id) {
        $property_id = base64_decode($property_id);
        $final['recordsTotal'] = $this->properties_model->get_all_zones($property_id, 'count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $property_zones = $this->properties_model->get_all_zones($property_id, 'results')->result_array();

        foreach ($property_zones as $key => $val) {
            $property_zones[$key] = $val;
            $property_zones[$key]['responsive'] = '';
        }
        $final['data'] = $property_zones;
        echo json_encode($final);
    }

    public function load_manage_zone() {
        $zone_id = base64_decode($this->input->post('zone'));
        $this->data['property'] = $this->input->post('property');
        $this->data['zone_info'] = $this->cms_model->get_all_details('property_zones', array('id' => $zone_id))->row_array();
        $this->load->view('properties/manage_zone', $this->data);
    }

    public function manage_zone() {
        $zone_id = base64_decode($this->input->post('zone'));
        $property_id = base64_decode($this->input->post('property'));
//        $this->data['zone_info'] = $this->cms_model->get_all_details('property_zones', array('id' => $zone_id))->row_array();
        $this->form_validation->set_rules("name", "Name", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        if (($this->form_validation->run() == TRUE)) {
            $name = $this->input->post('name');
            $password = $this->input->post('password');

            $input = array(
                'name' => $name,
                'password' => $password,
                'property_id' => $property_id
            );
            if (empty($zone_id)) {
                $this->cms_model->master_insert($input, "property_zones");
            } else {
                $condition = array('id' => $zone_id);
                $this->cms_model->master_update("property_zones", $input, $condition);
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger'><button class='close' data-dismiss='alert'><span>x</span></button>" . validation_errors() . "</div>";
        }
        exit;
    }

    public function delete_zone() {
        $id = base64_decode($this->input->post('zone'));
        $keyless_accesses = $this->cms_model->get_all_details('keyless_accesses', array('FIND_IN_SET(' . $id . ', zone_id) >' => 0))->result_array();
        $zone_info = $this->cms_model->get_all_details('property_zones', array('id' => $id))->row_array();
        $result = array();
        if (!empty($keyless_accesses)) {
            $this->data['alert_msg'] = "You couldn't delete the zone.Below Keyless Access are using " . $zone_info['name'] . ".<br>Please change the zone for the keyless access then try to delete zone.";
            $this->data['keyless_accesses'] = $keyless_accesses;
            $result['status'] = false;
            $result['keyless_accesses'] = $this->load->view('properties/list_keyless_zones', $this->data, TRUE);
        } else {
            $this->cms_model->master_delete('property_zones', array('id' => $id));
            $result['status'] = true;
        }
        echo json_encode($result);
    }

}

/* End of file Properties.php */
/* Location: ./application/controllers/Properties.php */