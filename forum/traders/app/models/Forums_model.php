<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Forums_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTopicBySlug($slug) {
        $this->db->select("{$this->db->dbprefix('topics')}.*, categories.name as category, users.id as user_id, users.avatar, users.username, users.gender as user_gender, users.signature, posts.body, {$this->db->dbprefix('posts')}.id as post_id, COUNT({$this->db->dbprefix('posts')}.id) as total_posts")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->join('users', 'users.id=topics.created_by', 'left')
        ->group_by('posts.topic_id')
        ->limit(1)->order_by("posts.created_at", "desc");
        if (!$this->Admin || !$this->Moderator) {
            if ($this->Settings->flag_option == 2) {
                $this->db->group_start()->where('topics.status', 1)->or_where('topics.status', 2)->group_end();
            } else {
                $this->db->where('topics.status', 1);
            }
        }

        $q = $this->db->get_where('topics', array('topics.slug' => $slug));
        if( $q->num_rows() > 0 ) {
            return $q->row();
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

    public function total_topics($category_id = NULL, $month = NULL, $search = NULL, $child_category_id = NULL, $user_topics = NULL) {
        $user_id = $this->session->userdata('user_id');
        $this->db->join('categories', 'categories.id=topics.category_id', 'left')
        ->where('categories.active', 1);
        if ($this->Settings->flag_option == 2) {
            $this->db->group_start()->where('topics.status', 1)->or_where('topics.status', 2)->group_end();
        } else {
            $this->db->where('topics.status', 1);
        }
        if ( ! $this->loggedIn) {
            $this->db->where('protected', 0);
        } elseif ($this->Member) {
            $this->db->group_start()->where('protected !=', 2)->or_where($this->db->dbprefix('topics').'.created_by', $user_id)->group_end();
        }
        if ($child_category_id) {
            $this->db->where('child_category_id', $child_category_id);
        } elseif ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($month) {
            $this->db->like($this->db->dbprefix('topics').'.created_at', $month, 'after');
        }
        if ($search) {
            $this->db->like('title', $search, 'both');
            $this->db->or_like("{$this->db->dbprefix('topics')}.description", $search, 'both');
        }
        if ($user_topics) {
            $this->db->where("{$this->db->dbprefix('topics')}.created_by", $user_topics);
        }
        return $this->db->count_all_results("topics");

    }

    public function get_topics($limit, $start, $category_id = NULL, $month = NULL, $search = NULL, $child_category_id = NULL, $user_topics = NULL, $sorting = NULL) {
        $user_id = $this->session->userdata('user_id');
        $sorting = $sorting ? $sorting : $this->Settings->sorting;
        $this->db->select("{$this->db->dbprefix('topics')}.*, categories.name as category, subcategories.name as child_category, users.id as user_id, users.username, users.avatar, users.gender as user_gender, posts.body, COUNT({$this->db->dbprefix('posts')}.id) as total_posts")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->join('categories subcategories', 'subcategories.id=topics.child_category_id', 'left')
        ->join('users', 'users.id=topics.created_by', 'left')
        ->where('categories.active', 1)
        ->group_by('posts.topic_id');
        if ( ! $this->loggedIn) {
            $this->db->where('protected', 0);
        } elseif ($this->Member) {
            $this->db->group_start()->where('protected !=', 2)->or_where($this->db->dbprefix('topics').'.created_by', $user_id)->group_end();
        }

        if ($this->Settings->flag_option == 2) {
            $this->db->group_start()->where('topics.status', 1)->or_where('topics.status', 2)->group_end();
        } else {
            $this->db->where('topics.status', 1);
        }

        if ($child_category_id) {
            $this->db->where("{$this->db->dbprefix('topics')}.child_category_id", $child_category_id);
        } elseif ($category_id) {
            $this->db->where("{$this->db->dbprefix('topics')}.category_id", $category_id)
            ->order_by('sticky_category', 'desc');
        }
        if ($month) {
            $this->db->like($this->db->dbprefix('topics').'.created_at', $month, 'after');
        }
        if ($search) {
            $this->db->like('title', $search, 'both');
            $this->db->or_like("{$this->db->dbprefix('topics')}.description", $search, 'both');
        }
        if ($user_topics) {
            $this->db->where("{$this->db->dbprefix('topics')}.created_by", $user_topics);
        }

        $this->db->limit($limit, $start)
        ->order_by('sticky', 'desc');
        if ($sorting == 1) {
            $this->db->order_by('posts.created_at', 'asc');
        } elseif ($sorting == 2) {
            if ($this->Settings->voting == 1) {
                $this->db->order_by('votes', 'desc');
            } elseif ($this->Settings->voting == 2) {
                $this->db->order_by('stars', 'desc');
            }
            $this->db->order_by('posts.created_at', 'desc');
        } elseif ($sorting == 3) {
            if ($this->Settings->voting == 1) {
                $this->db->order_by('votes', 'asc');
            } elseif ($this->Settings->voting == 2) {
                $this->db->order_by('stars', 'asc');
            }
            $this->db->order_by('posts.created_at', 'desc');
        } elseif ($sorting == 4) {
            $this->db->order_by('last_reply_time', 'desc');
            $this->db->order_by('posts.created_at', 'desc');
        } elseif ($sorting == 5) {
            $this->db->order_by('last_reply_time', 'asc');
            $this->db->order_by('posts.created_at', 'desc');
        } elseif ($sorting == 6) {
            $this->db->order_by('views', 'desc');
            $this->db->order_by('posts.created_at', 'desc');
        } elseif ($sorting == 7) {
            $this->db->order_by('views', 'asc');
            $this->db->order_by('posts.created_at', 'desc');
        } else {
            $this->db->order_by('posts.created_at', 'desc');
        }

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function total_topic_posts($topic_id) {
            $this->db->where('topic_id', $topic_id);
            if ($this->Settings->flag_option == 2) {
                $this->db->group_start()->where('posts.status', 1)->or_where('posts.status', 2)->group_end();
            } else {
                $this->db->where('posts.status', 1);
            }
            $posts = $this->db->count_all_results("posts");
            return ($posts-1);
    }

    public function get_posts($limit, $start, $topic_id, $skip_post_id) {
        $this->db->select("{$this->db->dbprefix('posts')}.*, users.id as user_id, users.avatar, users.username, users.gender as user_gender, users.signature")
        ->join('users', 'users.id=posts.created_by', 'left')
        ->group_by('posts.id')
        ->where('posts.id !=', $skip_post_id)
        ->limit($limit, $start);
        if ($this->Settings->flag_option == 2) {
            $this->db->group_start()->where('posts.status', 1)->or_where('posts.status', 2)->group_end();
        } else {
            $this->db->where('posts.status', 1);
        }
        if ($this->Settings->reply_sorting == 1) {
            $this->db->order_by('posts.created_at', 'asc');
        } else {
            $this->db->order_by('posts.created_at', 'desc');
        }
        $q = $this->db->get_where('posts', array('posts.topic_id' => $topic_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getPageBySlug($slug) {
        $q = $this->db->get_where('pages', array('slug' => $slug));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateViewsNum($topic_id, $views) {
        if ($this->db->update('topics', array('views' => $views), array('id' => $topic_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getChildrenCategories($parent_id) {
        $this->db->order_by('order_no', 'asc');
        $cat = $this->db->get_where('categories', array('parent_id' => $parent_id));
        if ($cat->num_rows() > 0) {
            foreach (($cat->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getFieldsData($topic_id) {
        $this->db->select('fields_data.value, fields.name, fields.type, fields.public')
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

    public function getThumbVote($topic_id) {
        $user_id = $this->session->userdata('user_id');
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

    public function getStarVote($topic_id) {
        $user_id = $this->session->userdata('user_id');
        $q = $this->db->get_where('star_votes', array('topic_id' => $topic_id, 'user_id' => $user_id));
        if( $q->num_rows() > 0 ) {
            $v = $q->row();
            return $v->stars;
        }
        return FALSE;
    }

    public function getStarVotes($topic_id) {
        $this->db->select('COUNT(stars) as votes')
        ->where('topic_id', $topic_id);
        return $this->db->count_all_results('star_votes');
    }

    public function get_members($limit, $start, $online, $sorting) {

        $this->db->select("{$this->db->dbprefix('users')}.id as id, first_name, last_name, username, avatar, created_on, accept_messages, last_login, groups.name as group")
        ->join('groups', 'groups.id=users.group_id', 'left');
        if ($online) {
            $time = (time()-300);
            $this->db->where('last_activity >', $time);
        }
        if ($sorting == 2) {
            $this->db->order_by('username', 'desc');
        } elseif ($sorting == 3) {
            $this->db->order_by('created_on', 'asc');
        } elseif ($sorting == 4) {
            $this->db->order_by('created_on', 'desc');
        } else {
            $this->db->order_by('username', 'asc');
        }
        $this->db->limit($limit, $start);

        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTotalUsers($online) {
        if ($online) {
            $time = (time()-300);
            $this->db->where('last_activity >', $time);
        }
        return $this->db->count_all('users');
    }

    public function registerComplain($data) {
        if ($this->db->insert('complains', $data, array('id' => $data['topic_id']))) {
            if ($data['post_id']) {
                $this->db->update('posts', array('status' => 2), array('id' => $data['post_id']));
            } else {
                $this->db->update('topics', array('status' => 2), array('id' => $data['topic_id']));
            }
            return TRUE;
        }
        return FALSE;
    }

}
