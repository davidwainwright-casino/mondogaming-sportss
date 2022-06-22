<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fields extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('warning', "Access denied!");
            redirect('forums');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('fields_model');
    }

    function index($category_id = NULL) {

        $this->data['fields'] = $this->fields_model->getAllFields();
        $this->data['page_title'] = lang('fields');
        $this->page_construct('fields/index', $this->data);

    }

    function add() {
        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        $this->form_validation->set_rules('type', lang('type'), 'required|trim');

        if($this->form_validation->run() == TRUE) {

            $data = array(
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'category_id' => $this->input->post('category') ? $this->input->post('category') : NULL,
                'required' => $this->input->post('field_req'),
                'options' => $this->input->post('options'),
                'unique_id' => $this->fields_model->generateUniqueFieldValue(),
            );
            if($this->fields_model->addField($data) == TRUE) {
                $this->session->set_flashdata('message', lang('field_added'));
                redirect("fields");
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->fields_model->getAllCategories();
            $this->data['page_title'] = lang('add_field');
            $this->load->view($this->theme.'fields/add', $this->data);

        }

    }

    function edit($id = NULL) {
        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        $this->form_validation->set_rules('type', lang('type'), 'required|trim');

        if($this->form_validation->run() == TRUE) {

            $data = array(
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'category_id' => $this->input->post('category') ? $this->input->post('category') : NULL,
                'required' => $this->input->post('field_req'),
                'options' => $this->input->post('options'),
            );
            if($this->fields_model->updateField($id, $data)) {
                $this->session->set_flashdata('message', lang('field_updated'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            }

        } else {
            
            $this->data['field'] = $this->fields_model->getFieldByID($id);
            $this->data['categories'] = $this->fields_model->getAllCategories();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('edit_field').' ('.$this->data['field']->name.')';
            $this->load->view($this->theme.'fields/edit', $this->data);

        }
    }

    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if($this->fields_model->deleteField($id)) { 
            $this->session->set_flashdata('message', $this->lang->line("field_deleted"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', $this->lang->line("delete_failed"));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

}
