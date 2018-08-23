<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Template_model extends CI_Model {

    public function get_all_details($type) {
        $columns = array('t.id', 't.type', 't.name');
        $keyword = $this->input->get('search');
        $provider_id = my_provider_id();
        $this->db->select('*');

        if (!empty($keyword['value'])) {
            $where = '(t.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.subject LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }
        $this->db->where('provider_id', $provider_id);
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("templates t");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("templates t");
            return $query;
        }
    }

    function get_template_info_by_name($provider_id, $type) {
        $this->db->select('*');
        $this->db->where('name', $type);
        $this->db->where('provider_id', $provider_id);
        $this->db->from('templates');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return FALSE;
    }
    
    function get_template($provider_id, $type) {
        $this->db->select('*');
        $this->db->where('name', $type);
        $this->db->where('provider_id', $provider_id);
        $this->db->from('templates');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return FALSE;
    }

    function insert_default_templates($provider_id, $email) {
        //create default template
        $templates_data = array();
        $is_exist_eviction_notice = $this->get_template_info_by_name($provider_id, 'Eviction_Notice');
        if (empty($is_exist_eviction_notice)) {
            $templates_data[] = array(
                'name' => 'Eviction_Notice',
                'type' => 'Notice',
                'subject' => 'Eviction Notice',
                'html_content' => '',
                'provider_id' => $provider_id,
                'from_email' => $email,
                'is_enable' => 0
            );
        }

        $is_exist_PDF_format = $this->get_template_info_by_name($provider_id, 'PDF_Format_Invoice');
        if (empty($is_exist_PDF_format)) {
            $templates_data[] = array(
                'name' => 'PDF_Format_Invoice',
                'type' => 'PDF',
                'subject' => 'PDF Format Invoice',
                'html_content' => '',
                'provider_id' => $provider_id,
                'from_email' => $email,
                'is_enable' => 0
            );
        }

        $is_exist_invoice_success = $this->get_template_info_by_name($provider_id, 'Invoice_Success');
        if (empty($is_exist_invoice_success)) {
            $templates_data[] = array(
                'name' => 'Invoice_Success',
                'type' => 'Invoice',
                'subject' => 'Invoice Success',
                'html_content' => '',
                'provider_id' => $provider_id,
                'from_email' => $email,
                'is_enable' => 0
            );
        }

        $is_exist_invoice_fail = $this->get_template_info_by_name($provider_id, 'Invoice_Fail');
        if (empty($is_exist_invoice_fail)) {
            $templates_data[] = array(
                'name' => 'Invoice_Fail',
                'type' => 'Invoice',
                'subject' => 'Invoice Fail',
                'html_content' => '',
                'provider_id' => $provider_id,
                'from_email' => $email,
                'is_enable' => 0
            );
        }

        $is_exist_invoice_fail = $this->get_template_info_by_name($provider_id, 'Auto_Increase_Package_Price');
        if (empty($is_exist_invoice_fail)) {
            $templates_data[] = array(
                'name' => 'Auto_Increase_Package_Price',
                'type' => 'Package',
                'subject' => 'Auto Increase Package Price',
                'html_content' => '',
                'provider_id' => $provider_id,
                'from_email' => $email,
                'is_enable' => 0
            );
        }
        if (!empty($templates_data)) {
            $this->db->insert_batch('templates', $templates_data);
        }
    }

    function remove_template_img($id) {
        $this->db->where('id', $id);
        $this->db->select('image');
        $this->db->from('templates');
        $query = $this->db->get();
        $result = $query->result();
        $image = $result[0]->image;
        if (!empty($image)) {
            $this->db->where('id', $id);
            $data = array('image' => '');
            $this->db->update('templates', $data);
            $image_path = $this->config->item('uploads_path') . '/templates/' . $id . '/';
            $image = $image_path . $image;
            if (file_exists($image)) {
                @unlink($image);
            }
        }
    }

    function remove_template_image($template_id) {
        $this->db->where('id', $template_id);
        $this->db->select('image');
        $this->db->from('templates');
        $query = $this->db->get();
        $result = $query->result();
        $template_image = $result[0]->image;
        if (!empty($template_image)) {
            $this->db->where('id', $template_id);
            $this->db->update('templates', array('image' => ''));
            $image_path = $this->config->item('uploads_path') . "templates/" . $template_id;
            $image = $image_path . $template_image;
            if (file_exists($image)) {
                @unlink($image);
            }
        }
    }

}

/* End of file Providers_model.php */
/* Location: ./application/models/Providers_model.php */
