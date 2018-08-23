<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_model extends CI_Model {

    function master_insert($input, $table = "") {
        $this->db->insert($table, $input);
        return $this->db->insert_id();
    }

    function master_update($table, $data, $condition) {
        $this->db->where($condition);
        $this->db->update($table, $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function master_delete($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);
        return TRUE;
    }

    function update_batch($table = "", $data = array(), $field = "") {
        $this->db->update_batch($table, $data, $field);
        return true;
    }

    function insert_batch($table = "", $data = array()) {
        $this->db->insert_batch($table, $data);
        //  echo $this->db->last_query();
        return true;
    }

    public function get_all_details($table = '', $condition = '', $sortArr = '', $limitArr = '', $select_fields = '', $where_in = false, $filed = '') {
        if ($sortArr != '' && is_array($sortArr)) {
            foreach ($sortArr as $sortRow) {
                if (is_array($sortRow)) {
                    $this->db->order_by($sortRow ['field'], $sortRow ['type']);
                }
            }
        }
        if ($select_fields != '') {
            $this->db->select($select_fields);
        }
        if ($limitArr != '') {
            if ($where_in) {
                $this->db->where_in($filed, $condition, $limitArr['l1'], $limitArr['l2']);
                return $this->db->get($table);
            } else {
                return $this->db->get_where($table, $condition, $limitArr['l1'], $limitArr['l2']);
            }
        } else {
            if ($where_in) {
                $this->db->where_in($filed, $condition);
                return $this->db->get($table);
            } else {
                return $this->db->get_where($table, $condition);
            }
        }
    }

    public function insert_update($mode = '', $table = '', $dataArr = '', $condition = '') {
        if ($mode == 'insert') {
            if ($this->db->insert($table, $dataArr)) {
                return $this->db->insert_id();
            } else {
                return 0;
            }
        } else if ($mode == 'update') {
            $this->db->where($condition);
            $this->db->update($table, $dataArr);
            $affected_row = $this->db->affected_rows();
            return $affected_row;
        } else if ($mode == 'delete') {
            $this->db->where($condition);
            $this->db->delete($table);
        }
    }

    function get_info($id = null, $tablename = null, $field = "id") {
        if ($tablename) {
            $this->db->select('*');
            if ($id) {
                $this->db->where($field, $id);
            }
            $this->db->from($tablename);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
        }
        return FALSE;
    }

}

/* End of file Cms_model.php */
/* Location: ./application/models/Cms_model.php */