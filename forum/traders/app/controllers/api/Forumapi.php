<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Forumapi extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api_model');
        $this->Settings = $this->api_model->getSettings();
        $key = isset($this->_args['SF-API-KEY']) ? $this->_args['SF-API-KEY'] : $this->input->server('HTTP_SF_API_KEY');
        $this->key_data = $this->api_model->getApiKey('key', $key);
        if (!$this->Settings->apis) {
            $this->response(['status' => FALSE, 'message' => 'Api feature is disabled'], 404);
        } elseif($this->Settings->apis == 1) {
            $this->user = $this->api_model->getUser('id', $this->key_data->user_id);
            if ($this->user->group_id > 1)  {
                $this->response(['status' => FALSE, 'message' => 'Api feature is disabled for users'], 404);
            }
        }
        $this->methods = [
            'threads_get' => ['level' => 1, 'limit' => 1000],
            'login_get' => ['level' => 2, 'limit' => 500],
            'logout_get' => ['level' => 2, 'limit' => 500],
            'login_post' => ['level' => 2, 'limit' => 500],
            'user_post' => ['level' => 2, 'limit' => 20],
            'user_delete' => ['level' => 2, 'limit' => 20],
        ];
    }

    public function threads_get()
    {

        $my_threads = $this->get('mine');
        $category_slug = $this->get('category');
        $limit = $this->get('count') ? $this->get('count') : 5;
        $user_id = $this->key_data->user_id;

        $topics = $this->api_model->get_topics($limit, $my_threads, $user_id, $category_slug);

        foreach ($topics as $topic) {
            $topic['thread_url'] = site_url($topic['category_slug'].'/'.$topic['slug']);
            $topic['avatar_url'] = base_url('uploads/avatars/thumbs/'.(!empty($topic['avatar']) ? $topic['avatar'] : 'default.png'));
            $topic['replies'] = ($topic['replies'] - 1);
            unset($topic['slug'], $topic['avatar'], $topic['category_slug']);
            ksort($topic);
            $data[] = $topic;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(['status' => FALSE, 'message' => lang('no_threads_found')], 404);
        }

    }

    public function categories_get()
    {

        $limit = $this->get('count') ? $this->get('count') : NULL;
        $categories = $this->api_model->get_categories($limit);

        foreach ($categories as $category) {
            $topic['name'] = $category['name'];
            $topic['slug'] = $category['slug'];
            $topic['url'] = site_url($category['slug']);
            $data[] = $topic;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(['status' => FALSE, 'message' => lang('no_threads_found')], 404);
        }

    }

    public function logout_get()
    {
        $username = $this->session->userdata('username');
        $this->api_model->logout($username);
        $this->response(['status' => TRUE, 'message' => "Logged out as {$username}"], 200);
    }

    public function login_get()
    {
        if ($this->get('username')) {
            if ($token = $this->api_model->getLoginToken($this->get('username'))) {
                $this->response(['status' => TRUE, 'message' => "Login token generated for {$this->get('username')}", 'token' => $token], 200);
            }
        }
        $this->response(['status' => FALSE, 'message' => 'User not found'], 404);
    }

    public function login_post()
    {
        if ($this->post('username')) {
            if ($this->api_model->login($this->post('username'), $this->post('token'))) {
                $this->response(['status' => TRUE, 'message' => "Logged in as {$this->session->userdata('username')}"], 200);
            }
        }
        $this->response(['status' => FALSE, 'message' => 'Invalid token, please refresh page and login'], 200);
    }

    public function user_post()
    {
        if (!empty($this->post('username')) && !empty($this->post('email')) && !empty($this->post('full_name'))) {

            $username = strtolower($this->post('username'));
            $email = strtolower($this->post('email'));

            if ($this->api_model->getUser('username', $username)) {
                $this->response(['status' => FALSE, 'message' => 'Username already exist'], 200);
            }
            if ($this->api_model->getUser('email', $email)) {
                $this->response(['status' => FALSE, 'message' => 'Email already exist'], 200);
            }

            $password = !empty($this->post('password')) ? $this->post('password') : rand(10000000, 99999999);
            list($first_name, $last_name) = explode(' ', $this->post('full_name'), 2);
            $active = true;

            $additional_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'gender' => $this->post('gender') ? $this->post('gender') : 'male',
                'dob' => $this->post('dob') ? $this->post('dob') : NULL,
                );

            $additional_data['group_id'] = (!empty($this->post('admin')) ? 1 : (!empty($this->post('mod')) ? 2 : 3));

            $this->load->library('ion_auth');
            $this->ion_auth->register($username, $password, $email, $additional_data, $active);

            $this->response(['status' => TRUE, 'message' => 'User account successfully created'], 201);
        }

        $this->response(['status' => FALSE, 'message' => 'username, email, and full_name are required'], 404);

    }

    public function user_delete()
    {
        $username = $this->query('username');

        if (empty($username)) {
            $this->response(NULL, 404);
        }

        $res = $this->api_model->deleteUser($username);
        if ($res) {
            $this->response(['status' => TRUE, 'message' => 'User successfully deleted'], 200);
        } else {
            $this->response(['status' => FALSE, 'message' => 'User not found'], 404);
        }

    }

}
