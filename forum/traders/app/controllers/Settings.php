<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('/');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->load->model('settings_model');

    }

    public function index()
    {

        $this->form_validation->set_rules('site_name', lang('site_name'), 'trim|required');
        $this->form_validation->set_rules('email', lang('default_email'), 'trim|required');
        $this->form_validation->set_rules('records_per_page', lang('records_per_page'), 'trim|required|greater_than[9]|less_than[501]');
        $this->form_validation->set_rules('protocol', lang('email_protocol'), 'trim|required');
        if ($this->input->post('protocol') == 'smtp') {
            $this->form_validation->set_rules('smtp_host', lang('smtp_host'), 'required');
            $this->form_validation->set_rules('smtp_user', lang('smtp_user'), 'required');
            $this->form_validation->set_rules('smtp_pass', lang('smtp_pass'), 'required');
            $this->form_validation->set_rules('smtp_port', lang('smtp_port'), 'required');
        }
        if ($this->input->post('wp_login')) {
            $this->form_validation->set_rules('wp_url', lang('wp_url'), 'required|valid_url');
            $this->form_validation->set_rules('wp_client_id', lang('wp_client_id'), 'required|exact_length[14]');
            $this->form_validation->set_rules('wp_secret', lang('wp_secret'), 'required|exact_length[40]');
            $host = parse_url(site_url());
            $dest = parse_url($this->input->post('wp_url'));
            if ($host['host'] != $dest['host']) {
                $this->form_validation->set_message('matches', lang('different_host'));
                $this->form_validation->set_rules('wp_url', lang('wp_url'), 'matches[site_name]');
            }
        }

        if ($this->form_validation->run() == true) {

            if ($this->input->post('rtl')) {
                if ($this->input->post('sidebar') == 'left') {
                    $sidebar = 'right';
                } elseif ($this->input->post('sidebar') == 'right') {
                    $sidebar = 'left';
                }
            } else {
                $sidebar = $this->input->post('sidebar');
            }

            $data = array('site_name' => DEMO ? 'SimpleForum' : $this->input->post('site_name'),
                'records_per_page' => $this->input->post('records_per_page'),
                'file_path' => DEMO ? 'files/' : $this->input->post('file_path'),
                'dateformat' => $this->input->post('date_format'),
                'timeformat' => $this->input->post('time_format'),
                'default_email' => DEMO ? 'noreply@sf.tecdiary.my' : $this->input->post('email'),
                'editor' => $this->input->post('editor'),
                'style' => $this->input->post('style'),
                'protocol' => DEMO ? 'mail' : $this->input->post('protocol'),
                'smtp_host' => $this->input->post('smtp_host'),
                'smtp_user' => $this->input->post('smtp_user'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_crypto' => $this->input->post('smtp_crypto'),
                'mode' => DEMO ? 'public' : $this->input->post('mode'),
                'sidebar' => $sidebar,
                'captcha' => $this->input->post('captcha'),
                'description' => $this->input->post('description'),
                'banned_words' => $this->input->post('banned_words'),
                'censore_word' => $this->input->post('censore_word'),
                'facebook' => $this->input->post('facebook'),
                'twitter' => $this->input->post('twitter'),
                'google_plus' => $this->input->post('google_plus'),
                'captcha_length' => $this->input->post('captcha_length'),
                'terms_page' => $this->input->post('terms_page'),
                'notification' => $this->input->post('notification'),
                'rtl' => $this->input->post('rtl'),
                'sorting' => $this->input->post('sorting'),
                'voting' => $this->input->post('voting'),
                'change_vote' => $this->input->post('change_vote'),
                'language' => $this->input->post('language'),
                'reply_sorting' => $this->input->post('reply_sorting'),
                'apis' => $this->input->post('apis'),
                'login_modal' => $this->input->post('login_modal'),
                'review_option' => $this->input->post('review_option'),
                'guest_reply' => $this->input->post('guest_reply'),
                'registration' => $this->input->post('registration'),
                'wp_login' => DEMO ? '0' : $this->input->post('wp_login'),
                'wp_url' => $this->input->post('wp_url'),
                'wp_client_id' => $this->input->post('wp_client_id'),
                'wp_secret' => $this->input->post('wp_secret'),
                'signature' => $this->input->post('signature'),
                'flag_option' => $this->input->post('flag_option'),
                'member_page' => $this->input->post('member_page'),
            );
            if ($this->input->post('smtp_pass')) {
                $data['smtp_pass'] = $this->input->post('smtp_pass');
            }

            if(!DEMO) {
                if ($_FILES['logo']['size'] > 0) {
                    $this->load->library('upload');
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = '500';
                    $config['max_width'] = '300';
                    $config['max_height'] = '80';
                    $config['overwrite'] = false;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('logo')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message', $error);
                        redirect('settings');
                    }
                    $data['logo'] = $this->upload->file_name;
                }
                if ($_FILES['favicon']['size'] > 0) {
                    $this->load->library('upload');
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = '100';
                    $config['max_width'] = '48';
                    $config['max_height'] = '48';
                    $config['overwrite'] = false;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('favicon')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message', $error);
                        redirect('settings');
                    }
                    $data['favicon'] = $this->upload->file_name;
                }
            }

        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            $this->session->set_flashdata('message', lang('setting_updated'));
            redirect("settings");
        } else {

            $this->data['error'] = validation_errors();
            $this->data['settings'] = $this->settings_model->getSettings();
            $this->data['smtp_pass'] = $this->data['settings']->smtp_pass;
            $this->data['page_title'] = lang('settings');
            $this->page_construct('settings/index', $this->data);
        }
    }

    public function ads()
    {

        $this->form_validation->set_rules('ad_thread', lang('ad_thread'), 'trim|required');
        $this->form_validation->set_rules('ad_sidebar', lang('ad_sidebar'), 'trim|required');
        $this->form_validation->set_rules('ad_sidebar2', lang('ad_sidebar2'), 'trim|required');
        $this->form_validation->set_rules('ad_footer', lang('ad_footer'), 'trim|required');

        if ($this->form_validation->run() == true) {

            $data = array('ad_thread' => DEMO ? 1 : $this->input->post('ad_thread'),
                'ad_thread_code' => DEMO ? '<div class="text-center"><a href="https://codecanyon.net/item/simple-invoice-manager-invoicing-made-easy/4259689?ref=Tecdiary" target="_blank"><img src="https://tecdiary.com/images/hotlink-ok/sim-ad.png" class="img-responsive" style="border-radius:3px;" alt="Simple Invoice Manager Ad"></a><small>Advertisement ('.lang('ad_thread').')</small></div>' : $this->input->post('ad_thread_code', false),
                'ad_sidebar' => DEMO ? 1 : $this->input->post('ad_sidebar'),
                'ad_sidebar_code' => DEMO ? '<div class="text-center"><a href="https://codecanyon.net/item/stock-manager-advance-with-point-of-sale-module/5403161?ref=Tecdiary" target="_blank"><img src="https://tecdiary.com/images/hotlink-ok/sma-ad.png" class="img-responsive" style="border-radius:3px;" alt="Stock Manager Advance Ad"></a><small>Advertisement ('.lang('ad_sidebar').')</small></div>' : $this->input->post('ad_sidebar_code', false),
                'ad_sidebar2' => DEMO ? 1 : $this->input->post('ad_sidebar2'),
                'ad_sidebar2_code' => DEMO ? '<div class="text-center"><a href="https://codecanyon.net/item/simple-pos-point-of-sale-made-easy/3947976?ref=Tecdiary" target="_blank"><img src="https://tecdiary.com/images/hotlink-ok/spos-ad.png" class="img-responsive" style="border-radius:3px;" alt="Simple POS Ad"></a><small>Advertisement ('.lang('ad_sidebar2').')</small></div>' : $this->input->post('ad_sidebar2_code', false),
                'ad_footer' => DEMO ? 1 : $this->input->post('ad_footer'),
                'ad_footer_code' => DEMO ? '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- sf side1 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6125604695243224"
     data-ad-slot="3905152201"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script><small class="text-center">Advertisement ('.lang('ad_footer').')</small>' : $this->input->post('ad_footer_code', false),
                'footer_code' => DEMO ? '<script>
$(document).ready(function() {
    console.info("Footer code is being added.");
});
</script>' : $this->input->post('footer_code', false),
                'alert' => DEMO ? 'Some features like image/file upload, change password, delete and some setting fields are disabled on demo and it will be reset every hour.' : $this->input->post('alert', false),

            );

        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            $this->session->set_flashdata('message', lang('ad_settings_updated'));
            redirect("settings/ads");
        } else {

            $this->data['error'] = validation_errors();
            $this->data['settings'] = $this->settings_model->getSettings();
            $this->data['page_title'] = lang('ad_settings');
            $this->page_construct('settings/ads', $this->data);
        }
    }

    public function fields($category_id = null)
    {

        $this->data['category'] = $category_id ? $this->settings_model->getCategoryByID($category_id) : null;
        $this->data['fields'] = $category_id ? $this->settings_model->getCategoryFields($category_id) : $this->settings_model->getAllFields();
        $this->data['page_title'] = lang('fields');
        $this->page_construct('settings/fields', $this->data);

    }

    public function add_field($category_id = null)
    {
        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        $this->form_validation->set_rules('type', lang('type'), 'required|trim');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'category_id' => $this->input->post('category') ? $this->input->post('category') : null,
                'required' => $this->input->post('req_field'),
                'public' => $this->input->post('public'),
                'options' => $this->input->post('options'),
                'unique_id' => $this->settings_model->generateUniqueFieldValue(),
            );
            if ($this->settings_model->addField($data) == true) {
                $this->session->set_flashdata('message', lang('field_added'));
                redirect("settings/fields");
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->data['category_id'] = $category_id;
            $this->data['page_title'] = lang('add_field');
            $this->load->view($this->theme . 'settings/add_field', $this->data);

        }

    }

    public function edit_field($id = null)
    {
        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        $this->form_validation->set_rules('type', lang('type'), 'required|trim');

        if ($this->form_validation->run() == true) {

            $data = array(
                'name' => $this->input->post('name'),
                'type' => $this->input->post('type'),
                'category_id' => $this->input->post('category') ? $this->input->post('category') : null,
                'required' => $this->input->post('req_field'),
                'public' => $this->input->post('public'),
                'options' => $this->input->post('options'),
            );
            if ($this->settings_model->updateField($id, $data)) {
                $this->session->set_flashdata('message', lang('field_updated'));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER['HTTP_REFERER']);
            }

        } else {

            $this->data['field'] = $this->settings_model->getFieldByID($id);
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('edit_field') . ' (' . $this->data['field']->name . ')';
            $this->load->view($this->theme . 'settings/edit_field', $this->data);

        }
    }

    public function delete_field($id = null)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if ($this->settings_model->deleteField($id)) {
            $this->session->set_flashdata('message', $this->lang->line("field_deleted"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', $this->lang->line("delete_failed"));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function api_keys($api_id = NULL) {

        $this->load->model('api_model');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['api_keys'] = $this->api_model->getApiKeys();
        $this->data['page_title'] = lang('api_keys');
        $this->page_construct('settings/api_keys', $this->data);

    }

    function create_api_key()
    {

        $this->form_validation->set_rules('reference', lang('reference'), 'required|trim');
        $this->form_validation->set_rules('level', lang('level'), 'required|trim');

        if ($this->form_validation->run() == true) {
            $this->load->model('api_model');
            $data = array(
                'reference' => $this->input->post('reference'),
                'user_id' => $this->session->userdata('user_id'),
                'key' => $this->api_model->generateKey(),
                'level' => $this->input->post('level'),
                'ignore_limits' => $this->input->post('ignore_limits'),
                'ip_addresses' => $this->input->post('ip_addresses'),
                'date_created' => time(),
            );

        } elseif ($this->input->post('create_api_key')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }

        if ($this->form_validation->run() == true && $this->api_model->addApiKey($data)) {

            $this->session->set_flashdata('message', lang('api_key_added'));
            redirect("settings/api_keys");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('create_api_key');
            $this->load->view($this->theme.'settings/create_api_key', $this->data);

        }
    }

    public function delete_api_key($id)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/');
        }
        $this->load->model('api_model');
        if ($this->api_model->deleteApiKey($id)) {
            $this->session->set_flashdata('message', $this->lang->line("api_key_deleted"));
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('error', $this->lang->line("delete_failed"));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function sitemap()
    {
        $categories = $this->settings_model->getSitemapCategories();
        $topics = $this->settings_model->getSitemapTopics();
        $pages = $this->settings_model->getSitemapPages();
        $map = '<?xml version="1.0" encoding="UTF-8" ?>';

        $map .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $map .= '<url>';
        $map .= '<loc>'.base_url().'</loc> ';
        $map .= '<priority>1.0</priority>';
        $map .= '<changefreq>daily</changefreq>';
        $map .= '<lastmod>'.date('Y-m-d').'</lastmod>';
        $map .= '</url>';

        foreach($categories as $category) {
            $map .= '<url>';
            $map .= '<loc>'.site_url($category->slug).'</loc> ';
            $map .= '<priority>0.8</priority>';
            $map .= '</url>';
        }

        foreach($topics as $topic) {
            $map .= '<url>';
            $map .= '<loc>'.site_url($topic->category_slug.'/'.$topic->slug).'</loc> ';
            $map .= '<priority>0.6</priority>';
            $map .= '<lastmod>'.date('Y-m-d', strtotime($topic->last_reply_time)).'</lastmod>';
            $map .= '</url>';
        }

        foreach($pages as $page) {
            $map .= '<url>';
            $map .= '<loc>'.site_url('pages/'.$page->slug).'</loc> ';
            $map .= '<priority>0.8</priority>';
            $map .= '<changefreq>yearly</changefreq>';
            if ($page->updated_at) {
                $map .= '<lastmod>'.date('Y-m-d', strtotime($page->updated_at)).'</lastmod>';
            }
            $map .= '</url>';
        }

        $map .= '</urlset>';
        file_put_contents('sitemap.xml', $map);
        header('Location: '.base_url('sitemap.xml'));
        exit;
    }

}
