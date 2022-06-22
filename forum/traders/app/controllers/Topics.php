<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Topics extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('topics_model');
    }

    function index() {

    }

    function add() {

        $this->form_validation->set_rules('title', lang('title'), 'required|trim');
        $this->form_validation->set_rules('body', lang('body'), 'required');
        $this->form_validation->set_rules('category', lang('category'), 'required');
        if ( ! $this->Member) {
            $this->form_validation->set_rules('slug', lang('slug'), 'trim|required|alpha_dash');
        }
        if($this->Settings->captcha == 2) {
            $this->form_validation->set_rules('captcha', lang('captcha'),
            array('required', array('captcha_check',
            function($val) {
                return $this->settings_model->captcha_check($val);
                }
            )));
        }

        $fields = $this->settings_model->getFields();

        if ($this->form_validation->run() == true) {
            if ($this->Member) {
                $this->load->helper('string');
                $es = $this->tec->title_slug($this->input->post('title'));
                $slug = $this->tec->slug( ! empty($es) ? $es : random_string('alnum', 16));
            } else {
                $slug = $this->tec->slug($this->input->post('slug'));
            }
            $created_at = date('Y-m-d H:i:s');
            $data = array(
                'title' => $this->db->escape_str($this->input->post('title')),
                'protected' => $this->input->post('protected'),
                'category_id' => $this->input->post('category'),
                'category_slug' => $this->topics_model->getCategorySlug($this->input->post('category')),
                'child_category_id' => $this->input->post('child_category'),
                'child_category_slug' => $this->topics_model->getCategorySlug($this->input->post('child_category')),
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => $created_at,
                'last_reply_time' => $created_at,
                'slug' => $this->db->escape_str($slug),
                'notify' => $this->input->post('notify'),
                'status' => 1,
            );
            $body = $this->tec->body_censor($this->input->post('body', TRUE));

            if ($this->Admin || $this->Moderator) {
                $data['sticky_category'] = $this->input->post('sticky_category');
                $data['description'] = $this->db->escape_str($this->input->post('description'));
            } else {
                $data['description'] = $this->db->escape_str($this->tec->character_limiter(strip_tags($body), 180));
            }
            if ($this->Admin) {
                $data['active'] = $this->input->post('active');
                $data['sticky'] = $this->input->post('sticky');
            }
            $post = array(
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => $created_at,
                'body' => $this->tec->encode_html($body)
            );
            if ($this->Member) {
                $user_publiched_number = $this->topics_model->totalPublishedTopics();
                if ($this->Settings->review_option == 1 ||
                    ($this->Settings->review_option == 2 && $user_publiched_number < 5) ||
                    ($this->Settings->review_option == 3 && $user_publiched_number < 10) ) {
                    $data['status'] = NULL;
                }
            }

            $fields_data = array();
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }
            $fields = $this->settings_model->getCategoryFields($this->input->post('category'));
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }
            $fields = $this->settings_model->getCategoryFields($this->input->post('child_category'));
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }

            if (empty($post['body'])) {
                $this->session->set_flashdata('error', lang('body_has_special_chars'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            // $this->tec->print_arrays($data, $post, $fields_data);

        } elseif($this->input->post('add_topic')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->topics_model->addTopic($data, $post, $fields_data)) {

            $this->session->set_flashdata('message', lang('topic_added').($data['status'] == 1 ? '' : '<br>'.lang('under_review').'<br>'.lang('be_patient')));
            $this->tec->new_topic_nofity($data);
            redirect('/');

        } else {

            if($this->Settings->captcha == 2) {
                $this->data['image'] = $this->tec->create_captcha();
            }
            $this->data['parent_categories'] = $this->topics_model->getParentCatgories();
            $this->data['fields'] = $fields;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('new_topic');
            $this->load->view($this->theme.'topics/add', $this->data);

        }

    }

    function edit($id = NULL) {

        $topic = $this->topics_model->getTopicByID($id);
        if (!$this->Admin && !$this->Moderator && $topic->created_by != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
        }
        $this->form_validation->set_rules('title', lang('title'), 'required|trim');
        $this->form_validation->set_rules('body', lang('body'), 'required');
        $this->form_validation->set_rules('category', lang('category'), 'required');
        if( ! $this->Member) {
            $this->form_validation->set_rules('slug', lang('slug'), 'trim|required|alpha_dash');
        }
        if($this->Settings->captcha == 2) {
            $this->form_validation->set_rules('captcha', lang('captcha'),
            array('required', array('captcha_check',
            function($val) {
                return $this->settings_model->captcha_check($val);
                }
            )));
        }

        if ($this->form_validation->run() == true) {
            if ($this->Member || $topic->slug == $this->input->post('slug')) {
                $slug = $topic->slug;
            } else {
                $slug = $this->tec->slug($this->input->post('slug'));
            }
            $updated_at = date('Y-m-d H:i:s');
            $post = $this->topics_model->getFirstTopicPostByID($id);
            if($post->id != $this->input->post('post')) {
                $this->session->set_flashdata('error', lang('post_id_x_match'));
                redirect('topics/edit/'.$id);
            }
            $data = array(
                'title' => $this->db->escape_str($this->input->post('title')),
                'protected' => $this->input->post('protected'),
                'category_id' => $this->input->post('category'),
                'category_slug' => $this->topics_model->getCategorySlug($this->input->post('category')),
                'child_category_id' => $this->input->post('child_category'),
                'child_category_slug' => $this->topics_model->getCategorySlug($this->input->post('child_category')),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $updated_at,
                'slug' => $this->db->escape_str($slug),
                'notify' => $this->input->post('notify'),
            );
            $body = $this->tec->body_censor($this->input->post('body', TRUE));
            if($this->Admin || $this->Moderator) {
                $data['description'] = $this->db->escape_str($this->input->post('description'));
                $data['sticky_category'] = $this->input->post('sticky_category');
            }
            if ($this->Admin) {
                $data['active'] = $this->input->post('active');
                $data['sticky'] = $this->input->post('sticky');
            }
            $post_data = array(
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $updated_at,
                'body' => $this->tec->encode_html($body)
            );

            $fields_data = array();

            $fields = $this->settings_model->getFields();
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }
            $fields = $this->settings_model->getCategoryFields($this->input->post('category'));
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }
            $fields = $this->settings_model->getCategoryFields($this->input->post('child_category'));
            if ( ! empty($fields)) {
                foreach ($fields as $field) {
                    if ($field_value = $this->db->escape_str($this->input->post($field->unique_id))) {
                        $fields_data[] = array(
                            'field_id' => $field->id,
                            'value' => $field->type == 'checkbox' ? implode('_|_', $field_value) : $field_value,
                            );
                    }
                }
            }

            if (empty($post_data['body'])) {
                $this->session->set_flashdata('error', lang('body_has_special_chars'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            // $this->tec->print_arrays($data, $post_data);

        } elseif($this->input->post('edit_topic')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->topics_model->updateTopic($id, $data, $post->id, $post_data, $fields_data)) {

            $this->session->set_flashdata('message', lang('topic_updated'));
            redirect($data['category_slug'].'/'.$data['slug']);

        } else {

            if($this->Settings->captcha == 2) {
                $this->data['image'] = $this->tec->create_captcha();
            }
            $this->data['parent_categories'] = $this->topics_model->getParentCatgories();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['topic'] = $topic;
            $this->data['fields'] = $this->topics_model->getFieldsData($topic->id);
            $this->data['child_categories'] = $this->topics_model->getChildrenCatgories($topic->category_id);
            $this->data['post'] = $this->topics_model->getFirstTopicPostByID($id);
            $this->data['page_title'] = lang('edit_topic');
            $this->load->view($this->theme.'topics/edit', $this->data);

        }

    }

    function add_post($topic_id) {

        $this->form_validation->set_rules('body', lang('body'), 'required|min_length[2]');
        if($this->Settings->captcha == 2) {
            $this->form_validation->set_rules('captcha', lang('captcha'),
            array('required', array('captcha_check',
            function($val) {
                return $this->settings_model->captcha_check($val);
                }
            )));
        }

        if ($this->form_validation->run() == true) {

            $created_at = date('Y-m-d H:i:s');
            $body = $this->tec->body_censor($this->input->post('body', TRUE));
            $post = array(
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => $created_at,
                'topic_id' => $topic_id,
                'body' => $this->tec->encode_html($body),
                'notify' => $this->input->post('notify'),
                'status' => 1,
            );

            if ($this->Member) {
                $user_publiched_number = $this->topics_model->totalPublishedPosts();
                if ($this->Settings->review_option == 1 ||
                    ($this->Settings->review_option == 2 && $user_publiched_number < 5) ||
                    ($this->Settings->review_option == 3 && $user_publiched_number < 10) ) {
                    $post['status'] = NULL;
                }
            }

            if (empty($post['body'])) {
                $this->session->set_flashdata('error', lang('body_has_special_chars'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        if ($this->form_validation->run() == true && $this->topics_model->addPost($post)) {

            $this->tec->notify($topic_id);
            $this->session->set_flashdata('message', lang('post_added').($post['status'] == 1 ? '' : '<br>'.lang('under_review').'<br>'.lang('be_patient')));
            redirect($_SERVER["HTTP_REFERER"]);

        } else {

            if($this->Settings->captcha == 2) {
                $this->data['image'] = $this->tec->create_captcha();
            }
            $this->session->set_flashdata('message', lang('post_added'));
            redirect($_SERVER["HTTP_REFERER"]);

        }

    }

    function edit_post($id) {

        $post = $this->topics_model->getPostByID($id);
        if (!$this->Admin && $post->created_by != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
        }
        $this->form_validation->set_rules('body', lang('body'), 'required|min_length[2]');
        if($this->Settings->captcha == 2) {
            $this->form_validation->set_rules('captcha', lang('captcha'),
            array('required', array('captcha_check',
            function($val) {
                return $this->settings_model->captcha_check($val);
                }
            )));
        }
        if ($this->form_validation->run() == true) {
            if (strip_tags($this->input->post('body')) == '') {
                $this->session->set_flashdata('error', lang('body_required'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
            $created_at = date('Y-m-d H:i:s');
            $body = $this->tec->body_censor($this->input->post('body', TRUE));
            $post = array(
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $created_at,
                'topic_id' => $post->topic_id,
                'body' => $this->tec->encode_html($body),
                'notify' => $this->input->post('notify')
            );

            if (empty($post['body'])) {
                $this->session->set_flashdata('error', lang('body_has_special_chars'));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } elseif($this->input->post('edit_post')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->topics_model->updatePost($id, $post)) {

            $this->session->set_flashdata('message', lang('post_updated'));
            redirect($_SERVER["HTTP_REFERER"]);

        } else {

            if($this->Settings->captcha == 2) {
                $this->data['image'] = $this->tec->create_captcha();
            }
            $this->data['post'] = $post;
            $this->data['page_title'] = lang('edit_post');
            $this->load->view($this->theme.'topics/edit_post', $this->data);

        }

    }

    public function get_child_categories($category_id) {

        $categories = $this->topics_model->getChildrenCatgories($category_id);
        $fields = $this->tec->fields($this->settings_model->getCategoryFields($category_id));
        $data = array('categories' => $categories, 'fields' => $fields);
        if($this->input->is_ajax_request()) {
            echo json_encode($data);
            die();
        }
        return $data;

    }

    public function get_child_fields($category_id) {

        $fields = $this->tec->fields($this->settings_model->getCategoryFields($category_id));
        $data = array('fields' => $fields);
        if($this->input->is_ajax_request()) {
            echo json_encode($data);
            die();
        }
        return $data;

    }

    function delete($id = NULL)
    {

        if(DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if (!$this->Admin) {
            $topic = $this->topics_model->getTopicByID($id);
            if ($topic->created_by != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', lang("access_denied"));
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
            }
        }

        if ($this->topics_model->deleteTopic($id)) {
            $this->session->set_flashdata('message', lang('topic_deleted'));
            redirect("/");
        }

    }

    function delete_post($id = NULL)
    {

        if(DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if (!$this->Admin) {
            $post = $this->topics_model->getPostByID($id);
            if ($post->created_by != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', lang("access_denied"));
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
            }
        }

        if ($this->topics_model->deletePost($id)) {
            $this->session->set_flashdata('message', lang('post_deleted'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "/");
        }

    }


}
