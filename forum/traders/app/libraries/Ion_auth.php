<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ion_auth
{
    public $_cache_user_in_group;

    public $_extra_set = [];

    public $_extra_where = [];

    protected $status;

    public function __construct()
    {
        $this->load->config('ion_auth', true);

        // Load IonAuth MongoDB model if it's set to use MongoDB,
        $this->load->model('auth_model');

        $this->_cache_user_in_group = &$this->auth_model->_cache_user_in_group;

        //auto-login the user if they are remembered
        if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code')) {
            if ($this->auth_model->login_remembered_user()) {
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }

        $this->auth_model->trigger_events('library_constructor');
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->auth_model, $method)) {
            throw new Exception('Undefined method Ion_auth::' . $method . '() called');
        }

        return call_user_func_array([$this->auth_model, $method], $arguments);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function forgotten_password($identity)
    {    //changed $email to $identity
        if ($this->auth_model->forgotten_password($identity)) {   //changed
            // Get user information
            $user = $this->where('email', $identity)->where('active', 1)->users()->row();  //changed to get_user_by_identity from email

            if ($user) {
                $data = [
                    'identity'                => $user->{$this->config->item('identity', 'ion_auth')},
                    'forgotten_password_code' => $user->forgotten_password_code,
                ];

                if (!$this->config->item('use_ci_email', 'ion_auth')) {
                    $this->set_message('forgot_password_successful');
                    return $data;
                }

                $this->load->library('parser');
                $parse_data = [
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'email'      => $user->email,
                    'reset_link' => site_url('reset_password/' . $user->forgotten_password_code),
                    'site_link'  => base_url(),
                    'site_name'  => $this->Settings->site_name,
                    'logo'       => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                ];
                $msg     = file_get_contents('./themes/' . $this->theme . 'emails/forgot_password.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = lang('email_forgotten_password_subject') . ' - ' . $this->Settings->site_name;

                $result = false;
                try {
                    if ($result = $this->tec->send_email($user->email, $subject, $message)) {
                        $this->set_message('forgot_password_successful');
                    } else {
                        $this->set_error('unable_to_send_email');
                    }
                } catch (\Exception $e) {
                    $this->set_error($e->getMessage());
                    log_message('error', $e->getMessage());
                }
                return $result;
            }
            $this->set_error('forgot_password_unsuccessful');
            $this->set_error('user_not_active');
            return false;
        }
        $this->set_error('forgot_password_unsuccessful');
        return false;
    }

    public function forgotten_password_check($code)
    {
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!is_object($profile)) {
            $this->set_error('password_change_unsuccessful');
            return false;
        }
        if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
            //Make sure it isn't expired
            $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
            if (time() - $profile->forgotten_password_time > $expiration) {
                //it has expired
                $this->clear_forgotten_password_code($code);
                $this->set_error('password_change_unsuccessful');
                return false;
            }
        }
        return $profile;
    }

    public function forgotten_password_complete($code)
    {
        $this->auth_model->trigger_events('pre_password_change');

        $identity = $this->config->item('identity', 'ion_auth');
        $profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!$profile) {
            $this->auth_model->trigger_events(['post_password_change', 'password_change_unsuccessful']);
            $this->set_error('password_change_unsuccessful');
            return false;
        }

        $new_password = $this->auth_model->forgotten_password_complete($code, $profile->salt);

        if ($new_password) {
            $data = [
                'identity'     => $profile->{$identity},
                'new_password' => $new_password,
            ];
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->set_message('password_change_successful');
                $this->auth_model->trigger_events(['post_password_change', 'password_change_successful']);
                return $data;
            }
            $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

            $this->email->clear();
            //$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
            $this->email->from($this->admin_email, SITE_NAME);
            $this->email->to($profile->email);
            $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
            $this->email->message($message);

            if ($this->email->send()) {
                $this->set_message('password_change_successful');
                $this->auth_model->trigger_events(['post_password_change', 'password_change_successful']);
                return true;
            }
            $this->set_error('password_change_unsuccessful');
            $this->auth_model->trigger_events(['post_password_change', 'password_change_unsuccessful']);
            return false;
        }

        $this->auth_model->trigger_events(['post_password_change', 'password_change_unsuccessful']);
        return false;
    }

    public function get_user_id()
    {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {
            return $user_id;
        }
        return null;
    }

    public function getUserGroup($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $group_id = $this->getUserGroupID($user_id);
        return $this->ion_auth->group($group_id)->row();
    }

    public function getUserGroupID($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $user = $this->ion_auth->user($user_id)->row();
        return $user->group_id;
    }

    public function in_group($check_group, $id = false)
    {
        $this->auth_model->trigger_events('in_group');

        $id || $id = $this->session->userdata('user_id');

        $group = $this->getUserGroup($id);

        if ($group->name === $check_group) {
            return true;
        }

        return false;
    }

    public function logged_in()
    {
        $this->auth_model->trigger_events('logged_in');

        return (bool) $this->session->userdata('identity');
    }

    public function logout()
    {
        $this->auth_model->trigger_events('logout');

        if ($this->Settings->mode) {
            if (!$this->ion_auth->in_group('admin')) {
                $this->set_message('site_is_offline_plz_try_later');
            } else {
                $this->set_message('logout_successful');
            }
        }

        $identity = $this->config->item('identity', 'ion_auth');
        $this->session->unset_userdata([$identity => '', 'id' => '', 'user_id' => '']);

        //delete the remember me cookies if they exist
        if (get_cookie('identity')) {
            delete_cookie('identity');
        }
        if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        //Destroy the session
        $this->session->sess_destroy();

        return true;
    }

    public function regenrate_activation($id)
    {
        $activation_code = $this->auth_model->activation_code;
        $identity        = $this->config->item('identity', 'ion_auth');
        $user            = $this->auth_model->user($id)->row();
        if ($user->active > 0) {
            $this->session->set_flashdata('error', lang('user_already_active'));
            return 1;
        }
        $activation_code = $this->auth_model->regenrate_code($id);

        $data = [
            'identity'   => $user->{$identity},
            'id'         => $user->id,
            'email'      => $user->email,
            'activation' => $activation_code,
        ];

        $this->load->library('parser');
        $parse_data = [
            'first_name'      => $user->first_name,
            'last_name'       => $user->last_name,
            'site_link'       => site_url(),
            'site_name'       => $this->Settings->site_name,
            'email'           => $user->email,
            'activation_link' => site_url('activate/' . $id . '/' . $activation_code),
            'logo'            => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
        ];

        $msg     = file_get_contents('./themes/' . $this->theme . 'emails/activate_email.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('email_activation_subject') . ' - ' . $this->Settings->site_name;

        $result = 3;
        try {
            if ($result = $this->tec->send_email($user->email, $subject, $message)) {
                $this->set_message('activation_email_successful');
                $result = 2;
            }
        } catch (\Exception $e) {
            $this->set_error($e->getMessage());
            log_message('error', $e->getMessage());
        }
        $this->session->set_flashdata('error', lang('unable_to_send_activate_email'));
        return $result;
    }

    public function register($username, $password, $email, $additional_data = [], $active = false, $notify = false)
    {
        $this->auth_model->trigger_events('pre_account_creation');
        $email_activation = $this->config->item('email_activation', 'ion_auth');

        if (!$email_activation || $active == '1') {
            $id = $this->auth_model->register($username, $password, $email, $additional_data, $active);
            if ($id !== false) {
                if ($notify) {
                    $this->load->library('parser');
                    $parse_data = [
                        'first_name' => $additional_data['first_name'],
                        'last_name'  => $additional_data['last_name'],
                        'site_link'  => site_url(),
                        'site_name'  => $this->Settings->site_name,
                        'email'      => $email,
                        'password'   => $password,
                        'username'   => $username,
                        'logo'       => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                    ];

                    $msg     = file_get_contents('./themes/' . $this->theme . 'emails/credentials.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = $this->lang->line('new_user_created') . ' - ' . $this->Settings->site_name;

                    try {
                        $this->tec->send_email($email, $subject, $message);
                    } catch (\Exception $e) {
                        $this->set_error($e->getMessage());
                        log_message('error', $e->getMessage());
                    }
                }

                $this->set_message('account_creation_successful');
                $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful']);
                return $id;
            }
            $this->set_error('account_creation_unsuccessful');
            $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful']);
            return false;
        }
        $id = $this->auth_model->register($username, $password, $email, $additional_data, $active);

        if (!$id) {
            $this->set_error('account_creation_unsuccessful');
            return false;
        }
        $this->set_message('account_creation_successful');

        $deactivate = $this->auth_model->deactivate($id, false);

        if (!$deactivate) {
            // $this->set_error('deactivate_unsuccessful');
            $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful']);
            return false;
        }

        $activation_code = $this->auth_model->activation_code;
        $identity        = $this->config->item('identity', 'ion_auth');
        $user            = $this->auth_model->user($id)->row();

        $data = [
            'identity'   => $user->{$identity},
            'id'         => $user->id,
            'email'      => $email,
            'activation' => $activation_code,
        ];
        if (!$this->config->item('use_ci_email', 'ion_auth')) {
            $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
            $this->set_message('activation_email_successful');
            return $data;
        }

        $this->load->library('parser');
        $parse_data = [
            'first_name'      => $additional_data['first_name'],
            'last_name'       => $additional_data['last_name'],
            'site_link'       => site_url(),
            'site_name'       => $this->Settings->site_name,
            'email'           => $email,
            'activation_link' => site_url('activate/' . $data['id'] . '/' . $data['activation']),
            'logo'            => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
        ];

        $msg     = file_get_contents('./themes/' . $this->theme . 'emails/activate_email.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        $subject = $this->lang->line('email_activation_subject') . ' - ' . $this->Settings->site_name;
        $result  = true;
        try {
            if ($this->tec->send_email($email, $subject, $message)) {
                $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
                $this->set_message('activation_email_successful');
                $result = $id;
            } else {
                $this->set_error('activation_email_unsuccessful');
            }
        } catch (\Exception $e) {
            $this->set_error($e->getMessage());
            log_message('error', $e->getMessage());
        }
        $this->auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful']);
        return $result;
    }
}
