<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addField($data)
    {
        if ($this->db->insert('fields', $data)) {
            return true;
        }
        return false;
    }

    public function captcha_check($cap)
    {
        $expiration = time() - 7200; // Two hour limit
        $this->db->delete('captcha', ['captcha_time <' => $expiration]);

        $this->db->select('COUNT(*) AS count')
            ->where('word', $cap)
            ->where('ip_address', $this->input->ip_address())
            ->where('captcha_time >', $expiration);

        if ($this->db->count_all_results('captcha')) {
            return true;
        }
        $this->form_validation->set_message('captcha_check', lang('captcha_wrong'));
        return false;
    }

    public function deleteField($id)
    {
        if ($this->db->delete('fields', ['id' => $id])) {
            return true;
        }
        return false;
    }

    public function generateUniqueFieldValue()
    {
        $this->load->helper('string');
        $unique_id = random_string('alnum', 16);
        $this->db->get_where('fields', ['unique_id' => $unique_id]);
        if ($this->db->affected_rows() > 0) {
            $this->generateUniqueFieldValue();
        } else {
            return $unique_id;
        }
    }

    public function getAdmins()
    {
        $this->db->select('users.*, groups.name as group')
        ->join('groups', 'groups.id=users.group_id', 'left')
        ->group_by('users.id');
        $q = $this->db->get_where('users', ['group_id' => 1]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllBadges()
    {
        $q = $this->db->get('badges');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllCategories()
    {
        $this->db->order_by('order_no', 'asc');
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllFields()
    {
        $this->db->select('fields.*, categories.name as category')
        ->join('categories', 'categories.id=fields.category_id', 'left');
        $q = $this->db->get('fields');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllUsers()
    {
        $this->db->select('users.*, groups.name as group')
        ->join('groups', 'groups.id=users.group_id', 'left')
        ->group_by('users.id');
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getCategoryByID($id)
    {
        $q = $this->db->get_where('categories', ['id' => $id]);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCategoryFields($category_id)
    {
        $q = $this->db->get_where('fields', ['category_id' => $category_id]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getFieldByID($id)
    {
        $q = $this->db->get_where('fields', ['id' => $id]);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getFields()
    {
        $q = $this->db->get_where('fields', ['category_id' => null]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getMenuCatgories()
    {
        $this->db->select('name, categories.slug, count(' . $this->db->dbprefix('topics') . '.id) as topics')
        ->join('topics', 'topics.category_id=categories.id', 'left')
        ->group_by('categories.id')
        ->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', ['parent_id' => 0, 'categories.active' => 1]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getMenuPages()
    {
        $this->db->select('title, slug')
        ->where('active', 1)
        ->order_by('order_no', 'asc');
        $q = $this->db->get('pages');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getOnlineUsers()
    {
        $time = (time() - 300);
        $this->db->where('last_activity >', $time);
        return $this->db->count_all_results('users');
    }

    public function getReceived($user_id)
    {
        $this->db->where('receiver_id', $user_id)->where('receiver_read !=', 1);
        return $this->db->count_all_results('conversations');
    }

    public function getSent($user_id)
    {
        $this->db->where('sender_id', $user_id)->where('sender_read !=', 1);
        return $this->db->count_all_results('conversations');
    }

    public function getSettings()
    {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getSitemapCategories()
    {
        $this->db->select('slug')->order_by('order_no', 'asc');
        $q = $this->db->get_where('categories', ['active' => 1, 'private' => 0]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getSitemapPages()
    {
        $this->db->select('slug, updated_at')
        ->order_by('order_no', 'asc');
        $q = $this->db->get_where('pages', ['active' => 1]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getSitemapTopics()
    {
        $this->db->select('topics.slug, category_slug, last_reply_time')
        ->join('categories', 'categories.id=topics.category_id', 'left');
        $q = $this->db->get_where('topics', ['topics.active' => 1, 'topics.protected' => 0, 'categories.active' => 1, 'categories.private' => 0]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getStaff()
    {
        $this->db->select('users.*, groups.name as group')
        ->join('groups', 'groups.id=users.group_id', 'left')
        ->group_by('users.id');
        $q = $this->db->get_where('users', ['group_id !=' => 3]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTodayBirthdays()
    {
        $md = date('-m-d');
        $this->db->select('users.username')
        ->like('dob', $md, 'before');
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTodayLogins()
    {
        $td = strtotime('today');
        $this->db->select('users.username');
        $q = $this->db->get_where('users', ['last_login >=' => $td]);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTopicBySlug($slug)
    {
        $q = $this->db->get_where('topics', ['slug' => $slug]);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTotalPosts()
    {
        $this->db->join('topics', 'topics.id=posts.topic_id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->where('categories.active', 1);
        if ($this->Settings->flag_option == 2) {
            $this->db->group_start()->where('topics.status', 1)->or_where('topics.status', 2)->group_end();
            $this->db->group_start()->where('posts.status', 1)->or_where('posts.status', 2)->group_end();
        } else {
            $this->db->where('topics.status', 1);
            $this->db->where('posts.status', 1);
        }
        return $this->db->count_all_results('posts');
    }

    public function getTotalTopics()
    {
        $this->db->join('categories', 'categories.id=topics.category_id', 'left')
        ->where('categories.active', 1);
        if ($this->Settings->flag_option == 2) {
            $this->db->group_start()->where('topics.status', 1)->or_where('topics.status', 2)->group_end();
        } else {
            $this->db->where('topics.status', 1);
        }
        return $this->db->count_all_results('topics');
    }

    public function getTotalUsers()
    {
        return $this->db->count_all('users');
    }

    public function getUnreadNum()
    {
        $user_id  = $this->session->userdata('user_id');
        $received = $this->getReceived($user_id);
        $sent     = $this->getSent($user_id);
        return ($received + $sent);
    }

    public function getUser($id = null)
    {
        if (!$id) {
            $id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('users', ['id' => $id], 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getUserActiveTopics($user_id = null)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select("{$this->db->dbprefix('topics')}.title, {$this->db->dbprefix('topics')}.slug, {$this->db->dbprefix('topics')}.category_slug, categories.name as category, p.total_posts as total_posts")
        ->join('posts', 'posts.topic_id=topics.id', 'left')
        ->join("( SELECT topic_id, (COUNT({$this->db->dbprefix('posts')}.id)-1) as total_posts FROM {$this->db->dbprefix('posts')} GROUP BY {$this->db->dbprefix('posts')}.topic_id ) p", 'p.topic_id=topics.id', 'left')
        ->join('categories', 'categories.id=topics.category_id', 'left')
        ->group_by('posts.topic_id')
        ->where('posts.created_by', $user_id)
        ->limit(5)->order_by('topics.last_reply_time', 'desc');

        $q = $this->db->get('topics');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getUserBadges($user_id)
    {
        $this->db->select('user_badges.id as id, badges.class, badges.image, badges.title')
        ->join('user_badges', 'user_badges.badge_id=badges.id', 'left')
        ->where('user_badges.user_id', $user_id);
        $q = $this->db->get('badges');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getUserByEmail($email = null)
    {
        $q = $this->db->get_where('users', ['email' => $email], 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getUserByUsername($username = null)
    {
        $q = $this->db->get_where('users', ['username' => $username], 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getUserGroup($user_id = false)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $group_id = $this->getUserGroupID($user_id);
        $q        = $this->db->get_where('groups', ['id' => $group_id], 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getUserGroupID($user_id = false)
    {
        if (!$user_id) {
            return false;
        }
        $user = $this->getUser($user_id);
        return $user->group_id;
    }

    public function getUserLastActivity($user_id)
    {
        $this->db->select('last_activity');
        $q = $this->db->get_where('users', ['id' => $user_id]);
        if ($q->num_rows() > 0) {
            $activity = $q->row();
            return $activity->last_activity;
        }
        return false;
    }

    public function getUserPosts($user_id)
    {
        $this->db->where('created_by', $user_id);
        return $this->db->count_all_results('posts');
    }

    public function getUserTopics($user_id)
    {
        $this->db->where('created_by', $user_id);
        return $this->db->count_all_results('topics');
    }

    public function insert_captcha($data)
    {
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
    }

    public function login_remembered_user()
    {
        $this->load->model('auth_model');
        if ($this->auth_model->login_remembered_user()) {
            return true;
        }
        return false;
    }

    public function total_pending_posts()
    {
        $this->db->where('status', null)->or_where('status', 0)->or_where('status', 2);
        return $this->db->count_all_results('posts');
    }

    public function total_pending_topics()
    {
        $this->db->where('status', null)->or_where('status', 0)->or_where('status', 2);
        return $this->db->count_all_results('topics');
    }

    public function updateField($id, $data)
    {
        if ($this->db->update('fields', $data, ['id' => $id])) {
            return true;
        }
        return false;
    }

    public function updateSetting($data)
    {
        $this->db->where('setting_id', '1');
        if ($this->db->update('settings', $data)) {
            return true;
        }
        return false;
    }

    public function updateUserLastActivity($user_id, $logout = null)
    {
        $current_time = $logout ? (time() - 300) : time();
        if ($this->db->update('users', ['last_activity' => $current_time], ['id' => $user_id])) {
            return true;
        }
        return false;
    }
}
