<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Digest_model extends CI_Model
{

    public function __construct() {
        parent::__construct();

    }

    public function getAllTimePopularTopics() {
        $this->db->select("title, slug, {$this->db->dbprefix('topics')}.category_slug, description")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->where('topics.active', 1)->where('topics.protected !=', 2)
        ->group_by('posts.topic_id')
        ->order_by("COUNT({$this->db->dbprefix('posts')}.topic_id)", 'desc')
        ->limit(5);

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPopularTopics($start_date, $end_date) {
        $this->db->select("title, slug, {$this->db->dbprefix('topics')}.category_slug, description")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->where('topics.active', 1)->where('topics.protected !=', 2)
        ->where('posts.created_at >=', $start_date.' 00:00:00')
        ->where('posts.created_at <=', $end_date.' 23:59:59')
        ->group_by('posts.topic_id')
        ->order_by("count({$this->db->dbprefix('posts')}.topic_id)", 'desc')
        ->limit(5);

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRepliedTopics($start_date, $end_date) {
        $this->db->select("title, slug, {$this->db->dbprefix('topics')}.category_slug")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->where('topics.active', 1)->where('topics.protected !=', 2)
        ->where('posts.created_at >=', $start_date.' 00:00:00')
        ->where('posts.created_at <=', $end_date.' 23:59:59')
        ->group_by('posts.topic_id')
        ->order_by("count({$this->db->dbprefix('posts')}.topic_id)", 'desc')
        ->limit(10);

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getNewTopics($start_date, $end_date) {
        $this->db->select('title, slug, category_slug')
        ->where('active', 1)->where('protected !=', 2)
        ->where('created_at >=', $start_date.' 00:00:00')
        ->where('created_at <=', $end_date.' 23:59:59')
        ->order_by('id', 'desc')
        ->limit(10);

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

}
