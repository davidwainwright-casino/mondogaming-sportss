<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Helper_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getSubscribers($topic_id) {
        $topic = $this->getTopicByID($topic_id);
        $data = array();

        if ($topic->notify) {
            if ($user = $this->settings_model->getUser($topic->created_by)) {
                if ($user->id != $this->session->userdata('user_id')) {
                    $data[] = array(
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'title' => $topic->title,
                        'slug' => $topic->slug,
                        'category_slug' => $topic->category_slug,
                        'subscription' => $user->subscription,
                    );
                }
            }
        }

        if ($posts = $this->getTopicPosts($topic_id)) {
            foreach ($posts as $post) {
                if ($user = $this->settings_model->getUser($post->created_by)) {
                    if ($user->id != $this->session->userdata('user_id')) {
                        $data[] = array(
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'title' => $topic->title,
                            'slug' => $topic->slug,
                            'category_slug' => $topic->category_slug,
                            'subscription' => $user->subscription,
                        );
                    }
                }
            }
        }
        return $data;
    }

    private function getTopicByID($id) {
        $q = $this->db->get_where('topics', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    private function getTopicPosts($topic_id) {
        $this->db->select('created_by')->distinct();
        $q = $this->db->get_where('posts', array('topic_id' => $topic_id, 'notify' => 1));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
