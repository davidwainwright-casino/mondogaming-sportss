<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {
    private $Settings;

    public function __construct() {
        parent::__construct();
        $this->Settings = $this->getSettings();
    }

    public function getSettings() {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUser($field, $value) {
        $q = $this->db->get_where('users', array($field => $value));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function get_topics($limit, $my_threads = NULL, $user_id = NULL, $category_slug = NULL) {

        $this->db->select("title, {$this->db->dbprefix('topics')}.slug, username, avatar, COUNT({$this->db->dbprefix('posts')}.id) as replies, {$this->db->dbprefix('topics')}.category_slug")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->join('users', 'users.id=topics.created_by', 'left')
        ->where('topics.status', 1)->where('categories.active', 1)->where('protected', 0)
        ->group_by('posts.topic_id')->limit(($limit > 10 ? 10 : $limit), 0);
        if ($my_threads) {
            $this->db->where('topics.created_by', $user_id);
        }
        if ($category_slug) {
            $this->db->group_start()
            ->where('topics.category_slug', $category_slug)
            ->or_where('topics.child_category_slug', $category_slug)
            ->group_end();
        }
        $this->db->order_by('topics.created_at', 'desc');

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_categories($limit) {

        $this->db->select("name, slug")
        ->where('active', 1)->where('parent_id', 0)->where('private', 0)
        ->order_by('order_no', 'asc');

        if ($limit) {
            $this->db->limit($limit);
        }

        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function login($username, $token) {

        if ($user = $this->getUser('username', $username)) {
            if ($user->wp_login_token != $token) { return FALSE; }
            if ($user->active < 1) { return FALSE; }

            $session_data = array(
                'identity' => $user->email,
                'username' => $user->username,
                'email' => $user->email,
                'user_id' => $user->id,
                'old_last_login' => $user->last_login,
                'last_ip' => $user->last_ip_address,
                'avatar' => $user->avatar,
                'gender' => $user->gender,
                'group_id' => $user->group_id,
            );

            $this->session->set_userdata($session_data);
            $ldata = array('user_id' => $user->id, 'ip_address' => $this->input->ip_address(), 'login' => 'api');
            $this->db->insert('user_logins', $ldata);

            return TRUE;
        }
        return FALSE;
    }

    public function getLoginToken($username) {
        $token = $this->generateKey();
        if ($this->db->update('users', array('wp_login_token' => $token), array('username' => $username))) {
            return $token;
        }
        return FALSE;
    }

    public function logout($username)
    {
        $token = $this->generateKey();
        $this->db->update('users', array('wp_login_token' => $token), array('username' => $username));
        $this->session->unset_userdata(array('email' => '', 'id' => '', 'user_id' => ''));
        if (get_cookie('identity')) { delete_cookie('identity'); }
        if (get_cookie('remember_code')) { delete_cookie('remember_code'); }
        $this->session->sess_destroy();
        return TRUE;
    }

    public function deleteUser($username)
    {
        if ($this->db->delete('users', array('username' => $username))) {
            return TRUE;
        }
        return FALSE;
    }

    public function generateKey()
    {
        $this->load->helper('string');
        $key = random_string('sha1');
        $this->load->model('api_model');
        if ($this->getApiKey('key', $key)) {
            $this->generateKey();
        } else {
            return $key;
        }
    }

    public function getApiKeys() {
        $q = $this->db->get('api_keys');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getApiKey($field, $value) {
        $q = $this->db->get_where('api_keys', array($field => $value));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function addApiKey($data) {
        if($this->db->insert('api_keys', $data)) {
            return TRUE;
        }
        return FALSE;
    }

    public function updateUserApiKey($user_id, $data) {
        if($this->db->update('api_keys', $data, array('user_id' => $user_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteApiKey($id) {
        if($this->db->delete('api_keys', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

}
