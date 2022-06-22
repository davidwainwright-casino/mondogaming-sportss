<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->Settings = $this->settings_model->getSettings();
        $this->config->set_item('language', $this->Settings->language);
        $this->lang->load('app', $this->Settings->language);
        $this->config->load('hybridauthlib');
        $this->theme = $this->Settings->theme.'/views/';
        $this->data['assets'] = base_url() . 'themes/default/assets/';
        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->tec->logged_in();
        $this->data['loggedIn'] = $this->loggedIn;
        $this->Admin = $this->tec->in_group('admin') ? TRUE : NULL;
        $this->data['Admin'] = $this->Admin;
        $this->Moderator = $this->tec->in_group('moderator') ? TRUE : NULL;
        $this->data['Moderator'] = $this->Moderator;
        $this->Member = $this->tec->in_group('member') ? TRUE : NULL;
        $this->data['Member'] = $this->Member;
        $this->data['menu_categories'] = $this->settings_model->getMenuCatgories();
        $this->data['menu_pages'] = $this->settings_model->getMenuPages();
        $this->data['total_topics'] = $this->settings_model->getTotalTopics();
        $this->data['total_posts'] = $this->settings_model->getTotalPosts();
        $this->data['total_users'] = $this->settings_model->getTotalUsers();
        $this->data['today_birthdays'] = $this->settings_model->getTodayBirthdays();
        $this->data['today_logins'] = $this->settings_model->getTodayLogins();
        $this->data['user_active_topics'] = $this->loggedIn ? $this->settings_model->getUserActiveTopics() : NULL;
        $this->load->helper('smiley');
        $smiley_array = _get_smiley_array();
        foreach($smiley_array as $key => $icon) {
            $smileys[] = array('name' => $icon[3], 'src' => $this->data['assets'].'smileys/'.$icon[0], 'shortcode' => $key);
        }
        $this->data['smileys'] = json_encode($smileys);
        if ($this->loggedIn) {
            $this->settings_model->updateUserLastActivity($this->session->userdata('user_id'));
        }
    }

    function page_construct($page, $data = array(), $meta = array()) {
        if(empty($meta)) { $meta['page_title'] = $data['page_title']; }
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Admin'] = $data['Admin'];
        $meta['Moderator'] = $data['Moderator'];
        $meta['loggedIn'] = $data['loggedIn'];
        $meta['Settings'] = $data['Settings'];
        $meta['assets'] = $data['assets'];
        $meta['menu_pages'] = $data['menu_pages'];
        $meta['unread_messages'] = $this->settings_model->getUnreadNum();
        $meta['total_pending_topics'] = $this->settings_model->total_pending_topics();
        $meta['total_pending_posts'] = $this->settings_model->total_pending_posts();
        $meta['meta_description'] = isset($data['meta_description']) ? stripslashes($data['meta_description']) : NULL;
        $data['online_users'] = $this->settings_model->getOnlineUsers();
        $this->load->view($this->theme . 'header', $meta);
        if ($this->Settings->sidebar == 'left') {
            $this->load->view($this->theme . 'sidebar', $data);
            $this->load->view($this->theme . $page, $data);
        } else {
            $this->load->view($this->theme . $page, $data);
            $this->load->view($this->theme . 'sidebar', $data);
        }
        $this->load->view($this->theme . 'footer');
    }

}

