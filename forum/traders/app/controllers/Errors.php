<?php defined('BASEPATH') or exit('No direct script access allowed');

class Errors extends MY_Controller
{

    public function error_404()
    {
        die();
        $this->data['page_title'] = lang('404_not_found');
        if ($this->Settings->mode == 1) {
            $this->page_construct('not_found', $this->data);
        } else {
            $this->load->view($this->theme . 'not_found', $this->data);
        }
    }

}
