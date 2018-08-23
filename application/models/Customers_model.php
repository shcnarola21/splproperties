<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model {

    public function find_customers_by_email($email_add) {
        $this->db->select('cid as user_id, name');
        $this->db->from('customers');
        $this->db->where('email', $email_add);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_customers_details($user_id) {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('cid', $user_id);
        $this->db->limit(1);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_customers_profile($user_id) {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('cid', $user_id);
        $res = $this->db->get();
        return $res->row_array();
    }

    public function check_unique_email_for_customers($where) {
        $this->db->where($where);
        $query = $this->db->get("customers");
        return $query->num_rows();
    }

    public function get_profile($user_id) {
        $this->db->select('*,c.cid as uid');
        $this->db->from('customers' . ' c');
        $this->db->where(array('c.cid' => $user_id));
        $res = $this->db->get();
        return $res->row_array();
    }

    public function get_all_details($table = '', $condition = '', $sortArr = '', $limitArr = '') {
        if ($sortArr != '' && is_array($sortArr)) {
            foreach ($sortArr as $sortRow) {
                if (is_array($sortRow)) {
                    $this->db->order_by($sortRow ['field'], $sortRow ['type']);
                }
            }
        }
        if ($limitArr != '') {
            return $this->db->get_where($table, $condition, $limitArr['l1'], $limitArr['l2']);
        } else {
            return $this->db->get_where($table, $condition);
        }
    }

    public function get_customers($type) {
        $provider_id = my_provider_id();
        $columns = array('c.cid', 'c.name', 'c.address', 'c.city', 'c.state', 'c.country', 'c.zip_code', 'c.status', 'c.created');
        $keyword = $this->input->get('sSearch');
        $is_terminated = isset($_GET['show_terminated']) ? $this->input->get('show_terminated') : NULL;

        $this->db->select('c.*,p.address as p_address, p.city as p_city,p.state as p_state,p.country as p_country,p.zip_code as p_zip_code');
        $this->db->where(array('c.provider_id' => $provider_id));
        if (!$is_terminated) {
            $this->db->where(array('c.status !=' => 'terminated'));
        }
        $this->db->join('properties p', '(p.id=c.property_id OR c.property_id IS NULL) AND p.provider_id=' . $provider_id, 'left');
        if (!empty($keyword)) {
            $where = '(c.cid LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.name LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.status LIKE ' . $this->db->escape('%' . $keyword . '%');
            $where .= 'OR c.address LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.city LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.state LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.country LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR c.zip_code LIKE ' . $this->db->escape('%' . $keyword . '%');
            $where .= 'OR p.address LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR p.city LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR p.state LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR p.country LIKE ' . $this->db->escape('%' . $keyword . '%') . ' OR p.zip_code LIKE ' . $this->db->escape('%' . $keyword . '%') . ')';
            $this->db->where($where);
        }
        $this->db->group_by('c.cid');
        $order_col = $this->input->get('iSortCol_0');
        $order = $this->input->get('sSortDir_0');
        if (!empty($order) && !empty($order_col)) {
            $this->db->order_by($columns[$order_col], $order);
        }
        if ($type == 'count') {
            $query = $this->db->get("customers c");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('iDisplayLength'), $this->input->get('iDisplayStart'));
            $query = $this->db->get("customers c");
            return $query;
        }
    }

    function get_packages_by_agent($id, $type, $services = '') {
        $this->db->select('p.id, p.name, p.price, p.services');
        $this->db->where('p.type', $type);
        if (!empty($services)) {
            foreach ($services as $value) {
                $this->db->where('p.services', $value);
            }
        }

        $this->db->from('packages p');
        if (!empty($id)) {
            $this->db->where('p.provider_id', $id);
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function get_customer_package($id) {
        $this->db->select('p.id, p.name, p.price, p.term, p.services, GROUP_CONCAT(s.service_name) as service_names');
        $this->db->join('services s', 'FIND_IN_SET(s.id, p.services) > 0', 'left');
        $this->db->from('packages p');

        $this->db->where('p.id', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function get_package_addons($package_services_id) {
        $provider_id = my_provider_id();
        $this->db->select('p.id, p.name, p.price, p.term');

        $this->db->from('packages p');
        $this->db->where(array('p.type' => 'Addon', 'FIND_IN_SET(' . $package_services_id . ', p.services) >' => ' 0', 'p.provider_id' => $provider_id));

        $query = $this->db->get();
//        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function check_email($email = "", $cid = "") {
        $this->db->select('cid');
        $this->db->where('cid != ', $cid);
        //$where_str = '(email = "'.$email.'" OR FIND_IN_SET("'.$email.'", additional_email) > 0)';
        $this->db->where('email', $email);
        $this->db->from('customers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }

    function save($fields, $package_id = '', $table = "customers") {
        if (!empty($package_id)) {
            if ($table == 'customers') {
                $this->db->where('cid', $package_id);
            } else {
                $this->db->where('id ', $package_id);
            }
            $this->db->update($table, $fields);
            return true;
        } else {
            $this->db->insert($table, $fields);
            return $this->db->insert_id();
        }
    }

    function get_package_addon_customers($pid, $type, $provider_id) {
        $this->db->select('GROUP_CONCAT(c.cid) as cids,GROUP_CONCAT(CONCAT(c.cid, "^", c.email,"^",c.name)) as emails');
        if ($type == 'Addon') {
            $this->db->where('FIND_IN_SET(' . $pid . ',c.basic_addon) > 0');
        } else {
            $this->db->where('FIND_IN_SET(' . $pid . ',c.basic_package) > 0');
        }
        $this->db->where('provider_id', $provider_id);
        $this->db->from('customers c');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return FALSE;
    }

    function get_customer_notes($id, $type = 'result', $by_customer = true) {
        $columns = array('cn.id', 'cn.notes', 'cn.created', 'p.name', 'added_by_name');
        $keyword = $this->input->get('search');

        if ($by_customer) {
            $this->db->select('cn.*,p.name as provider_name,IF(cn.added_by IS NULL, p.name,u.name ) as added_by_name, u.id as user_id');
            $this->db->where(array('cn.customer_id' => $id));
        } else {
            $this->db->select('cn.*,p.name as provider_name,IF(cn.added_by IS NULL, p.name,u.name ) as added_by_name, u.id as user_id,c.name as customer_name');
            $this->db->where(array('cn.provider_id' => $id));
        }
        if (!$by_customer) {
            $this->db->join('customers c', 'c.cid=cn.customer_id', 'left');
        }
        $this->db->join('providers p', 'p.id=cn.provider_id', 'left');
        $this->db->join('users u', 'u.id=cn.added_by OR cn.added_by IS NULL', 'left');
        if (!empty($keyword['value'])) {
            $where = '(cn.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR added_by_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cn.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            if (!$by_customer) {
                $where .= 'OR customer_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            }
            $where .= ')';
            $this->db->having($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        $this->db->group_by('cn.id');
        if ($type == 'count') {
            $query = $this->db->get("customer_notes cn");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("customer_notes cn");
            return $query;
        }
    }

    function get_customer_notification($id, $type = 'result') {
        $provider_id = my_provider_id();

        $columns = array('n.id', 'customer_name', 'n.type', 'n.updated_time', 'c_address');
        $keyword = $this->input->get('search');

        $this->db->select('n.id,n.created,n.type,n.updated_time,c.property_id,c.name as customer_name,c.address as c_address,c.city as c_city,c.state as c_state,c.country as c_country,c.zip_code as c_zip_code,p.address as p_address, p.city as p_city,p.state as p_state,p.country as p_country,p.zip_code as p_zip_code');
        $this->db->join('customers c', 'c.cid=n.customer_id', 'left');
        $this->db->join('properties p', '(p.id=c.property_id OR c.property_id IS NULL) AND p.provider_id=' . $provider_id, 'left');
        $this->db->where(array('n.provider_id' => $provider_id, 'n.customer_id' => $id));
        if (!empty($keyword['value'])) {
//            $where = '(cn.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR added_by_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cn.notes LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
//            if (!$by_customer) {
//                $where .= 'OR customer_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
//            }
//            $where .= ')';
//            $this->db->having($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        $this->db->group_by('n.id');
        if ($type == 'count') {
            $query = $this->db->get("notification_history n");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("notification_history n");
            return $query;
        }
    }

    function get_termination_customer_list() {
        $this->db->select('*', false);
        $wh_date = "((termination_event = 'specific' AND termination_specific_date = CURDATE()) OR (termination_event = 'on_renewal' AND  date_format(renewal_date,'%Y-%m-%d') = CURDATE()))";
        $this->db->where($wh_date);
        $this->db->where('cid !=', 0);
        $this->db->from('customers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        }
        return FALSE;
    }

    function get_renewal_customers_list() {
        $this->db->select('*', false);
        $this->db->where('cid !=', 0);
        $wh_date = 'date_format(renewal_date,"%Y-%m-%d") <= CURDATE()';
        $this->db->where($wh_date);
        $this->db->where_in('status', array('active', 'suspended'));
        $this->db->from('customers');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        }
        return FALSE;
    }

    function get_customer_credits($id, $type = 'result') {
        $columns = array('cn.id', 'cn.amount', 'added_by_name', 'cn.type', 'month', 'cn.created', 'cn.description');
        $keyword = $this->input->get('search');

        $this->db->select('cn.*,p.name as provider_name,IF(cn.added_by IS NULL, p.name,u.name ) as added_by_name, u.id as user_id');
        $this->db->where(array('cn.customer_id' => $id));

        $this->db->join('providers p', 'p.id=cn.provider_id', 'left');
        $this->db->join('users u', 'u.id=cn.added_by OR cn.added_by IS NULL', 'left');
        if (!empty($keyword['value'])) {
            $where = '(cn.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR added_by_name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cn.type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cn.amount LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cn.description LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            $where .= 'OR DATE_FORMAT(cn.created,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= ')';
            $this->db->having($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        $this->db->group_by('cn.id');
        if ($type == 'count') {
            $query = $this->db->get("customer_credits cn");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("customer_credits cn");
            return $query;
        }
    }

    function get_customer_total_credit($customer_id, $only_general = false) {
        $this->db->select_sum('amount', 'amount');
        $where = array('customer_credits.customer_id' => $customer_id);
        if ($only_general) {
            $where['customer_credits.type'] = 'General';
        }
        $this->db->where($where);
        $this->db->from('customer_credits');
        $query = $this->db->get();
        $result = $query->result();
        return ($result[0]->amount > 0) ? $result[0]->amount : 0;
    }

    function get_customer_contracts($id, $type = 'result') {
        $columns = array('cc.id', 'cc.name', 'cc.start_date', 'cc.end_date', 'cc.notification_email', 'cc.reminder_days');
        $keyword = $this->input->get('search');

        $this->db->select('*');
        $this->db->where(array('cc.customer_id' => $id));

        if (!empty($keyword['value'])) {
            $where = '(cc.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cc.name LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cc.notification_email LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR cc.reminder_days LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            $where .= 'OR DATE_FORMAT(cc.start_date,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= 'OR DATE_FORMAT(cc.end_date,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= ')';
            $this->db->having($where);
        }
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("customer_contracts cc");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("customer_contracts cc");
            return $query;
        }
    }

    function get_customer_invoices($id, $type = 'result') {
        $columns = array('t.id', 't.created', 't.detail', 't.price', 't.payment_type', 't.ccnumber', 't.card_type', 't.payment_status');
        $keyword = $this->input->get('search');

        $this->db->select('t.*,p.failture_reason,p.message');
        $this->db->where(array('t.customer_id' => $id,'t.is_deleted' => '0'));
        $this->db->join('payment_logs p', 't.invoice_id = p.id', 'left');
        if (!empty($keyword['value'])) {
            $where = '(t.id LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.detail LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.card_type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.payment_status LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.price LIKE ' . $this->db->escape('%' . $keyword['value'] . '%') . ' OR t.payment_type LIKE ' . $this->db->escape('%' . $keyword['value'] . '%');
            $where .= 'OR DATE_FORMAT(t.created,"%Y-%m-%d") = ' . $this->db->escape($keyword['value']);
            $where .= ')';
            $this->db->having($where);
        }
        $this->db->group_by('t.id');
        $order = $this->input->get('order');
        if (!empty($order)) {
            $this->db->order_by($columns[$order[0]['column']], $order[0]['dir']);
        }
        if ($type == 'count') {
            $query = $this->db->get("transactions t");
            return $query->num_rows();
        } else {
            $this->db->limit($this->input->get('length'), $this->input->get('start'));
            $query = $this->db->get("transactions t");
            return $query;
        }
    }

}

/* End of file Customers_model.php */
/* Location: ./application/models/Customers_model.php */
