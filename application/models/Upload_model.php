<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_model extends CI_Model {

    public function get_folders($type) {
        $columns = array('f.id', 'f.name', 'u.name');
        $keyword = $this->input->get('search');

        $user_data = $this->session->userdata('session_info');
        $user_id = $user_data['user_id'];
        $user_type = $user_data['type'];


        $order = $this->input->get('order');
        $this->db->select('f.*,u.name as user_name');
        if ($user_type == 'user') {
            $this->db->where(array(
                'f.user_id' => $user_id
            ));
        }

        if (!empty($keyword['value'])) {
            $where = '(f.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR u.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR f.created LIKE "%' . $keyword['value'] . '%")';
            $this->db->where($where);
        }

        $this->db->join("users" . ' as u', 'f.user_id=u.id', 'left');
        if (!empty($order)) {
            $order = $this->input->get('order');
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("folders f");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("folders f");
            return $query;
        }
    }

    function remove_folder_img($id, $action = "") {
        $this->db->where('id', $id);
        $this->db->select('image_name,folder_id');
        $this->db->from('folder_images');
        $query = $this->db->get();
        $result = $query->result();
        $image = $result[0]->image_name;
        $folder_id = $result[0]->folder_id;
        if (!empty($image)) {
            $this->db->where('id', $id);
            $this->db->delete('folder_images');
            $image_path = $this->config->item('uploads_path') . '/' . $folder_id . '/';
            $image = $image_path . $image;
            if (file_exists($image)) {
                @unlink($image);
            }
        }
    }

    function remove_folder_images($user_id, $id) {
        $this->db->where('folder_id', $id);
        $this->db->delete('folder_images');
        $dir_path = $this->config->item('uploads_path') . '/' . $user_id;
        $this->deleteDirectory($dir_path);

        $this->db->where('id', $id);
        $this->db->delete('folders');
    }

    function deleteDirectory($dir) {
        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
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

    public function get_assigned_folders($device_id) {
        $user_type = $this->session->userdata('type');
        $this->db->select('m.*,f.name as folder_name');
        $this->db->where(array(
            'm.device_id' => $device_id,
        ));

        $this->db->join("folders" . ' as f', 'm.folder_id=f.id', 'left');
        $query = $this->db->get("device_folder m");
        return $query;
    }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
