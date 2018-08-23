<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model {

    public function get_all_details($type) {
        $provider_id = my_provider_id();
        $columns = array('p.id', 'p.address', 'p.units', 'p.created');
        $keyword = $this->input->get('search');

        $this->db->select('*');

        if (!empty($keyword['value'])) {
            $where = '(p.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR p.address LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR p.units LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR p.created LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }


        $this->db->where('provider_id', $provider_id);

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("properties p");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("properties p");
            return $query;
        }
    }

    function remove_property_img($id, $action = "") {
        $this->db->where('id', $id);
        $this->db->select('image_name,property_id,type');
        $this->db->from('property_images');
        $query = $this->db->get();
        $result = $query->result();
        $image = $result[0]->image_name;
        $property_id = $result[0]->property_id;
        if (!empty($image)) {
            $this->db->where('id', $id);
            $this->db->delete('property_images');
            $image_path = $this->config->item('uploads_path') . '/properties/' . $property_id . '/';
            $image = $image_path . $image;
            if (file_exists($image)) {
                @unlink($image);
            }
        }
    }

    function remove_property_images($id) {
        $this->db->where('property_id', $id);
        $this->db->delete('property_images');
        $dir_path = $this->config->item('uploads_path') . '/properties/' . $id;
        $this->deleteDirectory($dir_path);
    }

    function deleteDirectory($dir) {
        if (!is_dir($dir) || is_link($dir)) {
            return @unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectory($dir . "/" . $item, false)) {
                chmod($dir . "/" . $item, 0777);
                if (!$this->deleteDirectory($dir . "/" . $item, false))
                    return false;
            };
        }
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_all_zones($property_id, $type) {
        $columns = array('pz.id', 'pz.name', 'pz.password');
        $keyword = $this->input->get('search');

        $this->db->select('pz.*');

        if (!empty($keyword['value'])) {
            $where = '(pz.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR pz.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR pz.password LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ')';
            $this->db->where($where);
        }


        $this->db->where('property_id', $property_id);

        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("property_zones pz");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("property_zones pz");
            return $query;
        }
    }

}

/* End of file Providers_model.php */
/* Location: ./application/models/Providers_model.php */
