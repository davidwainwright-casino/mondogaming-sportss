<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller {

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
        $this->load->model('pages_model');
    }

    function index() {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['pages'] = $this->pages_model->getAllPages();
        $this->data['page_title'] = lang('pages');
        $this->page_construct('pages/index', $this->data);

    }

    function add()
    {

        $this->form_validation->set_rules('title', lang('title'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('body', lang('body'), 'required');
        $this->form_validation->set_rules('order_no', lang('order_no'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('slug', lang('slug'), 'trim|required|is_unique[pages.slug]|alpha_dash');

        if ($this->form_validation->run() == true) {

            $data = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'body' => $this->tec->encode_html($this->input->post('body', TRUE)),
                'slug' => $this->tec->title_slug($this->input->post('slug')),
                'order_no' => $this->input->post('order_no'),
                'active' => $this->input->post('active'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

        } elseif ($this->input->post('add_page')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if ($this->form_validation->run() == true && $this->pages_model->addPage($data)) {

            $this->session->set_flashdata('message', lang('page_added'));
            redirect("pages");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('new_page');
            $this->load->view($this->theme.'pages/add', $this->data);

        }
    }

    function edit($id = NULL)
    {

        $page = $this->pages_model->getPageByID($id);
        $this->form_validation->set_rules('title', lang('title'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('body', lang('body'), 'required');
        $this->form_validation->set_rules('order_no', lang('order_no'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('slug', lang('slug'), 'trim|required|alpha_dash');
        if($page->slug != $this->input->post('slug')) {
            $this->form_validation->set_rules('slug', lang('slug'), 'is_unique[pages.slug]');
        }

        if ($this->form_validation->run() == true) {

            $data = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'body' => $this->tec->encode_html($this->input->post('body', TRUE)),
                'slug' => $this->tec->title_slug($this->input->post('slug')),
                'order_no' => $this->input->post('order_no'),
                'active' => $this->input->post('active'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

        } elseif ($this->input->post('edit_page')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'pages');
        }

        if ($this->form_validation->run() == true && $this->pages_model->updatePage($id, $data)) {

            $this->session->set_flashdata('message', lang('page_updatedd'));
            redirect("pages");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('edit_page');
            $this->data['page'] = $page;
            $this->load->view($this->theme.'pages/edit', $this->data);

        }
    }

    function delete($id = NULL)
    {

        if(DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->pages_model->deletePage($id)) {
            $this->session->set_flashdata('message', lang('page_deleted'));
            redirect("pages");
        }

    }

}
