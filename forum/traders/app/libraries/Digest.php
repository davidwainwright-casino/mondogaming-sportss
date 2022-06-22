<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  ============================================================================
 *  Author  : Mian Saleem
 *  Email   : saleem@tecdiary.com
 *  Web     : http://tecdiary.com
 *  ============================================================================
 */

class Digest {

    public function __construct() {

    }

    public function __get($var) {
        return get_instance()->$var;
    }

    public function sendDigest($user, $data, $date, $type)
    {
        $this->load->library('parser');
        $parse_data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'site_link' => base_url(),
            'site_name' => $this->Settings->site_name,
            'logo' => '<img src="' . base_url() . 'uploads/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
        );
        $message = $this->parser->parse_string($data, $parse_data);
        $subject = lang($type.'_digest').' - '.$this->Settings->site_name;
        $result = false;
        try {
            if ($result = $this->tec->send_email($user->email, $subject, $message)) {
                $this->db->update('users', array('digest_date' => $date), array('id' => $user->id));
            }
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
        }
        return $result;
    }

    public function getHTML($date, $type)
    {
        if ($type == 'daily') {
            $start_date = $date;
            $end_date = $date;
        } elseif ($type == 'weekly') {
            $start_date = date('Y-m-d', strtotime('monday last week'));
            $end_date = date('Y-m-d', strtotime('sunday last week'));
        } elseif ($type == 'monthly') {
            $start_date = date('Y-m-d', strtotime("first day of last month"));
            $end_date = date('Y-m-d', strtotime("last day of last month"));
        }

        $filename = './themes/' . $this->theme . 'emails/'.$type.'_digest.html';
        $modified = date("Y-m-d", filemtime($filename));
        if (file_exists($filename) && $modified > $end_date) {
            $html =  file_get_contents($filename);
        } else {
            $period = ($type == 'daily') ? $this->tec->hrsd($start_date) : $this->tec->hrsd($start_date).' - '.$this->tec->hrsd($end_date);
            $this->load->model('digest_model');
            $popular_topics = $this->digest_model->getPopularTopics($start_date, $end_date);
            if ( ! $popular_topics) {
                $popular_topics = $this->digest_model->getAllTimePopularTopics();
            }
            $replied_topics = $this->digest_model->getRepliedTopics($start_date, $end_date);
            $new_topics = $this->digest_model->getNewTopics($start_date, $end_date);
            $html = $this->update_digest_file($type, $period, $popular_topics, $replied_topics, $new_topics);
        }
        return array('start_date' => $start_date, 'data' => $html);
    }

    private function update_digest_file($type, $period, $popular_topics, $replied_topics, $new_topics)
    {
        $phtml = '';
        if ($popular_topics) {
            foreach ($popular_topics as $topic) {
                $phtml .= '
                <tr>
                    <td align="left" style="border-radius:4px;padding:10px 15px;">
                        <a target="_blank" href="'.site_url($topic->category_slug.'/'.$topic->slug).'" class="link"> '.$topic->title.' </a>
                        <p>'.$topic->description.'</p>
                    </td>
                </tr>
                ';
            }
        }
        $rhtml = '';
        if ($replied_topics) {
            foreach ($replied_topics as $topic) {
                $rhtml .= '
                <p style="font-weight:bold;line-height:1.5em;"">
                    [<a target="_blank" href="'.site_url($topic->category_slug.'/'.$topic->slug).'">'.$topic->title.'</a>]
                </p>
                ';
            }
        }
        $nhtml = '';
        if ($new_topics) {
            foreach ($new_topics as $topic) {
                $nhtml .= '
                <p style="font-weight:bold;line-height:1.5em;"">
                    [<a target="_blank" href="'.site_url($topic->category_slug.'/'.$topic->slug).'">'.$topic->title.'</a>]
                </p>
                ';
            }
        }
        $html = '';
        $html .= '
        <div class="movableContent">
            <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="padding-top:25px;padding-bottom:25px;">
                        <table cellpadding="0" cellspacing="0" border="0" align="left">
                            <tr>
                                <td align="left" style="border-radius:4px;padding:10px 15px;">

                                    <h1>'.lang('popular_threads').'</h1>

                                </td>
                            </tr>

                            '.(empty($phtml) ? lang('no_data') : $phtml).'

                        </table>
                    </td>
                </tr>
            </table>
        </div>
        ';
        if ( !empty($nhtml) || !empty($rhtml)) {
            $html .= '
            <div class="movableContent" align="center" style="text-align:center;">
                <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                    <tr>
                        <td width="100%" colspan="2" align="center" style="padding-bottom:20px;padding-top:20px;border-top:1px solid #CCC;">

                            <h1>'.lang('threads').'</h1>

                        </td>
                    </tr>
                    <tr>
                        <td width="50%" align="left" style="padding-bottom:20px;padding-top:20px;vertical-align:top;">

                            <h3>'.lang('new').'</h3>
                            '.(empty($nhtml) ? '0 '.lang('new').' '.lang('thread')  : $nhtml).'

                        </td>
                        <td width="50%" align="left" style="padding-bottom:20px;padding-top:20px;vertical-align:top;">

                            <h3>'.lang('Replied').'</h3>
                            '.(empty($rhtml) ? '0 '.lang('Replied').' '.lang('thread') : $rhtml).'

                        </td>
                    </tr>
                </table>
            </div>
            ';
        }
        $this->load->library('parser');
        $parse_data = array('content' => $html, 'period' => $period);
        $msg = file_get_contents('./themes/' . $this->theme . 'emails/digest.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        file_put_contents('./themes/' . $this->theme . 'emails/'.$type.'_digest.html', $message);
        return $message;
    }
}
