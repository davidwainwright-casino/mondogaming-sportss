<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->min_pw_len = 5;
        $this->lang->load('auth', $this->Settings->language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return [$key => $value];
    }

    public function _render_page($view, $data = null, $render = false)
    {
        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html      = $this->load->view('header', $this->viewdata, $render);
        $view_html .= $this->load->view($view, $this->viewdata, $render);
        $view_html = $this->load->view('footer', $this->viewdata, $render);

        if (!$render) {
            return $view_html;
        }
    }

    public function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return true;
        }
        return false;
    }

    public function activate($id, $code = false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } elseif ($this->Admin) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            if ($this->Admin) {
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            } else {
                if ($this->Settings->mode == 1) {
                    $this->tec->pending_user_notification($id);
                }
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect('login#forgot_password');
        }
    }

    public function add_badge()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $this->form_validation->set_rules('title', lang('title'), 'required|is_unique[badges.title]');

        if ($this->form_validation->run() == true) {
            $photo = '';
            if ($_FILES['image']['size'] > 0) {
                $this->load->library('upload');

                $config['upload_path']      = 'uploads';
                $config['allowed_types']    = 'gif|jpg|png|jpeg';
                $config['max_size']         = 200;
                $config['min_width']        = 32;
                $config['min_height']       = 32;
                $config['max_width']        = 512;
                $config['max_height']       = 512;
                $config['overwrite']        = false;
                $config['max_filename']     = 25;
                $config['file_ext_tolower'] = true;
                $config['encrypt_name']     = true;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER['HTTP_REFERER'] ?? '/users/badges');
                }

                $photo = $this->upload->file_name;
            } elseif (!$this->input->post('class')) {
                $this->form_validation->set_rules('image', lang('image'), 'required');
            }
            $data = [
                'title' => $this->input->post('title'),
                'class' => $this->input->post('class'),
                'image' => $photo,
            ];
        }

        if ($this->form_validation->run() == true && $this->auth_model->addBadge($data)) {
            $this->session->set_flashdata('message', lang('badge_added'));
            redirect('users/badges');
        } else {
            $this->data['error']      = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('new_badge');
            $this->load->view($this->theme . 'auth/add_badge', $this->data);
        }
    }

    public function assign_badge($user_id = null)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $this->form_validation->set_rules('badge', lang('badge'), 'required');

        if ($this->form_validation->run() == true) {
            $data = [
                'user_id'  => $user_id,
                'badge_id' => $this->input->post('badge'),
            ];
        }

        if ($this->form_validation->run() == true && $this->auth_model->assignBadge($data)) {
            $this->session->set_flashdata('message', lang('badge_assigned'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        } else {
            $this->data['error']      = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['user_id']    = $user_id;
            $this->data['page_title'] = lang('select_badge');
            $this->data['badges']     = $this->settings_model->getAllBadges();
            $this->load->view($this->theme . 'auth/assign_badge', $this->data);
        }
    }

    /* ---------------- BADGES -------------- */

    public function badges()
    {
        $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['badges']     = $this->settings_model->getAllBadges();
        $this->data['page_title'] = lang('badges');
        $this->page_construct('auth/badges', $this->data);
    }

    public function change_password()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[' . $this->min_pw_len . ']|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/profile/' . $user->username);
        } else {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }

            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('users/profile/' . $user->username);
            }
        }
    }

    public function create_user()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        $this->data['title'] = lang('add_user');
        $this->form_validation->set_message('is_unique', lang('is_unique'));
        $this->form_validation->set_rules('username', lang('username'), 'trim|is_unique[users.username]|alpha_dash');
        $this->form_validation->set_rules('email', lang('email'), 'trim|is_unique[users.email]');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email    = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $notify   = $this->input->post('notify');

            $additional_data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
                'gender'     => $this->input->post('gender'),
                'dob'        => $this->input->post('dob') ? $this->input->post('dob') : null,
                'group_id'   => $this->input->post('group') ? $this->input->post('group') : '3',
            ];
            $active = $this->input->post('status');
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('users');
        } else {
            $this->data['error']      = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups']     = $this->ion_auth->groups()->result_array();
            $this->data['page_title'] = lang('add_user');
            $this->page_construct('auth/create_user', $this->data);
        }
    }

    public function deactivate($id = null)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;
        $this->form_validation->set_rules('confirm', lang('confirm'), 'required');

        if ($this->form_validation->run() == false) {
            if ($this->input->post('deactivate')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            } else {
                $this->data['csrf']     = $this->_get_csrf_nonce();
                $this->data['user']     = $this->ion_auth->user($id)->row();
                $this->data['modal_js'] = $this->site->modal_js();
                $this->load->view($this->theme . 'auth/deactivate_user', $this->data);
            }
        } else {
            if ($this->input->post('confirm') == 'yes') {
                if ($id != $this->input->post('id')) {
                    show_error(lang('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->Admin) {
                    $this->ion_auth->deactivate($id);
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                }
            }

            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    public function delete($id = null)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if (!$this->Admin || $id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->delete_user($id)) {
            $this->session->set_flashdata('message', lang('user_deleted'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    public function delete_avatar($id = null)
    {
        $user = $this->ion_auth->user($id)->row();
        if (!$this->loggedIn || !$this->Admin && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('users/profile/' . $user->username);
        } else {
            unlink('uploads/avatars/' . $user->avatar);
            unlink('uploads/avatars/thumbs/' . $user->avatar);
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('avatar');
            }
            $this->db->update('users', ['avatar' => null], ['id' => $id]);
            $this->session->set_flashdata('message', lang('avatar_deleted'));
            redirect('users/profile/' . $user->username);
        }
    }

    public function delete_badge($id = null)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if ($this->auth_model->deleteBadge($id)) {
            $this->session->set_flashdata('message', lang('badge_deleted'));
            redirect('users/badges');
        }
    }

    public function detach_badge($id = null)
    {
        if (!$this->Admin || !$id) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if ($this->auth_model->deleteUserBadge($id)) {
            echo lang('badge_detached');
            exit();
        }
        echo lang('access_denied');
        exit();
    }

    public function edit_badge($id = null)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $badge = $this->auth_model->getBadgeByID($id);
        $this->form_validation->set_rules('title', lang('title'), 'required');
        if ($badge->title != $this->input->post('title')) {
            $this->form_validation->set_rules('title', lang('title'), 'is_unique[badges.title]');
        }

        if ($this->form_validation->run() == true) {
            $photo = '';
            if ($_FILES['image']['size'] > 0) {
                $this->load->library('upload');

                $config['upload_path']      = 'uploads';
                $config['allowed_types']    = 'gif|jpg|png|jpeg';
                $config['max_size']         = 200;
                $config['min_width']        = 32;
                $config['min_height']       = 32;
                $config['max_width']        = 512;
                $config['max_height']       = 512;
                $config['overwrite']        = false;
                $config['max_filename']     = 25;
                $config['file_ext_tolower'] = true;
                $config['encrypt_name']     = true;

                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER['HTTP_REFERER'] ?? '/users/badges');
                }

                $photo = $this->upload->file_name;
            } elseif (!$this->input->post('class')) {
                $this->form_validation->set_rules('image', lang('image'), 'required');
            }
            $data = [
                'title' => $this->input->post('title'),
                'class' => $this->input->post('class'),
                'image' => $photo,
            ];
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateBadge($id, $data)) {
            $this->session->set_flashdata('message', lang('badge_updatedd'));
            redirect('users/badges');
        } else {
            $this->data['error']      = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('edit_badge');
            $this->data['badge']      = $badge;
            $this->load->view($this->theme . 'auth/edit_badge', $this->data);
        }
    }

    public function edit_user($id = null)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang('edit_user');
        $this->form_validation->set_message('is_unique', lang('is_unique'));
        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
        $this->form_validation->set_rules('username', lang('username'), 'required|alpha_dash');
        $this->form_validation->set_rules('email', lang('email'), 'required|trim|valid_email');

        if (!$this->loggedIn || !$this->Admin && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        $user = $this->ion_auth->user($id)->row();

        if ($user->username != $this->input->post('username')) {
            $this->form_validation->set_rules('username', lang('username'), 'trim|is_unique[users.username]');
        }
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', lang('email'), 'trim|is_unique[users.email]');
        }

        if ($this->form_validation->run() === true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
            $sig       = $this->input->post('signature') ? $this->input->post('signature', true) : null;
            $signature = $sig ? $this->tec->encode_html($this->tec->body_censor($sig)) : null;

            if ($this->Admin && $id != $this->session->userdata('user_id')) {
                $data = [
                    'first_name'      => $this->input->post('first_name'),
                    'last_name'       => $this->input->post('last_name'),
                    'username'        => $this->input->post('username'),
                    'email'           => $this->input->post('email'),
                    'phone'           => $this->input->post('phone'),
                    'dob'             => $this->input->post('dob'),
                    'gender'          => $this->input->post('gender'),
                    'active'          => $this->input->post('status'),
                    'group_id'        => $this->input->post('group'),
                    'accept_messages' => $this->input->post('accept_messages'),
                    'subscription'    => $this->input->post('subscription'),
                    'signature'       => $signature,
                ];
            } else {
                $data = [
                    'first_name'      => $this->input->post('first_name'),
                    'last_name'       => $this->input->post('last_name'),
                    'phone'           => $this->input->post('phone'),
                    'gender'          => $this->input->post('gender'),
                    'dob'             => $this->input->post('dob'),
                    'username'        => $this->input->post('username'),
                    'email'           => $this->input->post('email'),
                    'accept_messages' => $this->input->post('accept_messages'),
                    'subscription'    => $this->input->post('subscription'),
                    'signature'       => $signature,
                ];
                $this->session->set_userdata('username', $this->input->post('username'));
            }

            if ($this->Admin) {
                if ($this->input->post('password')) {
                    $this->form_validation->set_rules('password', lang('password'), 'required|min_length[' . $this->min_pw_len . ']|max_length[25]|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');

                    $data['password'] = $this->input->post('password');
                }
            }
            //$this->sma->print_arrays($data);
        }
        if ($this->form_validation->run() === true && $this->ion_auth->update($user->id, $data)) {
            $this->session->set_flashdata('message', lang('user_updated'));
            if (isset($data['active']) && $user->active != $data['active']) {
                $status = $data['active'];
                if ($status == 1) {
                    $active = lang('active');
                } elseif ($status == -2 || $status == 0) {
                    $active = lang('inactive');
                } elseif ($status == -1) {
                    $active = lang('pending');
                } elseif ($status == -3) {
                    $active = lang('banned');
                }

                $this->load->library('parser');
                $parse_data = [
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'site_link'  => site_url(),
                    'site_name'  => $this->Settings->site_name,
                    'email'      => $user->email,
                    'status'     => $active,
                    'logo'       => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                ];

                $msg     = file_get_contents('./themes/' . $this->theme . 'emails/account_status.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = $this->lang->line('user_status_changed') . ' - ' . $this->Settings->site_name;

                try {
                    $this->tec->send_email($user->email, $subject, $message);
                } catch (Exception $e) {
                    $this->set_error($e->getMessage());
                }
            }
            $user = $this->ion_auth->user($id)->row();
            redirect('users/profile/' . $user->username);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    public function forgot_password()
    {
        $this->form_validation->set_rules('forgot_email', lang('email_address'), 'required|trim|valid_email');

        if ($this->form_validation->run() == false) {
            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);
            redirect('login#forgot_password');
        } else {
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('forgot_email')))->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect('login#forgot_password');
            }

            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if ($forgotten) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('login#forgot_password');
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('login#forgot_password');
            }
        }
    }

    public function forgot_password_modal()
    {
        if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            $this->session->set_flashdata('message', $this->session->flashdata('message'));
            redirect('/');
        }

        $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['message']    = $this->session->flashdata('message');
        $this->data['page_title'] = lang('forgot_password');
        $this->load->view($this->theme . 'auth/forgot_password_modal', $this->data);
    }

    public function generate_api_key()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        if ($this->Settings->apis != 2) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $user_id = $this->session->userdata('user_id');
        $this->load->model('api_model');
        $data = [
            'reference'     => 'Key for ' . $this->session->userdata('username'),
            'user_id'       => $user_id,
            'key'           => $this->api_model->generateKey(),
            'level'         => 1,
            'ignore_limits' => 500,
            'ip_addresses'  => null,
            'date_created'  => time(),
        ];

        if ($this->api_model->getApiKey('user_id', $user_id)) {
            $this->api_model->updateUserApiKey($user_id, $data);
        } else {
            $this->api_model->addApiKey($data);
        }

        $this->session->set_flashdata('message', lang('api_key_generated'));
        redirect('users/profile/' . $this->session->userdata('username') . '#api_key');
    }

    public function index()
    {
        redirect('/');
    }

    public function login($m = null)
    {
        if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            $this->session->set_flashdata('message', $this->session->flashdata('message'));
            redirect('/');
        }
        if ($this->Settings->wp_login && !$this->input->get('wp_checked')) {
            header('Location: ' . site_url('wp_login') . '?return_url=' . site_url('login') . '?wp_checked=1');
            exit();
        }
        if ($this->Settings->captcha) {
            $this->form_validation->set_rules(
                'captcha',
                lang('captcha'),
                ['required', ['captcha_check',
                    function ($val) {
                        return $this->settings_model->captcha_check($val);
                    },
                ]]
            );
        }

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                if ($this->Settings->mode == 2) {
                    if (!$this->ion_auth->in_group('admin')) {
                        $this->ion_auth->logout();
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        redirect('login');
                    }
                }

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : '/';
                redirect($referrer);
            } else {
                $error = $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error');
                $this->session->set_flashdata('error', $error);
                redirect('login');
            }
        } else {
            if ($this->Settings->captcha) {
                $this->data['image'] = $this->tec->create_captcha();
            }
            $this->data['min_pw_len'] = $this->min_pw_len;
            $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            if ($m) {
                $this->data['message'] = lang($m);
            } else {
                $this->data['message'] = $this->session->flashdata('message');
            }
            $this->data['page_title'] = lang('login');
            $this->load->view($this->theme . 'auth/login', $this->data);
        }
    }

    public function login_modal()
    {
        if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            $this->session->set_flashdata('message', $this->session->flashdata('message'));
            redirect('/');
        }

        if ($this->Settings->captcha) {
            $this->data['image'] = $this->tec->create_captcha();
        }
        $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['message']    = $this->session->flashdata('message');
        $this->data['page_title'] = lang('login');
        $this->load->view($this->theme . 'auth/login_modal', $this->data);
    }

    public function logout($m = null)
    {
        $this->settings_model->updateUserLastActivity($this->session->userdata('user_id'), true);
        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        if ($this->session->flashdata('error')) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
        }
        if ($this->Settings->wp_login && $this->session->userdata('gravatar')) {
            redirect('wp_login?logout=1');
        }
        if ($this->input->get('return_url')) {
            header('Location: ' . $this->input->get('return_url', true));
            exit();
        }
        if ($m) {
            redirect('login/' . $m);
        } else {
            redirect('/');
        }
    }

    public function profile($username = null)
    {
        if (!$username || empty($username)) {
            redirect('auth');
        }

        $user                       = $this->settings_model->getUserByUsername($username);
        $this->data['user']         = $user;
        $this->data['group']        = $this->settings_model->getUserGroup($user->id);
        $this->data['badges']       = $this->settings_model->getUserBadges($user->id);
        $this->data['user_threads'] = $this->settings_model->getUserTopics($user->id);
        $this->data['user_replies'] = $this->settings_model->getUserPosts($user->id);

        if ($this->loggedIn || $this->Admin) {
            $this->data['api_key'] = $this->auth_model->getApiKey($user->id);
            $this->data['groups']  = $this->auth_model->getAllGroups();
            $this->data['csrf']    = $this->_get_csrf_nonce();
        }

        $this->data['min_pw_len'] = $this->min_pw_len;
        $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('profile');
        $this->page_construct('auth/profile', $this->data);
    }

    public function register()
    {
        if ($this->Settings->mode != 2 && $this->Settings->registration) {
            $this->form_validation->set_message('is_unique', lang('is_unique'));
            $this->form_validation->set_rules('full_name', lang('full_name'), 'required');
            $this->form_validation->set_rules('dob', lang('dob'), 'required');
            $this->form_validation->set_rules('username', lang('username'), 'required|is_unique[users.username]|alpha_dash');
            $this->form_validation->set_rules('email', lang('email'), 'required|is_unique[users.email]|trim|valid_email');
            $this->form_validation->set_rules('password', lang('password'), 'required|min_length[' . $this->min_pw_len . ']|max_length[25]');
            $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'required|matches[password]');
            if ($this->Settings->captcha) {
                $this->form_validation->set_rules(
                    'captcha',
                    lang('captcha'),
                    ['required', ['captcha_check',
                        function ($val) {
                            return $this->settings_model->captcha_check($val);
                        },
                    ]]
                );
            }

            if ($this->form_validation->run() == true) {
                $username                     = strtolower($this->input->post('username'));
                $email                        = strtolower($this->input->post('email'));
                $password                     = $this->input->post('password');
                list($first_name, $last_name) = explode(' ', $this->input->post('full_name'), 2);

                $additional_data = [
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'gender'     => $this->input->post('gender'),
                    'dob'        => $this->input->post('dob'),
                    'group_id'   => 3,
                ];
            }
            if ($this->form_validation->run() == true && $id = $this->ion_auth->register($username, $password, $email, $additional_data)) {
                $this->session->set_flashdata('message', is_int($id) ? lang('account_registed') : ($id ? lang('account_registed_x_email') : 'account_registed_unknown'));
                redirect('login');
            } else {
                $this->session->set_flashdata('message', $this->session->flashdata('message'));
                $this->session->set_flashdata('full_name', $this->input->post('full_name'));
                $this->session->set_flashdata('dob', $this->input->post('dob'));
                $this->session->set_flashdata('email', $this->input->post('email'));
                $this->session->set_flashdata('username', $this->input->post('username'));
                $this->session->set_flashdata('gender', $this->input->post('gender'));
                $this->session->set_flashdata('error', validation_errors());
                redirect('login#register');
            }
        } else {
            $this->session->set_flashdata('error', lang('registration_is_closed'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    public function register_modal()
    {
        if ($this->loggedIn) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
            $this->session->set_flashdata('message', $this->session->flashdata('message'));
            redirect('/');
        }

        if ($this->Settings->captcha) {
            $this->data['image'] = $this->tec->create_captcha();
        }
        $this->data['min_pw_len'] = $this->min_pw_len;
        $this->data['error']      = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['message']    = $this->session->flashdata('message');
        $this->data['page_title'] = lang('register');
        $this->load->view($this->theme . 'auth/register_modal', $this->data);
    }

    public function reload_captcha()
    {
        echo $this->tec->create_captcha();
    }

    public function resend_activation($id)
    {
        $res = $this->ion_auth->regenrate_activation($id);
        if ($res == 2) {
            $this->session->set_flashdata('message', lang('activation_email_sent'));
        } elseif ($res == 1) {
            $this->session->set_flashdata('error', lang('action_failed_user_already_active'));
        } elseif ($res == 3) {
            $this->session->set_flashdata('error', lang('action_failed_email_x_sent'));
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function reset_password($code = null)
    {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            $this->form_validation->set_rules('new', lang('password'), 'required|min_length[' . $this->min_pw_len . ']|max_length[25]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', lang('confirm_password'), 'required');

            if ($this->form_validation->run() == false) {
                $this->data['error']               = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->data['message']             = $this->session->flashdata('message');
                $this->data['min_password_length'] = 8;
                $this->data['new_password']        = [
                    'name'        => 'new',
                    'id'          => 'new',
                    'type'        => 'password',
                    'class'       => 'form-control',
                    'pattern'     => '^.{' . $this->min_pw_len . '}.*$',
                    'placeholder' => lang('new_password'),
                ];
                $this->data['new_password_confirm'] = [
                    'name'        => 'new_confirm',
                    'id'          => 'new_confirm',
                    'type'        => 'password',
                    'class'       => 'form-control',
                    'pattern'     => '^.{' . $this->min_pw_len . '}.*$',
                    'placeholder' => lang('confirm_password'),
                ];
                $this->data['user_id'] = [
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                ];
                $this->data['csrf']           = $this->_get_csrf_nonce();
                $this->data['code']           = $code;
                $this->data['identity_label'] = $user->email;
                $this->data['page_title']     = lang('reset_password');
                $this->load->view($this->theme . 'auth/reset_password', $this->data);
            } else {
                if ($user->id != $this->input->post('user_id')) {
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error(lang('error_csrf'));
                } else {
                    $identity = $user->email;

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect('login');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        redirect('reset_password/' . $code);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect('login#forgot_password');
        }
    }

    public function update_avatar($id = null)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        if (!$this->loggedIn || !$this->Admin && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        $user = $this->ion_auth->user($id)->row();
        $this->form_validation->set_rules('avatar', lang('avatar'), 'trim');

        if ($this->form_validation->run() == true) {
            if ($_FILES['avatar']['size'] > 0) {
                $this->load->library('upload');

                $config['upload_path']      = 'uploads/avatars';
                $config['allowed_types']    = 'gif|jpg|png|jpeg';
                $config['max_size']         = 500;
                $config['min_width']        = 80;
                $config['min_height']       = 80;
                $config['max_width']        = 800;
                $config['max_height']       = 800;
                $config['overwrite']        = false;
                $config['max_filename']     = 25;
                $config['file_ext_tolower'] = true;
                $config['encrypt_name']     = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER['HTTP_REFERER'] ?? '/');
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library']  = 'gd2';
                $config['source_image']   = 'uploads/avatars/' . $photo;
                $config['new_image']      = 'uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = true;
                $config['width']          = 80;
                $config['height']         = 80;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
            } else {
                $this->form_validation->set_rules('avatar', lang('avatar'), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatar($id, $photo)) {
            unlink('uploads/avatars/' . $user->avatar);
            unlink('uploads/avatars/thumbs/' . $user->avatar);
            $this->session->set_userdata('avatar', $photo);
            $this->session->set_flashdata('message', lang('avatar_updated'));
            redirect('users/profile/' . $user->username);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect('users/profile/' . $user->username);
        }
    }

    public function users()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        $page   = $this->input->get('page') ? $this->input->get('page') : 0;
        $active = null;
        $query  = $this->input->get('uquery');
        if ($status = $this->input->get('status')) {
            if ($status == 'active') {
                $active = 1;
            } elseif ($status == 'inactive') {
                $active = -2;
            } elseif ($status == 'pending') {
                $active = -1;
            } elseif ($status == 'banned') {
                $active = -3;
            }
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total                    = $this->auth_model->total_users($active, $query);
        $this->data['links']      = pagination('users', $total, $limit);
        $start                    = $page ? ($page * $limit) - $limit : 0;
        $this->data['records']    = ['total' => $total, 'from' => $start, 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['users']      = $this->auth_model->get_users($limit, $start, $active, $query);
        $this->data['page_title'] = lang('users');
        $this->page_construct('auth/index', $this->data);
    }
}
