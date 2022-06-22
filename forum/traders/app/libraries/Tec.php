<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 *  ============================================================================
 *  Author  : Mian Saleem
 *  Email   : saleem@tecdiary.com
 *  Web     : http://tecdiary.com
 *  ============================================================================
 */

class Tec
{
    public function __construct()
    {
        if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code')) {
            $this->load->model('settings_model');
            if ($this->settings_model->login_remembered_user()) {
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function body_censor($str)
    {
        $disallowed = explode(',', $this->Settings->banned_words);
        $last_text  = end($disallowed);
        if (empty($last_text)) {
            array_pop($disallowed);
        }
        $censored    = array_map('trim', $disallowed);
        $replacement = $this->Settings->censore_word ? $this->Settings->censore_word : '[censored]';
        $this->load->helper('text');
        $checked_str = word_censor($str, $censored, $replacement);
        return $checked_str;
    }

    public function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (mb_strlen($str) < $n) {
            return $str;
        }

        $str = preg_replace('/ {2,}/', ' ', str_replace(["\r", "\n", "\t", "\x0B", "\x0C"], ' ', $str));
        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            if (mb_strlen($out) >= $n) {
                $out = trim($out);
                return (mb_strlen($out) === mb_strlen($str)) ? $out : $out . $end_char;
            }
        }
    }

    public function checkLastActivit($user_id)
    {
        if ($user_id == $this->session->userdata('user_id')) {
            return true;
        }
        return (bool) ($this->settings_model->getUserLastActivity($user_id) > (time() - 300));
    }

    public function cleanMetaTags($str)
    {
        return str_replace(['<meta', '&lt;meta', 'refresh', 'http-equiv'], ['<span><meta</span>', '<span>&lt;meta</span>', '<span>refresh</span>', '<span>http-equiv</span>'], $str);
    }

    public function create_captcha()
    {
        $this->load->helper('captcha');
        $vals = [
            'img_path'    => './uploads/captcha/',
            'img_url'     => site_url() . 'uploads/captcha/',
            'img_width'   => 220,
            'img_height'  => 34,
            'word_length' => $this->Settings->captcha_length,
            'colors'      => ['background' => [255, 255, 255], 'border' => [204, 204, 204], 'text' => [102, 102, 102], 'grid' => [204, 204, 204]],
        ];
        $cap  = create_captcha($vals);
        $data = [
            'captcha_time' => $cap['time'],
            'ip_address'   => $this->input->ip_address(),
            'word'         => $cap['word'],
        ];
        $this->settings_model->insert_captcha($data);
        return $cap['image'];
    }

    public function decode_html($str)
    {
        return $this->encryptMetaTags(html_entity_decode(html_entity_decode(htmlspecialchars_decode($str), ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8')));
    }

    public function display_contents($body)
    {
        if ($this->Settings->editor == 'simpledme') {
            $this->load->library('parsedown');
            $contents = $this->parsedown->text($this->tec->decode_html($body));
        } elseif ($this->Settings->editor == 'sceditor') {
            $this->load->library('bbcode');
            $this->load->helper('emoticons');
            $emoticons = get_emoticons_array();
            $contents  = parse_smileys($this->tec->decode_html($this->tec->decode_html($this->bbcode->tohtml($body, true))), base_url('themes/default/assets/components/sceditor/emoticons/'), $emoticons);
        } else {
            $contents = nl2br(stripcslashes($this->tec->decode_html($body)));
        }
        return $contents;
    }

    public function encode_html($str)
    {
        return html_escape(
            htmlentities(
                $this->cleanMetaTags(
                    strip_tags(
                        $this->encryptMetaTags($str),
                        '<span><a><br><p><b><i><u><img><blockquote><ul><ol><li><hr><pre><code><strong><em><table><tr><td><th><tbody><h3><h4><h5><h6>'
                    )
                )
            )
        );
    }

    public function encryptMetaTags($str)
    {
        $str = preg_replace_callback(
            '|\<pre[^>]*>\<code[^>]*>(.+?)\</code>\</pre>|',
            function ($m) {
                $ptag = explode('>', $m[0], 2);
                $ctag = explode('>', $ptag[1], 2);
                preg_match_all('#<pre[^>]*><code[^>]*>(.+?)</code></pre>#', $m[0], $contents);
                return $ptag[0] . '>' . $ctag[0] . '>' . htmlentities($contents[1][0]) . '</code></pre>';
            },
            $str
        );
        $str = preg_replace_callback(
            '|\<meta[^>]*>|',
            function ($m) {
                return ((strpos($m[0], '<') !== false) ? htmlentities($m[0]) : $m[0]) ;
            },
            $str
        );
        $str = preg_replace_callback(
            '|\<code[^>]*>(.+?)\</code>|',
            function ($matches) {
                $tag = explode('>', $matches[0], 2);
                preg_match_all('#<code[^>]*>(.+?)</code>#', $matches[0], $contents);
                return $tag[0] . '> ' . ((strpos($contents[1][0], '<') !== false) ? htmlentities($contents[1][0]) : $contents[1][0]) . '</code>';
            },
            $str
        );
        return str_replace('<meta', '&lt;meta', $str);
    }

    public function fields($fields = null)
    {
        if (!$fields) {
            return false;
        }
        $html  = '';
        $names = [];
        foreach ($fields as $field) {
            $names[] = $field->unique_id;
            if ($field->type == 'text') {
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field->unique_id . '">' . $field->name . '</label>';
                $html .= form_input($field->unique_id, set_value($field->unique_id, (isset($field->value) ? $field->value : false)), 'class="form-control tip" id="' . $field->unique_id . '" ' . ($field->required ? 'required="required" data-fv-notempty-message="' . $field->name . ' ' . lang('is_required') . '"' : ''));
                $html .= '</div>';
            } elseif ($field->type == 'url') {
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field->unique_id . '">' . $field->name . '</label>';
                $html .= form_input($field->unique_id, set_value($field->unique_id, (isset($field->value) ? $field->value : false)), 'class="form-control tip" id="' . $field->unique_id . '" data-fv-uri="true" data-fv-uri-message="' . lang('input_invalid') . '" ' . ($field->required ? 'required="required" data-fv-notempty-message="' . $field->name . ' ' . lang('is_required') . '"' : ''));
                $html .= '</div>';
            } elseif ($field->type == 'select') {
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field->unique_id . '">' . $field->name . '</label>';
                $cfoptions = explode('|', $field->options);
                foreach ($cfoptions as $key => $value) {
                    $options[$value] = $value;
                }
                $html .= form_dropdown($field->unique_id, $options, set_value($field->unique_id, (isset($field->value) ? $field->value : false)), 'class="form-control tip select2" style="width:100%;" id="' . $field->unique_id . '" ' . ($field->required ? 'required="required" data-fv-notempty-message="' . $field->name . ' ' . lang('is_required') . '"' : ''));
                $html .= '</div>';
            } elseif ($field->type == 'checkbox') {
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field->unique_id . '">' . $field->name . '</label>';
                $cfoptions       = explode('|', $field->options);
                $selected_values = (isset($field->value) ? explode('_|_', $field->value) : []);
                foreach ($cfoptions as $key => $value) {
                    $html .= '<label class="checkbox" for="' . $field->unique_id . $key . '">';
                    $html .= '<input type="checkbox" name="' . $field->unique_id . '[]" value="' . $value . '" ' . (in_array($value, $selected_values) ? 'checked="checked"' : '') . ' id="' . $field->unique_id . $key . '" ' . ($field->required ? 'required="required" data-fv-notempty-message="' . $field->name . ' ' . lang('is_required') . '"' : '') . '/>';
                    $html .= '<span class="icon"><i class="fa fa-check"></i></span>';
                    $html .= $value;
                    $html .= '</label>';
                }
                $html .= '</div>';
            } elseif ($field->type == 'radio') {
                $html .= '<div class="form-group">';
                $html .= '<label for="' . $field->unique_id . '">' . $field->name . '</label>';
                $cfoptions      = explode('|', $field->options);
                $selected_value = (isset($field->value) ? $field->value : false);
                foreach ($cfoptions as $key => $value) {
                    $html .= '<label class="radio" for="' . $field->unique_id . $key . '">';
                    $html .= '<input type="radio" name="' . $field->unique_id . '" value="' . $value . '" ' . (($selected_value == $value) ? 'checked="checked" ' : '') . ' id="' . $field->unique_id . $key . '" ' . ($field->required ? 'required="required" data-fv-notempty-message="' . $field->name . ' ' . lang('is_required') . '"' : '') . '/>';
                    $html .= '<span class="icon"><i class="fa fa-circle"></i></span>';
                    $html .= $value;
                    $html .= '</label>';
                }
                $html .= '</div>';
            }
        }
        return ['html' => $html, 'names' => $names];
    }

    public function getTimezoneAbbr()
    {
        $dateTime = new DateTime();
        $dateTime->setTimeZone(new DateTimeZone(TIMEZONE));
        return $dateTime->format('T');
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->Settings->dateformat . ' ' . $this->Settings->timeformat, strtotime($ldate));
        }
        return false;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->Settings->dateformat, strtotime($sdate));
        }
        return FASLE;
    }

    public function in_group($check_group, $id = false)
    {
        $id || $id = $this->session->userdata('user_id');
        $group     = $this->settings_model->getUserGroup($id);
        if ($group && $group->name === $check_group) {
            return true;
        }
        return false;
    }

    public function is_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function logged_in()
    {
        return (bool) $this->session->userdata('identity');
    }

    public function new_topic_nofity($data)
    {
        if ($this->Settings->notification) {
            $users = $this->Settings->notification == 1 ? $this->settings_model->getAdmins() : $this->settings_model->getStaff();
            foreach ($users as $user) {
                if ($user->id != $this->session->userdata('user_id') && $user->subscription == 3) {
                    $this->load->library('parser');
                    $parse_data = [
                        'first_name'  => $user->first_name,
                        'last_name'   => $user->last_name,
                        'email'       => $user->email,
                        'title'       => $data['title'],
                        'thread_link' => site_url($data['category_slug'] . '/' . $data['slug']),
                        'site_link'   => base_url(),
                        'site_name'   => $this->Settings->site_name,
                        'logo'        => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                    ];
                    $msg     = file_get_contents('./themes/' . $this->theme . 'emails/topic_created.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = lang('created') . ': ' . $data['title'] . ' - ' . $this->Settings->site_name;
                    try {
                        $this->send_email($user->email, $subject, $message);
                    } catch (\Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
            }
        }
    }

    public function notify($topic_id)
    {
        $this->load->model('helper_model');
        $subscribers = $this->helper_model->getSubscribers($topic_id);
        if (!empty($subscribers)) {
            foreach ($subscribers as $subscriber) {
                if ($subscriber['subscription'] == 3) {
                    $this->load->library('parser');
                    $parse_data = [
                        'first_name'  => $subscriber['first_name'],
                        'last_name'   => $subscriber['last_name'],
                        'email'       => $subscriber['email'],
                        'title'       => $subscriber['title'],
                        'thread_link' => site_url($subscriber['category_slug'] . '/' . $subscriber['slug']),
                        'site_link'   => base_url(),
                        'site_name'   => $this->Settings->site_name,
                        'logo'        => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                    ];
                    $msg     = file_get_contents('./themes/' . $this->theme . 'emails/topic_replied.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = lang('Replied') . ': ' . $subscriber['title'] . ' - ' . $this->Settings->site_name;
                    try {
                        $this->send_email($subscriber['email'], $subject, $message);
                    } catch (\Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }
            }
        }
    }

    public function notify_user($user_id)
    {
        if ($receiver = $this->settings_model->getUser($user_id)) {
            $sender = $this->settings_model->getUser($this->session->userdata('user_id'));
            $this->load->library('parser');
            $parse_data = [
                'first_name' => $receiver->first_name,
                'last_name'  => $receiver->last_name,
                'email'      => $receiver->email,
                'title'      => lang('new_message_received'),
                'sender'     => $sender->first_name . ' ' . $sender->last_name . ' (' . $sender->username . ')',
                'site_link'  => base_url(),
                'site_name'  => $this->Settings->site_name,
                'logo'       => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
            ];
            $msg     = file_get_contents('./themes/' . $this->theme . 'emails/message_received.html');
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = lang('new_message_received') . ' - ' . $this->Settings->site_name;
            try {
                $this->send_email($receiver->email, $subject, $message);
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
            }
        }
    }

    public function pending_user_notification($id)
    {
        $users    = $this->settings_model->getAdmins();
        $new_user = $this->settings_model->getUser($id);
        foreach ($users as $user) {
            if ($user->id != $this->session->userdata('user_id')) {
                $this->load->library('parser');
                $parse_data = [
                    'first_name'      => $user->first_name,
                    'last_name'       => $user->last_name,
                    'email'           => $user->email,
                    'user_first_name' => $new_user->first_name,
                    'user_last_name'  => $new_user->last_name,
                    'user_email'      => $new_user->email,
                    'site_link'       => base_url(),
                    'site_name'       => $this->Settings->site_name,
                    'logo'            => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>',
                ];
                $msg     = file_get_contents('./themes/' . $this->theme . 'emails/pending_user.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = lang('pending_user_subject') . ' - ' . $this->Settings->site_name;
                try {
                    $this->send_email($user->email, $subject, $message);
                } catch (\Exception $e) {
                    log_message('error', $e->getMessage());
                }
            }
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo '<pre>';
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo '</pre>';
        die();
    }

    public function send_digest($date)
    {
        $this->load->library('digest');
        $daily_digest   = $this->digest->getHTML($date, 'daily');
        $weekly_digest  = $this->digest->getHTML($date, 'weekly');
        $monthly_digest = $this->digest->getHTML($date, 'monthly');
        $users          = $this->settings_model->getAllUsers();

        foreach ($users as $user) {
            if (!$user->subscription && $user->digest_date < $daily_digest['start_date']) {
                $this->digest->sendDigest($user, $daily_digest['data'], $date, 'daily');
            } elseif ($user->subscription == 1 && $user->digest_date < $weekly_digest['start_date']) {
                $this->digest->sendDigest($user, $weekly_digest['data'], $date, 'weekly');
            } elseif ($user->subscription == 2 && $user->digest_date < $monthly_digest['start_date']) {
                $this->digest->sendDigest($user, $monthly_digest['data'], $date, 'monthly');
            }
        }
        if ($this->db->update('settings', ['digest_date' => $date], ['setting_id' => 1])) {
            return true;
        }
        return false;
    }

    public function send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    {
        list($user, $domain) = explode('@', $to);
        if ($domain != 'tecdiary.com' || DEMO) {
            $result = false;
            $this->load->library('tec_mail');
            try {
                $result = $this->tec_mail->send_mail($to, $subject, $message, $from, $from_name, $attachment, $cc, $bcc);
            } catch (\Exception $e) {
                log_message('error', 'Mail Error: ' . $e->getMessage());
                $this->session->set_flashdata('error', 'Mail Error: ' . $e->getMessage());
                // throw new \Exception($e->getMessage());
            }
            return $result;
        }
        return false;
    }

    public function send_json($data)
    {
        header('Content-Type: application/json');
        die(json_encode($data));
        exit;
    }

    public function short_num($n, $precision = 1)
    {
        $k = pow(10, 3);
        $m = pow(10, 6);
        $b = pow(10, 9);
        if ($n < $k) {
            $sn = number_format($n);
        } elseif ($n < $m) {
            $sn = number_format($n / $k, $precision) . 'K';
        } elseif ($n < $b) {
            $sn = number_format($n / $m, $precision) . 'M';
        } else {
            $sn = number_format($n / $b, $precision) . 'B';
        }

        return $sn;
    }

    public function slug($slug, $r = 1)
    {
        if ($this->settings_model->getTopicBySlug($slug)) {
            $slug = $slug . $r;
            $r++;
            $this->slug($slug, $r);
        }
        return $slug;
    }

    public function timespan($date)
    {
        return timespan(mysql_to_unix($date), now(), 1);
    }

    public function title_slug($title)
    {
        $this->load->helper('text');
        $title = url_title(convert_accented_characters($title), '-', true);
        return $title;
    }

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return false;
    }

    public function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) === '') {
            return $str;
        }
        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);
        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }
        return rtrim($matches[0]) . $end_char;
    }
}
