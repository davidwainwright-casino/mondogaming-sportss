<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forums extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('forums_model');
        if ($this->Settings->mode == 2 && !$this->Admin) {
            redirect('login');
        }
    }

    public function archive($year = null, $month = null)
    {
        if ($this->Settings->mode == 1 && !$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }
        $nmonth = $year . '-' . $month;
        if (isset($_GET['sort'])) {
            $this->session->set_userdata('sorting', $this->input->get('sort', true));
        }
        $sorting             = $this->session->userdata('sorting') ? $this->session->userdata('sorting') : $this->Settings->sorting;
        $page                = $this->input->get('page') ? $this->input->get('page', true) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total                    = $this->forums_model->total_topics(null, $nmonth);
        $this->data['links']      = pagination(('archive/' . $month), $total, $limit);
        $start                    = $page ? ($page * $limit) - $limit : 0;
        $this->data['records']    = ['total' => $total, 'from' => ($start + 1), 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['topics']     = $this->forums_model->get_topics($limit, $start, null, $nmonth, null, null, null, $sorting);
        $this->data['page_title'] = lang('archive_for') . ' ' . date('F Y', strtotime($nmonth));
        $this->page_construct('forums/index', $this->data);
    }

    public function complain($slug = null)
    {
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $post_id = $this->input->get('post') ? $this->input->get('post', true) : null;

        $topic = $this->forums_model->getTopicBySlug($slug);
        if (!$topic) {
            $this->session->set_flashdata('error', lang('topic_x_found'));
            redirect('/');
        }
        $this->form_validation->set_rules('reason', lang('reason'), 'required|trim');

        if ($this->form_validation->run() == true) {
            $data = [
                'topic_id'      => $topic->id,
                'post_id'       => $post_id,
                'user_id'       => $this->session->userdata('user_id'),
                'reason'        => $this->input->post('reason'),
                'complained_at' => date('Y-m-d H:i:s'),
            ];
        } elseif ($this->input->post('complain')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        if ($this->form_validation->run() == true && $this->forums_model->registerComplain($data)) {
            $this->session->set_flashdata('message', ($post_id ? lang('reply_flagged') : lang('thread_flagged')));
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        } else {
            $this->data['thread']     = $topic;
            $this->data['post_id']    = $post_id;
            $this->data['page_title'] = lang('complain');
            $this->load->view($this->theme . 'forums/complain', $this->data);
        }
    }

    public function get_slug()
    {
        echo $this->tec->slug($this->tec->title_slug(str_replace(['"', "'"], '-', $this->input->get('title', true))));
        exit();
    }

    public function index($category_slug = null, $user_threads = null)
    {
        if ($this->Settings->mode == 1 && !$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if (isset($_GET['sort'])) {
            $this->session->set_userdata('sorting', $this->input->get('sort', true));
        }
        $sorting  = $this->session->userdata('sorting') ? $this->session->userdata('sorting') : $this->Settings->sorting;
        $page     = $this->input->get('page') ? $this->input->get('page', true) : 0;
        $category = $category_slug ? $this->forums_model->getCategoryBySlug($category_slug) : null;
        if ($category_slug && !$category) {
            $this->session->set_flashdata('error', sprintf(lang('page_x_found'), base_url() . uri_string()));
            redirect('/');
        }
        $child_category      = $category_slug ? ($category->parent_id ? $category->id : null) : null;
        $category_id         = $category_slug ? $category->id : null;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total                          = $this->forums_model->total_topics($category_id, null, null, $child_category, $user_threads);
        $link_uri                       = $user_threads ? 'user_topics/' . $user_threads : $category_slug;
        $this->data['links']            = pagination($link_uri, $total, $limit);
        $start                          = $page ? ($page * $limit) - $limit : 0;
        $this->data['category']         = $category;
        $this->data['user_threads']     = $user_threads;
        $this->data['records']          = ['total' => $total, 'from' => ($start + 1), 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['topics']           = $this->forums_model->get_topics($limit, $start, $category_id, null, null, $child_category, $user_threads, $sorting);
        $this->data['child_categories'] = $category_slug ? $this->forums_model->getChildrenCategories($category_id) : null;
        $this->data['page_title']       = $category_slug ? $category->name : lang('home');
        $this->data['meta_description'] = $category_slug ? $category->description : $this->Settings->description;
        $this->page_construct('forums/index', $this->data);
    }

    public function members($online = null)
    {
        if (($this->Settings->mode == 1 && !$this->loggedIn) || !$this->Settings->member_page) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if (isset($_GET['sort'])) {
            $this->session->set_userdata('member_sorting', $this->input->get('sort', true));
        }
        $sorting             = $this->session->userdata('member_sorting');
        $online              = $this->input->get('online') ? $this->input->get('online', true) : false;
        $page                = $this->input->get('page') ? $this->input->get('page', true) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total                    = $this->forums_model->getTotalUsers($online);
        $this->data['links']      = pagination('members', $total, $limit);
        $start                    = $page ? ($page * $limit) - $limit : 0;
        $this->data['links']      = $this->pagination->create_links();
        $this->data['records']    = ['total' => $total, 'from' => ($start + 1), 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['members']    = $this->forums_model->get_members($limit, $start, $online, $sorting);
        $this->data['online']     = $online;
        $this->data['page_title'] = lang('members');
        $this->page_construct('forums/members', $this->data);
    }

    public function page($slug = null)
    {
        if ($this->Settings->mode == 1 && !$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $page                = $this->forums_model->getPageBySlug($slug);
        if (!$page) {
            $this->session->set_flashdata('error', lang('page_x_found'));
            redirect('/');
        }
        $this->data['page']             = $page;
        $this->data['page_title']       = $page->title;
        $this->data['meta_description'] = $page->description;
        $this->page_construct('forums/page', $this->data);
    }

    public function search()
    {
        if ($this->Settings->mode == 1 && !$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        if ($this->input->get('query')) {
            $search_str = $this->input->get('query', true);
        } else {
            $this->session->set_flashdata('error', lang('search_empty'));
            redirect('/');
        }
        if (isset($_GET['sort'])) {
            $this->session->set_userdata('sorting', $this->input->get('sort', true));
        }
        $sorting             = $this->session->userdata('sorting') ? $this->session->userdata('sorting') : $this->Settings->sorting;
        $page                = $this->input->get('page') ? $this->input->get('page', true) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total                    = $this->forums_model->total_topics(null, null, $search_str);
        $this->data['links']      = pagination(('search?query=' . $search_str), $total, $limit);
        $start                    = $page ? ($page * $limit) - $limit : 0;
        $this->data['links']      = $this->pagination->create_links();
        $this->data['records']    = ['total' => $total, 'from' => ($start + 1), 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['topics']     = $this->forums_model->get_topics($limit, $start, null, null, $search_str, null, null, $sorting);
        $this->data['page_title'] = lang('seaching_for') . ' ' . $search_str;
        $this->page_construct('forums/index', $this->data);
    }

    public function terms($slug = null)
    {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $page                = $this->forums_model->getPageBySlug($this->Settings->terms_page);
        $this->data['page']  = $page;
        $this->load->view($this->theme . 'forums/terms', $this->data);
    }

    public function topic($category_slug = null, $slug = null)
    {
        if ($this->Settings->mode == 1 && !$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        $topic = $this->forums_model->getTopicBySlug($slug);
        if (!$topic) {
            $this->session->set_flashdata('error', lang('topic_x_found'));
            redirect('/');
        }

        if (!empty($topic->protected)) {
            if (!$this->loggedIn) {
                $this->session->set_flashdata('error', lang('access_denied'));
                redirect('/');
            } else {
                if (($this->Member && $topic->protected == 2)) {
                    if ($topic->created_by != $this->session->userdata('user_id')) {
                        $this->session->set_flashdata('error', lang('access_denied'));
                        redirect('/');
                    }
                }
            }
        }
        $page                = $this->input->get('page') ? $this->input->get('page', true) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit               = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total               = $this->forums_model->total_topic_posts($topic->id);
        $this->data['links'] = pagination(($category_slug . '/' . $slug), $total, $limit);
        $start               = $page ? ($page * $limit) - $limit : 0;
        $this->data['topic'] = $topic;
        $this->data['posts'] = $this->forums_model->get_posts($limit, $start, $topic->id, $topic->post_id);
        if ($this->Settings->captcha == 2 || ($this->Settings->guest_reply && !$this->loggedIn)) {
            $this->data['image'] = $this->tec->create_captcha();
        }
        $this->data['records']    = ['total' => $total, 'from' => ($start + 1), 'till' => (($start + $limit) < $total ? ($start + $limit) : $total)];
        $this->data['fields']     = $this->forums_model->getFieldsData($topic->id);
        $this->data['page_title'] = $topic->title;
        if ($this->Settings->voting == 1) {
            $this->data['vote']         = $this->loggedIn ? $this->forums_model->getThumbVote($topic->id) : false;
            $this->data['thread_votes'] = $this->forums_model->getThumbVotes($topic->id);
        } elseif ($this->Settings->voting == 2) {
            $this->data['my_stars']     = $this->loggedIn ? $this->forums_model->getStarVote($topic->id) : false;
            $this->data['thread_votes'] = $this->forums_model->getStarVotes($topic->id);
        }
        $this->data['meta_description'] = $topic->description;
        $this->forums_model->updateViewsNum($topic->id, ($topic->views + 1));
        $this->page_construct('forums/topic', $this->data);
    }

    public function upload($type = null)
    {
        // if(DEMO) {
        //     $error = array('error' => true, 'msg' => $this->lang->line('disabled_in_demo'));
        //     http_response_code(400);
        //     $this->tec->send_json($error);
        // }

        $this->security->csrf_verify();
        if (isset($_FILES['file'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'uploads/';
            $config['max_size']    = '500';

            if ($type == 'image') {
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_width']     = '800';
                $config['max_height']    = '800';
            } elseif ($type == 'file') {
                $config['allowed_types'] = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|txt';
            }

            $config['overwrite']    = false;
            $config['max_filename'] = 25;
            $config['encrypt_name'] = true;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $error = ['error' => true, 'msg' => stripslashes(strip_tags($error))];
                http_response_code(400);
                $this->tec->send_json($error);
            }

            $file  = $this->upload->file_name;
            $array = [
                'success'  => true,
                'name'     => $this->input->post('name'),
                'file'     => base_url() . 'uploads/' . $file,
                'filelink' => base_url() . 'uploads/' . $file,
            ];

            die(stripslashes(json_encode($array)));
        }
        $error = ['error' => true, 'msg' => 'No file selected to upload!'];
        http_response_code(400);
        $this->tec->send_json($error);
    }
}
