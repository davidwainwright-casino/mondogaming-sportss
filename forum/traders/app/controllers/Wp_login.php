<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_login extends MY_Controller {

	function __construct() {
        parent::__construct();
    }

	public function index() {
		if ($this->Settings->wp_login) {
			$host = parse_url(site_url());
			$dest = parse_url($this->Settings->wp_url);
			if ($host['host'] != $dest['host']) {
				$this->session->set_flashdata('error', lang('different_host'));
				redirect('login?wp_checked=1');
			}

			if ($this->input->get('logout')) {
				$logout_url = $this->Settings->wp_url.'?SFRequest=logout&return_url='.site_url();
				header('Location: '.$logout_url);
				exit();
			} else {

				$ctime = time();
				$signature = md5($ctime.$this->Settings->wp_secret);
				$url = $this->Settings->wp_url;
				$queryData = array(
					'SFRequest' 	=> 'connect',
					'client_id' 	=> $this->Settings->wp_client_id,
					'secret' 		=> $this->Settings->wp_secret,
					'return_url' 	=> site_url('wp_login'),
					'signature' 	=> $signature,
					'_' 			=> $ctime
					);
				$agent= 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0';
				$wp_data = $this->get_contents($url, $queryData, $agent);
				$data = json_decode($wp_data);

				if ($data->loggedin) {
					$return_url = $this->input->get('return_url') ? $this->input->get('return_url') : ($this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : '/');
					$this->login($data, $return_url);
				} else {
					if ($this->input->get('return_url')) {
						redirect($this->input->get('return_url', TRUE));
					} else {
						header('Location: '.$this->Settings->wp_url.'wp-login.php?redirect_to='.site_url('wp_login'));
						exit();
					}
				}

			}
		} else {
			$this->session->set_flashdata('error', lang('wp_login_disabled'));
            redirect('/');
		}
	}

	private function login($data, $return_url) {

		$this->load->model('auth_model');
		$this->load->library('ion_auth');
		$user_profile = $data->user;

		if ($user = $this->settings_model->getUserByEmail($user_profile->email)) {

			if ($user->active < 1) {
				$this->session->set_flashdata('error', lang('login_unsuccessful_not_active'));
				redirect('login');
			}
			$this->auth_model->update_last_login($user->id);
			$this->auth_model->update_last_login_ip($user->id);
			$user->avatar = $this->get_gravatar($user->email);
			$this->auth_model->set_session($user);
			$this->session->set_userdata('gravatar', 1);
			$this->session->set_userdata('up_logout_url', $data->up_logout_url);
			$this->session->set_flashdata('message', $this->ion_auth->messages());

		} else {

			$this->load->helper('string');
			$dob = NULL;
			if (isset($user_profile->dob) && ! empty($user_profile->dob)) {
				$dob = $user_profile->dob;
			}
			$username = $user_profile->username;
			$password = random_string('alnum', 8);
			$email = $user_profile->email;
			$avatar = $this->get_gravatar($user->email);
			$aname = md5($email). '.jpg';
			copy($avatar, '/uploads/avatars/thumbs/'.$aname);
			copy($avatar, '/uploads/avatars/'.$aname);
			$additional_data = array(
				'first_name' => $user_profile->first_name,
				'last_name' => $user_profile->last_name,
				'gender' => $user_profile->gender,
				'dob' => $dob,
				'group_id' => 3,
				'avatar_url' => 1,
				'avatar' => $aname
				);
			if ($this->ion_auth->register($username, $password, $email, $additional_data, TRUE, TRUE)) {

				if ($this->ion_auth->login($email, $password)) {
					if ($this->Settings->mode == 2) {
						if ( ! $this->tec->in_group('admin')) {
							$this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
							redirect($return_url);
						}
					}
					$user->avatar = $this->get_gravatar($user->email);
					$this->session->set_userdata('avatar', $user->avatar);
					$this->session->set_userdata('gravatar', 1);

					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect($return_url);
				} else {
					$this->session->set_flashdata('error', lang('user_login_failed').' '.$this->ion_auth->errors());
					redirect($return_url);
				}

			} else {
				$this->session->set_flashdata('error', lang('user_registeration_failed').' '.$this->ion_auth->errors());
				redirect($return_url);
			}
		}

		redirect($return_url);
	}

	private function get_contents($url, $queryData, $agent) {

		if (!empty($queryData)) {
			$s = sizeof($queryData);
			$r = 1;
			$query = '?';
			foreach ($queryData as $key => $value) {
				if ($s == $r) {
					$query .= $key.'='.$value;
				} else {
					$query .= $key.'='.$value.'&';
				}
				$r++;
			}
		} else {
			$query = '';
		}

		$curl = curl_init();

		$cookies = array();
		foreach ($_COOKIE as $key => $value) {
			if ($key != 'Array') {
				$cookies[] = $key . '=' . $value;
			}
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url.$query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			// CURLOPT_SSL_VERIFYPEER => false,
			// CURLOPT_VERBOSE => true,
			// CURLOPT_USERAGENT => $agent,
			// CURLOPT_COOKIEFILE => '/',
			// CURLOPT_REFERER => true,
			CURLOPT_REFERER => site_url('wp_login'),
			CURLOPT_COOKIE => implode(';', $cookies)
			));
		

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			return array('error' => 1, 'msg' => "cURL Error #:" . $err);
		} else {
			return $response;
		}
	}

	private function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
	    $url = 'https://www.gravatar.com/avatar/';
	    $url .= md5( strtolower( trim( $email ) ) );
	    $url .= "?s=$s&d=$d&r=$r";
	    if ( $img ) {
	        $url = '<img src="' . $url . '"';
	        foreach ( $atts as $key => $val )
	            $url .= ' ' . $key . '="' . $val . '"';
	        $url .= ' />';
	    }
	    return $url;
	}

}