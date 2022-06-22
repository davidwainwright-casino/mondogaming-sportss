<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_model extends CI_Model
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

    public function addTopic($data, $post) {
        if ($this->db->insert('topics', $data)) {
            $post['topic_id'] = $this->db->insert_id();
            $this->db->insert('posts', $post);
            return true;
        }
        return false;
    }

    public function updateTopic($id, $data, $pid, $post) {
        if ($this->db->update('topics', $data, array('id' => $id)) && $this->db->update('posts', $post, array('id' => $pid))) {
            return true;
        }
        return false;
    }

    public function getChildrenCatgories($parent_id) {
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', array('parent_id' => $parent_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getParentCatgories() {
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', array('parent_id <' => 1));
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

    public function vote($topic_id, $action, $stars = FALSE) {
        $vote = $action == 'up' ? 1 : -1;
        $user_id = $this->session->userdata('user_id');
        if ($this->Settings->voting == 1) {

            if ($vote_details = $this->getThumbVote($topic_id, $user_id)) {
                $this->db->update('votes', array('vote' => $vote), array('topic_id' => $topic_id, 'user_id' => $user_id));
            } else {
                $this->db->insert('votes',
                array( 'vote' => $vote, 'topic_id' => $topic_id, 'user_id' => $user_id ));
            }
            $votes = $this->getThumbVotes($topic_id);
            $this->db->update('topics', array('votes' => ($votes['up']-$votes['down']), 'up_votes' => $votes['up'], 'down_votes' => $votes['down']), array('id' => $topic_id));

        } elseif ($this->Settings->voting == 2) {

            if ($vote_details = $this->getStarVote($topic_id, $user_id)) {
                $this->db->update('star_votes', array('stars' => $stars), array('topic_id' => $topic_id, 'user_id' => $user_id));
            } else {
                $this->db->insert('star_votes',
                array( 'stars' => $stars, 'topic_id' => $topic_id, 'user_id' => $user_id ));
            }
            $stars = $this->getStarVotes($topic_id);
            $this->db->update('topics', array('stars' => $stars), array('id' => $topic_id));

        }
        return TRUE;
    }

    public function getThumbVote($topic_id, $user_id) {
        $q = $this->db->get_where('votes', array('topic_id' => $topic_id, 'user_id' => $user_id));
        if( $q->num_rows() > 0 ) {
            $v = $q->row();
            return $v->vote;
        }
        return FALSE;
    }

    public function getThumbVotes($topic_id) {
        $up_votes = $this->getUpVotes($topic_id);
        $down_votes = $this->getDownVotes($topic_id);
        return array('up' => $up_votes, 'down' => $down_votes);
    }

    public function getUpVotes($topic_id) {
        $this->db->where('topic_id', $topic_id)->where('vote', 1);
        return $this->db->count_all_results('votes');
    }

    public function getDownVotes($topic_id) {
        $this->db->where('topic_id', $topic_id)->where('vote', -1);
        return $this->db->count_all_results('votes');
    }

    public function getStarVote($topic_id, $user_id) {
        $q = $this->db->get_where('star_votes', array('topic_id' => $topic_id, 'user_id' => $user_id));
        if( $q->num_rows() > 0 ) {
            $v = $q->row();
            return $v->stars;
        }
        return FALSE;
    }

    public function getStarVotes($topic_id) {
        $this->db->select('AVG(stars) as stars')
        ->group_by('topic_id');
        $q = $this->db->get_where('star_votes', array('topic_id' => $topic_id));
        if( $q->num_rows() > 0 ) {
            $v = $q->row();
            return $v->stars;
        }
        return FALSE;
    }

    public function addPost($post) {
        if ($this->db->insert('posts', $post)) {
            return true;
        }
        return false;
    }

    public function UnbanUsers() {
        if($users = $this->getBannedUsers()) {
            foreach ($users as $user) {
                $this->db->update('users', array('active' => 1, 'unban_date' => NULL, 'message' => NULL), array('id' => $user->id));
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getBannedUsers() {
        $date = date('Y-m-d');
        $this->db->select('id')->where('unban_date !=', NULL)->where('unban_date <=', $date);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
