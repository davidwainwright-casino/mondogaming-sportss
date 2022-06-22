<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_reviews extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn || (!$this->Admin && !$this->Moderator)) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('/');
        }
        $this->load->model('review_model');
    }

    public function index() {

        $page = $this->input->get('page') ? $this->input->get('page', TRUE) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total = $this->settings_model->total_pending_topics();
        $this->data['links'] = pagination('reviews', $total, $limit);
        $start = $page ? ($page*$limit)-$limit : 0;
        $this->data['records'] = array('total' => $total, 'from' => ($start+1), 'till' => (($start+$limit) < $total ? ($start+$limit) : $total));
        $this->data['topics'] = $this->review_model->get_topics($limit, $start);
        $this->data['page_title'] = lang('threads_to_review');
        $this->data['meta_description'] = $this->Settings->description;
        $this->page_construct('reviews/index', $this->data);

    }

    public function posts() {

        $page = $this->input->get('page') ? $this->input->get('page', TRUE) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total = $this->settings_model->total_pending_posts();
        $this->data['links'] = pagination('reviews/posts', $total, $limit);
        $start = $page ? ($page*$limit)-$limit : 0;
        $this->data['records'] = array('total' => $total, 'from' => ($start+1), 'till' => (($start+$limit) < $total ? ($start+$limit) : $total));
        $this->data['posts'] = $this->review_model->get_posts($limit, $start);
        $this->data['page_title'] = lang('posts_to_review');
        $this->data['meta_description'] = $this->Settings->description;
        $this->page_construct('reviews/posts', $this->data);

    }

    public function topic() {

        if($this->Settings->mode == 1 && ! $this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $slug = $this->input->get('slug', TRUE);
        $topic = $this->review_model->getTopicBySlug($slug);
        if ( ! $topic) {
            $this->session->set_flashdata('error', lang('topic_x_found'));
            redirect('/');
        }

        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['topic'] = $topic;
        $this->data['page_title'] = $topic->title;
        $this->data['meta_description'] = $topic->description;
        $this->page_construct('reviews/topic', $this->data);

    }

    public function approve() {
        if ($this->Admin || $this->Moderator) {
            if ($this->input->get('topic')) {
                if ($this->review_model->approveTopic($this->input->get('topic', TRUE))) {
                    if ($this->input->is_ajax_request()) {
                        echo lang('topic_approved');
                    } else {
                        $this->session->set_flashdata('message', lang('topic_approved'));
                        redirect('reviews');
                    }
                }
            } elseif ($this->input->get('post')) {
                if ($this->review_model->approvePost($this->input->get('post', TRUE))) {
                    echo lang('post_approved');
                }
            }
        } else {
            echo lang('access_denied');
            exir();
        }
    }

    public function delete() {
        if ($this->Admin || $this->Moderator) {
            if ($this->input->get('topic')) {

                $topic_id = $this->input->get('topic', TRUE);
                if ($this->Admin) {
                    $this->review_model->deleteTopic($topic_id);
                } elseif ($this->Moderator) {
                    if ($topic = $this->review_model->getTopicByID($topic_id)) {
                        if ($topic->status != 1) {
                            $this->review_model->deleteTopic($topic_id);
                        } else {
                            $mod_error = ture;
                        }
                    }
                }
                if ($this->input->is_ajax_request()) {
                    echo $this->tec->send_json(isset($mod_error) ? array('error' => 1, 'msg' => lang('access_denied')) : array('msg' => lang('topic_deleted')));
                    exit();
                } else {
                    if (isset($mod_error)) {
                        $this->session->set_flashdata('error', lang('access_denied'));
                    } else {
                        $this->session->set_flashdata('message', lang('topic_deleted'));
                    }
                    redirect('reviews');
                }

            } elseif ($this->input->get('post')) {

                $post_id = $this->input->get('post', TRUE);
                if ($this->Admin) {
                    $this->review_model->deletePost($post_id);
                } elseif ($this->Moderator) {
                    if ($post = $this->review_model->getPostByID($post_id)) {
                        if ($post->status != 1) {
                            $this->review_model->deletePost($post_id);
                        } else {
                            echo $this->tec->send_json(array('error' => 1, 'msg' => lang('access_denied')));
                            exit();
                        }
                    }
                }
                echo $this->tec->send_json(array('msg' => lang('post_deleted')));
                exit();

            }
        } else {
            echo $this->tec->send_json(array('error' => 1, 'msg' => lang('access_denied')));
            exit();
        }
    }

    function ban() {

        if ($this->input->get('user')) {

            $id = $this->input->get('user', TRUE);
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
            $this->form_validation->set_rules('unban_date', lang('unban_date'), 'required|trim');
            $this->form_validation->set_rules('message', lang('message'), 'required|trim');

            if ($this->form_validation->run() == true) {

                $data = array(
                    'banned_at' => date('Y-m-d H:i:s'),
                    'banned_by' => $this->session->userdata('user_id'),
                    'unban_date' => $this->input->post('unban_date'),
                    'message' => $this->input->post('message'),
                    'active' => '-3'
                );

                // $this->tec->print_arrays($data);

            } elseif ($this->input->post('ban_user')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($this->form_validation->run() == true && $this->review_model->banUser($id, $data)) {

                $this->session->set_flashdata('message', lang('user_banned'));
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');

            } else {

                $this->data['member'] = $this->settings_model->getUser($id);
                $this->data['page_title'] = lang('ban_user');
                $this->load->view($this->theme.'reviews/ban', $this->data);
            }

        } else {
            $this->session->set_flashdata('error', lang('user_x_selected'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

    }

}
