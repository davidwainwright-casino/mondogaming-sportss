<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTopicBySlug($slug) {
        $this->db->select("{$this->db->dbprefix('topics')}.*, categories.name as category, users.id as user_id, users.avatar, users.username, users.gender as user_gender, posts.body, {$this->db->dbprefix('posts')}.id as post_id, COUNT({$this->db->dbprefix('posts')}.id) as total_posts")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->join('users', 'users.id=topics.created_by', 'left')
        ->group_by('posts.topic_id')
        ->limit(1)->order_by("posts.created_at", "desc");

        $q = $this->db->get_where('topics', array('topics.slug' => $slug));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function get_topics($limit, $start) {
        $this->db->select("{$this->db->dbprefix('topics')}.*, categories.name as category, subcategories.name as child_category, users.id as user_id, users.username, users.avatar, users.gender as user_gender, posts.body, COUNT({$this->db->dbprefix('posts')}.id) as total_posts, (CASE WHEN {$this->db->dbprefix('topics')}.status = 2 THEN {$this->db->dbprefix('complains')}.reason ELSE NULL END) as reason, (CASE WHEN {$this->db->dbprefix('topics')}.status = 2 THEN CONCAT(cusers.id, '__', cusers.username) ELSE NULL END) as complained_by")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join('complains', 'complains.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->join('categories subcategories', 'subcategories.id=topics.child_category_id', 'left')
        ->join('users', 'users.id=topics.created_by', 'left')
        ->join('users cusers', 'cusers.id=complains.user_id', 'left')
        ->group_by('posts.topic_id');

        $this->db->where('topics.status', NULL)->or_where('topics.status', 0)->or_where('topics.status', 2);
        $this->db->limit($limit, $start)
        ->order_by('topics.status', 'desc')
        ->order_by('topics.id', 'asc');

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_posts($limit, $start) {
        $this->db->select("{$this->db->dbprefix('posts')}.*, users.id as user_id, users.avatar, users.username, users.gender as user_gender, {$this->db->dbprefix('topics')}.title, {$this->db->dbprefix('topics')}.category_slug, {$this->db->dbprefix('topics')}.slug, (CASE WHEN {$this->db->dbprefix('posts')}.status = 2 THEN {$this->db->dbprefix('complains')}.reason ELSE NULL END) as reason, (CASE WHEN {$this->db->dbprefix('posts')}.status = 2 THEN CONCAT(cusers.id, '__', cusers.username) ELSE NULL END) as complained_by")
        ->join('users', 'users.id=posts.created_by', 'left')
        ->join('topics', 'posts.topic_id=topics.id', 'left')
        ->join('complains', 'complains.post_id=posts.id', 'left')
        ->join('users cusers', 'cusers.id=complains.user_id', 'left')
        ->where('posts.status', NULL)->or_where('posts.status', 0)->or_where('posts.status', 2)
        ->group_by('posts.id')
        ->limit($limit, $start)
        ->order_by('posts.status', 'desc')
        ->order_by('posts.id', 'asc');

        $q = $this->db->get('posts');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function approveTopic($id) {
        if ($this->db->update('topics', array('status' => 1), array('id' => $id))) {
            $this->db->delete('complains', array('topic_id' => $id, 'post_id' => NULL));
            return true;
        }
        return false;
    }

    public function approvePost($id) {
        $post = $this->getPostByID($id);
        $created_at = date('Y-m-d H:i:s');
        $user = empty($post->created_by) ? FALSE : $this->settings_model->getUser($post->created_by);
        $last_reply_by = $user ? $user->username : $post->guest_name;
        if ($post->status != 2) {
            $data = array('status' => 1, 'created_at' => $created_at);
            $topic_data = array('last_reply_time' => $created_at, 'last_reply_by' => $last_reply_by);
        } else {
            $data = array('status' => 1);
            $topic_data = array();
        }
        if ($this->db->update('posts', $data, array('id' => $id))) {
            if ( ! empty($topic_data)) {
                $this->db->update('topics', $topic_data, array('id' => $post->topic_id));
            }
            $this->db->delete('complains', array('topic_id' => $post->topic_id, 'post_id' => $id));
            return true;
        }
        return false;
    }

    public function getTopicByID($id) {
        $q = $this->db->get_where('topics', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPostByID($id) {
        $q = $this->db->get_where('posts', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function deleteTopic($id) {
        if ($this->db->delete('topics', array('id' => $id)) && $this->db->delete('posts', array('topic_id' => $id))) {
            $this->db->delete('complains', array('topic_id' => $id));
            return true;
        }
        return false;
    }

    public function deletePost($id) {
        if ($this->db->delete('posts', array('id' => $id))) {
            $this->db->delete('complains', array('post_id' => $id));
            return true;
        }
        return false;
    }

    public function banUser($id, $data) {
        if ($this->db->update('users', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

}
