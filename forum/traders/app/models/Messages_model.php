<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getConversationByID($con_id) {
        $this->db->select("{$this->db->dbprefix('conversations')}.*, users.avatar, users.username, users.gender as user_gender")
        ->join('users', 'users.id=conversations.sender_id', 'left');
        $q = $this->db->get_where('conversations', array('conversations.id' => $con_id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function total_conversations($search = NULL) {
        $user_id = $this->session->userdata('user_id');

        if ($search) {
            $this->db->like('subject', $search, 'both');
        }

        return $this->db->count_all_results("conversations");

    }

    public function get_conversations($limit, $start, $search = NULL) {
        $user_id = $this->session->userdata('user_id');
        $this->db->select("{$this->db->dbprefix('conversations')}.*, s.username as from_user, r.username as to_user, COUNT({$this->db->dbprefix('messages')}.id) as total_messages")
        ->join('users s', 's.id=conversations.sender_id', 'left')
        ->join('users r', 'r.id=conversations.receiver_id', 'left')
        ->join('messages', 'messages.con_id=conversations.id', 'left')
        ->group_start()
        ->where('conversations.receiver_id', $user_id)->where('conversations.receiver_delete', NULL)
        ->group_end()
        ->or_group_start()
        ->where('conversations.sender_id', $user_id)->where('conversations.sender_delete', NULL)
        ->group_end();

        $this->db->group_by('conversations.id')
        ->limit($limit, $start)
        ->order_by('conversations.important desc, conversations.last_reply_time desc, conversations.id desc');

        if ($search) {
            $this->db->like('subject', $search, 'both');
        }

        $q = $this->db->get('conversations');
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function total_conversation_messages($con_id) {
            $this->db->where('con_id', $con_id);
            return $this->db->count_all_results("messages");
    }

    public function get_messages($limit, $start, $con_id) {
        $this->db->select("{$this->db->dbprefix('messages')}.*, users.id as user_id, users.avatar, users.username, users.first_name, users.last_name, users.gender as user_gender")
        ->join('users', 'users.id=messages.user_id', 'left')
        ->group_by('messages.id')
        ->limit($limit, ($start))->order_by('messages.id', 'asc');
        $q = $this->db->get_where('messages', array('messages.con_id' => $con_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function updateRead($con_id, $data) {
        if ($this->db->update('conversations', $data, array('id' => $con_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function addConversation($data, $message) {
        if ($this->db->insert('conversations', $data)) {
            $message['con_id'] = $this->db->insert_id();
            $this->db->insert('messages', $message);
            return true;
        }
        return false;
    }

    public function addMessage($message, $con_data) {
        if ($this->db->insert('messages', $message) &&
        $this->db->update('conversations', $con_data, array('id' => $message['con_id']))) {
            return true;
        }
        return false;
    }

    public function deleteConversation($id, $user_id) {
        $conversation = $this->getConversationByID($id);
        if ($conversation->receiver_id == $user_id) {
            if ($conversation->sender_delete) {
                $this->db->delete('conversations', array('id' => $id));
                $this->db->delete('messages', array('con_id' => $id));
            } else {
                $this->db->update('conversations', array('receiver_read' => 1, 'receiver_delete' => 1), array('id' => $id));
            }
        } elseif ($conversation->sender_id == $user_id) {
            if ($conversation->receiver_delete) {
                $this->db->delete('conversations', array('id' => $id));
                $this->db->delete('messages', array('con_id' => $id));
            } else {
                $this->db->update('conversations', array('sender_read' => 1, 'sender_delete' => 1), array('id' => $id));
            }
        }
        return true;
    }

}
