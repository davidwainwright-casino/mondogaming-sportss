<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax_calls extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('ajax_model');
    }

    public function get_user_details($id = null)
    {
        if ($user = $this->settings_model->getUser($id)) {
            $user_group  = $this->settings_model->getUserGroup($id);
            $user_topics = $this->settings_model->getUserTopics($id);
            $user_posts  = $this->settings_model->getUserPosts($id);
            $badges      = $this->settings_model->getUserBadges($user->id);

            echo '<div class="circle"><img src="' . ($user->avatar ? base_url('uploads/avatars/thumbs/' . $user->avatar) : base_url() . 'themes/' . $this->Settings->theme . '/assets/img/' . $user->gender . '.png') . '" alt="" class="img-rounded img-responsive"></div>

            <hgroup>
            <h2><a href="' . site_url('users/profile/' . $user->username) . '" class="skip">' . $user->first_name . ' ' . $user->last_name . '</a></h2>
            <span class="label label-info">' . lang($user_group->name) . '</span><br><br>
            <span class="label block">' . lang('member_since') . ': ' . $this->tec->hrsd(date('Y-m-d H:i:s', $user->created_on)) . '</span>
            <span class="label block">' . lang('last_visit') . ': ' . $this->tec->hrld(date('Y-m-d H:i:s', $user->last_login)) . '</span>
            </hgroup>';

            if ($badges) {
                echo '<ul class="awards-box" style="display:flex;align-items:center;">';
                foreach ($badges as $badge) {
                    if (!empty($badge->class)) {
                        echo "<li style='display:flex;align-items:center;'><i class='{$badge->class} btip' title='{$badge->title}'></i></li>";
                    } else {
                        echo !empty($badge->image) ? '<li><img src="' . base_url('uploads/' . $badge->image) . '" alt="' . $badge->title . '" style="max-width:24px;max-height:24px;" class="btip" title="' . $badge->title . '" /></li>' : '';
                    }
                }
                echo '</ul>';
            }
            echo '<h4><a href="' . site_url('user_topics/' . $user->id) . '" class="skip">' . lang('threads') . ' <span> ' . $user_topics . '</span></a>  &nbsp;-&nbsp;  <a>' . lang('replies') . ' <span> ' . $user_posts . '</span></a></h4>';
            if ($this->loggedIn && $user->id != $this->session->userdata('user_id')) {
                echo '<div class="text-center mt"><a href="' . site_url('messages/send/' . $user->id) . '" data-toggle="ajax-modal" class="btn btn-primary btn-block">' . lang('send_message') . '</a></div>';
            }
            echo '
            <script>$(document).ready(function(){ $(".btip").tooltip(); $(\'[data-toggle="ajax-modal"]\').click(function (e) { e.preventDefault(); $.get($(this).attr(\'href\')).done(function(data) { $(\'#myModal\').html(data).modal({backdrop: \'static\'}); }); });});</script>
            ';
        } else {
            echo '<h4>' . lang('user_x_found') . '</h4>';
        }
    }

    public function index()
    {
        redirect('/');
    }

    public function post()
    {
        if ($this->Settings->guest_reply) {
            $this->form_validation->set_rules('body', lang('body'), 'required|trim|min_length[2]');
            $this->form_validation->set_rules('name', lang('name'), 'required|min_length[2]');
            $this->form_validation->set_rules('email', lang('email'), 'required|trim|valid_email');
            if ($this->Settings->captcha == 2 || ($this->Settings->guest_reply && !$this->loggedIn)) {
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
                $topic_id   = $this->input->get('topic', true);
                $created_at = date('Y-m-d H:i:s');
                $body       = $this->tec->body_censor($this->input->post('body', true));
                $post       = [
                    'created_by'  => 0,
                    'created_at'  => $created_at,
                    'topic_id'    => $topic_id,
                    'body'        => $this->tec->encode_html($body),
                    'notify'      => 0,
                    'status'      => null,
                    'guest'       => 1,
                    'guest_name'  => $this->input->post('name', true),
                    'guest_email' => $this->input->post('email', true),
                ];
            }

            if ($this->form_validation->run() == true && $this->ajax_model->addPost($post)) {
                $this->session->set_flashdata('message', lang('post_added') . ($post['status'] == 1 ? '' : '<br>' . lang('under_review') . '<br>' . lang('be_patient')));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('error', validation_errors() ? validation_errors() : lang('body_is_required'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            redirect('/');
        }
    }

    public function send_digest()
    {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        if ($this->Settings->digest_date < $yesterday) {
            $this->ajax_model->UnbanUsers();
            $this->tec->send_digest($yesterday);
        }
    }

    public function vote()
    {
        if ($this->input->is_ajax_request() && $this->loggedIn) {
            if ($this->Settings->voting == 1) {
                if ($this->input->post('postid') && $this->input->post('action')) {
                    $user_id = $this->session->userdata('user_id');
                    $this->ajax_model->vote($this->input->post('postid'), $this->input->post('action'));
                    die(lang('vote_casted'));
                }
                die(lang('action_failed'));
            } elseif ($this->Settings->voting == 2) {
                if ($this->input->post('postid') && $this->input->post('stars')) {
                    $user_id = $this->session->userdata('user_id');
                    $this->ajax_model->vote($this->input->post('postid'), false, $this->input->post('stars'));
                    die(lang('rating_added'));
                }
                die(lang('action_failed'));
            }
        } else {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
}
