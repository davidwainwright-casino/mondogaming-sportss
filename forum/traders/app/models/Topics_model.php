<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Topics_model extends CI_Model
{

    public function __construct() {
        parent::__construct();

    }

    public function getTopicByID($id) {
        $q = $this->db->get_where('topics', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTopicBySlug($slug) {
        $q = $this->db->get_where('topics', array('slug' => $slug));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getFirstTopicPostByID($id) {
        $this->db->order_by('id');
        $q = $this->db->get_where('posts', array('topic_id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function addTopic($data, $post, $fields_data = array()) {
        if ($this->db->insert('topics', $data)) {
            $post['topic_id'] = $this->db->insert_id();
            $this->db->insert('posts', $post);
            if ( ! empty($fields_data)) {
                foreach ($fields_data as $field_data) {
                    $field_data['topic_id'] = $post['topic_id'];
                    $this->db->insert('fields_data', $field_data);
                }
            }
            return true;
        }
        return false;
    }

    public function updateTopic($id, $data, $pid, $post, $fields_data = array()) {
        if ($this->db->update('topics', $data, array('id' => $id)) && $this->db->update('posts', $post, array('id' => $pid)) && $this->db->delete('fields_data', array('topic_id' => $id))) {
            if ( ! empty($fields_data)) {
                foreach ($fields_data as $field_data) {
                    $field_data['topic_id'] = $id;
                    $this->db->insert('fields_data', $field_data);
                }
            }
            return true;
        }
        return false;
    }

    public function getPostByID($id) {
        $q = $this->db->get_where('posts', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function addPost($post) {
        if ($this->db->insert('posts', $post)) {
            if ($post['status'] == 1) {
                $this->db->update('topics', array('last_reply_time' => $post['created_at'], 'last_reply_by' => $this->session->userdata('username')), array('id' => $post['topic_id']));
            }
            $this->db->update('posts', array('notify' => $post['notify']), array('topic_id' => $post['topic_id'], 'created_by' => $post['created_by']));
            return true;
        }
        return false;
    }

    public function updatePost($id, $post) {
        if ($this->db->update('posts', $post, array('id' => $id))) {
            $this->db->update('posts', array('notify' => $post['notify']), array('topic_id' => $post['topic_id'], 'created_by' => $post['updated_by']));
            return true;
        }
        return false;
    }

    public function getChildrenCatgories($parent_id) {
        if (!$this->Admin && !$this->Moderator) {
            $this->db->where('private !=', 1);
        }
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', array('parent_id' => $parent_id, 'active' => 1));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getParentCatgories() {
        if (!$this->Admin && !$this->Moderator) {
            $this->db->where('private !=', 1);
        }
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', array('parent_id <' => 1, 'active' => 1));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategoryBySlug($slug) {
        $q = $this->db->get_where('categories', array('slug' => $slug));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategorySlug($id) {
        if($category = $this->getCategoryByID($id)) {
            return $category->slug;
        }
        return FALSE;
    }

    public function deleteTopic($id) {
        if ($this->db->delete('topics', array('id' => $id)) && $this->db->delete('posts', array('topic_id' => $id))  && $this->db->delete('fields_data', array('topic_id' => $id))) {
            return true;
        }
        return false;
    }

    public function deletePost($id) {
        if ($this->db->delete('posts', array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function totalPublishedTopics() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('status', 1)->where('created_by', $user_id);
        return $this->db->count_all_results("topics");
    }

    public function totalPublishedPosts() {
        $user_id = $this->session->userdata('user_id');
        $topics = $this->totalPublishedTopics();
        $this->db->where('status', 1)->where('created_by', $user_id);
        $total_posts =  $this->db->count_all_results("posts");
        return ($total_posts-$topics);
    }

    public function getFieldsData($topic_id) {
        $this->db->select('fields.*, fields_data.value')
        ->join('fields', 'fields.id=fields_data.field_id', 'left');
        $cat = $this->db->get_where('fields_data', array('fields_data.topic_id' => $topic_id));
        if ($cat->num_rows() > 0) {
            foreach (($cat->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
