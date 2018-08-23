<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cms_model');
        $this->load->model('template_model');
        is_user_logged();
    }

    public function index() {
        $data['title'] = 'Templates';
        $provider_id = my_provider_id();
        $provider_info = $this->cms_model->get_all_details('providers', 'id = ' . $provider_id)->row_array();
        $email = $provider_info['email'];
        $this->template_model->insert_default_templates($provider_id, $email);
        $this->template->load('default_front', 'email_template/index', $data);
    }

    public function get() {
        $provider_id = my_provider_id();
        $final['recordsTotal'] = $this->template_model->get_all_details('count');
        $final['redraw'] = 1;
        $final['recordsFiltered'] = $final['recordsTotal'];
        $templates = $this->template_model->get_all_details('results')->result_array();
        $start = $this->input->get('start') + 1;
        foreach ($templates as $key => $val) {
            $templates[$key] = $val;
            $templates[$key]['sr_no'] = $start++;
            $templates[$key]['responsive'] = '';
            $templates[$key]['user_name'] = $val['name'];
        }
        $final['data'] = $templates;

        echo json_encode($final);
    }

    public function edit($template_id) {
        $data['title'] = 'Edit Templates';
        $id = base64_decode($template_id);
        $templates_info = $this->cms_model->get_all_details('templates', array('id' => $id))->row_array();
        if (!empty($templates_info) && isset($templates_info)) {
            $data['template'] = $templates_info;
            $this->template->load('default_front', 'email_template/edit', $data);
        } else {
            $this->session->set_flashdata('error', 'Id not exist.');
            redirect('templates');
        }
    }

    public function save() {
        $template_id = base64_decode($this->input->post('template_id'));
        $type = $this->input->post('type');

        $this->form_validation->set_rules('template_id', 'template_id', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $fields = array(
                'from_email' => $this->input->post('from_email'),
                'subject' => $this->input->post('subject'),
                'html_content' => $this->input->post('html_content'),
                'provider_id' => my_provider_id(),
            );
            if ($type == 'PDF') {
                $fields['header_box'] = $this->input->post('header_box');
                $fields['footer_box'] = $this->input->post('footer_box');

                $folder_error = '';
                if (!empty($template_id)) {
                    $folder_path = $this->config->item('uploads_path') . '/templates/' . $template_id;
                    $folder_exist = true;
                    if (!file_exists($folder_path)) {
                        if (!created_directory($folder_path)) {
                            $folder_exist = false;
                            $folder_error = 'Templates Folder could not created.';
                        }
                    }
                    if (empty($folder_error) && file_exists($folder_path) && isset($_FILES['image'])) {
                        if (($_FILES['image']['size']) > 0) {
                            $this->template_model->remove_template_image($template_id);
                            $image_name = upload_image('image', $folder_path);
                            if (empty($image_name)) {
                                $folder_error .= $this->upload->display_errors();
                            } else {
                                $fields['image'] = $image_name;
                            }
                        }
                    }
                } else {
                    echo "2^";
                    exit;
                }
            }

            if (!empty($template_id)) {
                $this->cms_model->master_update('templates', $fields, array('id' => $template_id));
            }
            echo "1^";
        } else {
            echo "0^<div class='alert alert-danger' style='margin-bottom:0px !important;'><button class='close' data-dismiss='alert'></button>" . validation_errors() . "</div><br>";
        }
        exit;
    }

    function delete_template_img() {
        $id = $this->input->post('id');
        $result = "0";
        if (!empty($id)) {
            $this->template_model->remove_template_img($id);
            $result = "1";
        }
        echo $result;
    }

    function update_status() {
        $tid = $this->input->post('tid');
        $tid = base64_decode($tid);
        $checked = $this->input->post('checked');
        $fields = array(
            'is_enable' => ($checked == "true") ? 1 : 0
        );
        $this->cms_model->master_update('templates', $fields, array('id' => $tid));
    }

    function preview($template_id) {
        $id = base64_decode($template_id);
        $templates_info = $this->cms_model->get_all_details('templates', array('id' => $id))->row_array();
        $data = array();
        if ($templates_info['type'] == "PDF") {
            $template_image = $templates_info['image'];
            $data['templates_info'] = $templates_info;
            $data['template_image'] = $template_image;
            $data['subject'] = $templates_info['subject'];
            $data['header_info'] = $templates_info['header_box'];
            $data['footer_box'] = $templates_info['footer_box'];
            $data['transaction_info']['payment_status'] = 'paid';
            $data['customer'] = array(
                array(
                    'name' => 'Customer Name',
                    'address' => 'Customer Address',
                    'phone' => 'Customer Phone',
                    'email' => 'Customer Email',
                )
            );

            $pdf_html_view = 'email_template/invoice_pdf';
            $pdf_content_html = $this->load->view($pdf_html_view, $data, true);
            require_once(APPPATH . 'libraries/Pdf.php');
            $this->pdf = new Pdf();
            $this->pdf->useAdobeCJK = true;
            $this->pdf->autoLangToFont = true;
            $this->pdf->autoScriptToLang = true;
            $this->pdf->WriteHTML($pdf_content_html);
            $this->pdf->Output();
        } else {
            $message = $this->load->view('email_template/default_header.php', array(), true);
            $message .= $templates_info['html_content'];
            $message .= $this->load->view('email_template/default_footer.php', array(), true);
            $data['message'] = $message;
        }
        $this->load->view("email_template/preview", $data);
    }

    function test_pdf() {
        require_once(APPPATH . 'libraries/Pdf.php');
        $this->pdf = new Pdf();
        $this->pdf->useAdobeCJK = true;
        $this->pdf->autoLangToFont = true;
        $this->pdf->autoScriptToLang = true;
        $pdf_content_html = $this->load->view('');
        $this->pdf->WriteHTML($pdf_content_html);
        $this->pdf->Output();
    }

}

/* End of file Templates.php */
/* Location: ./application/controllers/Templates.php */