<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('messages_model');
    }

    public function index() {

        $page = $this->input->get('page') ? $this->input->get('page', TRUE) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total = $this->messages_model->total_conversations();
        $this->data['links'] = pagination('messages', $total, $limit);
        $start = $page ? ($page*$limit)-$limit : 0;
        $this->data['records'] = array('total' => $total, 'from' => ($start+1), 'till' => (($start+$limit) < $total ? ($start+$limit) : $total));
        $this->data['conversations'] = $this->messages_model->get_conversations($limit, $start);
        $this->data['user_id'] = $this->session->userdata('user_id');
        $this->data['meta_description'] = $this->Settings->description;
        $this->data['page_title'] = lang('messages');
        $this->page_construct('messages/index', $this->data);
    }

    function conversation($con_id = NULL) {

        if ( ! ($conversation = $this->messages_model->getConversationByID($con_id))) {
            $this->session->set_flashdata('error', lang('message_x_found'));
            redirect('messages');
        }
        $user_id = $this->session->userdata('user_id');
        if ($user_id != $conversation->receiver_id && $user_id != $conversation->sender_id) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }
        $page = $this->input->get('page') ? $this->input->get('page', TRUE) : 0;
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $limit = $this->Settings->records_per_page;
        $this->load->helper('pagination');
        $total = $this->messages_model->total_conversation_messages($conversation->id);
        $this->data['links'] = pagination(('messages/conversation/'.$conversation->id), $total, $limit);
        $start = $page ? ($page*$limit)-$limit : 0;
        $this->data['conversation'] = $conversation;
        $this->data['messages'] = $this->messages_model->get_messages($limit, $start, $conversation->id);
        if($this->Settings->captcha == 2) {
            $this->data['image'] = $this->tec->create_captcha();
        }
        $this->data['records'] = array('total' => $total, 'from' => ($start), 'till' => (($start+$limit) < $total ? ($start+$limit) : $total));
        $this->data['user_id'] = $user_id;
        $this->data['receiver'] = $this->settings_model->getUser($conversation->receiver_id);
        $this->messages_model->updateRead($conversation->id, ($user_id == $conversation->sender_id ? array('sender_read' => 1) : array('receiver_read' => 1)));
        $this->data['meta_description'] = $this->Settings->description;
        $this->data['page_title'] = lang('messages');
        $this->page_construct('messages/conversation', $this->data);
    }

    function send($receiver_id = NULL) {
        if ( ! $receiver_id) {
            $this->session->set_flashdata('error', lang('please_select_member'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('subject', lang('subject'), 'required|trim');
        $this->form_validation->set_rules('message_body', lang('message_body'), 'required');
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
            $data = array(
                'subject' => $this->db->escape_str($this->input->post('subject')),
                'sender_id' => $this->session->userdata('user_id'),
                'receiver_id' => $receiver_id,
                'sender_read' => 1,
                'receiver_read' => 0,
                'created_at' => $created_at,
                'last_reply_time' => $created_at,
            );
            $body = $this->tec->body_censor($this->input->post('message_body', TRUE));

            $message = array(
                'user_id' => $this->session->userdata('user_id'),
                'created_at' => $created_at,
                'body' => $this->tec->encode_html($body)
            );

            // $this->tec->print_arrays($data, $message);

        } elseif ($this->input->post('send_message')) {
            if ($this->input->is_ajax_request()) {
                $this->tec->send_json(['status' => 'error', 'msg' => validation_errors()]);
            }
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->messages_model->addConversation($data, $message)) {

            $this->tec->notify_user($receiver_id);
            if ($this->input->is_ajax_request()) {
                $this->tec->send_json(['status' => 'success', 'msg' => lang('message_sent')]);
            }
            $this->session->set_flashdata('message', lang('message_sent'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');

        } else {

            if($this->Settings->captcha == 2) {
                $this->data['image'] = $this->tec->create_captcha();
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['member'] = $this->settings_model->getUser($receiver_id);
            $this->data['page_title'] = lang('send_message');
            $this->load->view($this->theme.'messages/send', $this->data);
        }
    }

    function reply($con_id) {

        $conversation = $this->messages_model->getConversationByID($con_id);
        $this->form_validation->set_rules('message_body', lang('message_body'), 'required|min_length[2]');
        if($this->Settings->captcha == 2) {
            $this->form_validation->set_rules('captcha', lang('captcha'),
            array('required', array('captcha_check',
            function($val) {
                return $this->settings_model->captcha_check($val);
                }
            )));
        }

        if ($this->form_validation->run() == true) {

            $user_id = $this->session->userdata('user_id');
            if ($user_id != $conversation->receiver_id && $user_id != $conversation->sender_id) {
                $this->session->set_flashdata('error', lang('access_denied'));
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
            }
            $created_at = date('Y-m-d H:i:s');
            $body = $this->tec->body_censor($this->input->post('message_body', TRUE));
            $message = array(
                'user_id' => $user_id,
                'created_at' => $created_at,
                'con_id' => $con_id,
                'body' => $this->tec->encode_html($body),
            );

            $con_data = array(
                'receiver_read' => $conversation->receiver_id == $user_id ? 1 : 0,
                'sender_read' => $conversation->sender_id == $user_id ? 1 : 0,
                'last_reply_time' => $created_at
            );

        }

        if ($this->form_validation->run() == true && $this->messages_model->addMessage($message, $con_data)) {

            $this->tec->notify_user($conversation->receiver_id == $user_id ? $conversation->sender_id : $conversation->receiver_id);
            $this->session->set_flashdata('message', lang('reply_added'));
            redirect($_SERVER["HTTP_REFERER"]);

        } else {

            $this->session->set_flashdata('error', lang('action_failed'));
            redirect($_SERVER["HTTP_REFERER"]);

        }

    }

    public function actions()
    {
        $action = $this->input->post('action');
        $ids = $this->input->post('msg_id');
        $user_id = $this->session->userdata('user_id');
        if ($action == 'mread') {
            foreach($ids as $id) {
                $conversation = $this->messages_model->getConversationByID($id);
                if ($conversation->receiver_id == $user_id) {
                    $this->messages_model->updateRead($conversation->id, array('receiver_read' => 1));
                } elseif ($conversation->sender_id == $user_id) {
                    $this->messages_model->updateRead($conversation->id, array('sender_read' => 1));
                }
            }
        } elseif ($action == 'munread') {
            foreach($ids as $id) {
                $conversation = $this->messages_model->getConversationByID($id);
                if ($conversation->receiver_id == $user_id) {
                    $this->messages_model->updateRead($conversation->id, array('receiver_read' => 0));
                } elseif ($conversation->sender_id == $user_id) {
                    $this->messages_model->updateRead($conversation->id, array('sender_read' => 0));
                }
            }
        } elseif ($action == 'mimportant') {
            foreach($ids as $id) {
                $this->messages_model->updateRead($id, array('important' => 1));
            }
        } elseif ($action == 'munimportant') {
            foreach($ids as $id) {
                $this->messages_model->updateRead($id, array('important' => 0));
            }
        } elseif ($action == 'mdelete') {
            foreach($ids as $id) {
                $this->messages_model->deleteConversation($id, $user_id);
            }
        }
        $this->session->set_flashdata('message', lang('action_performed'));
        redirect($_SERVER["HTTP_REFERER"]);
    }

}
