<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Categories_model extends CI_Model
{


    public function __construct() {
        parent::__construct();
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id));
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function addCategory($data) {
        if ($this->db->insert('categories', $data)) {
            return true;
        }
        return false;
    }

    public function updateCategory($id, $data) {
        if ($this->db->update('categories', $data, array('id' => $id))) {
            $this->db->update('topics', array('category_slug' => $data['slug']), array('category_id' => $id));
            $this->db->update('topics', array('child_category_slug' => $data['slug']), array('child_category_id' => $id));
            return true;
        }
        return false;
    }

    public function getAllCatgories() {
        $this->db->order_by('order_no', 'asc');
        $cat = $this->db->get('categories');
        if ($cat->num_rows() > 0) {
            foreach (($cat->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getParentCategories() {
        $this->db->order_by('order_no', 'asc');
        $cat = $this->db->get_where('categories', array('parent_id' => 0));
        if ($cat->num_rows() > 0) {
            foreach (($cat->result()) as $row) {
                $data[] = $row;
            }
            return $data;
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

    public function deleteCategory($id) {
        if ($this->hasTopic($id)) {
            return false;
        }
        if ($this->db->delete('categories', array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function hasTopic($category_id) {
        $this->db->where('category_id', $category_id);
        return $this->db->count_all_results('topics');
    }

}
