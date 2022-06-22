<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model
{


    public function __construct() {
        parent::__construct();
    }

    public function getPageByID($id) {
        $q = $this->db->get_where('pages', array('id' => $id));
        if( $q->num_rows() > 0 ) {
			return $q->row();
		}
        return FALSE;
    }

    public function getPageBySlug($slug) {
        $q = $this->db->get_where('pages', array('slug' => $slug));
        if( $q->num_rows() > 0 ) {
			return $q->row();
		}
        return FALSE;
    }

    public function addPage($data) {
        if ($this->db->insert('pages', $data)) {
            return true;
        }
        return false;
    }

    public function updatePage($id, $data) {
        if ($this->db->update('pages', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getAllPages() {
        $cat = $this->db->get('pages');
        if ($cat->num_rows() > 0) {
            foreach (($cat->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function deletePage($id) {
        if ($this->db->delete('pages', array('id' => $id))) {
            return true;
        }
        return false;
    }

}
