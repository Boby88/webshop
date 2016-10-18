<?php

class Login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('login/facebook');
	}

	/**
	 * @desc Load login form or user menu pagelet into navbar
	 */
	public function render_login_form_or_user_menu()
	{
		$userdata = $this->session->userdata;
		if(isset($userdata['is_logged_in']) && $userdata['is_logged_in'])
		{
			$userinfo = $userdata['userinfo'];
			$memberinfo = $userdata['memberinfo'];

			$data['user'] = array(
				'profile'			=> ($userinfo != NULL && $userinfo->first_name != '' && $userinfo->last_name != '') ? $userinfo->first_name . ' ' . $userinfo->last_name : $memberinfo->email,
				'photo_url'		=> ($userinfo != NULL && $userinfo->photo_url) ? $userinfo->photo_url : base_url('assets/img/empty_user_25x25.jpg')
			);
			$this->load->view('user_menu', $data);
		}
		else
		{
			$this->load->view('login_form');
		}
	}

	/**
	 * Validate login credentials
	 * Called by AJAX
	 *
	 * Success: call log_in method
	 * Failure: display errors in JSON string
	 */
	public function validate_credentials()
	{
		$this->load->library('form_validation');

		// field name, error message, validation rules
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');

		// validation
		if($this->form_validation->run() == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
				'errors'	=> array(
					'email_address' => form_error('email_address'),
					'password'			=> form_error('password')
				)
			)));
		}
		else
		{
			$this->load->model('membership_model');
			// esisting member
			if($query = $this->membership_model->validate()) // if the user's credentials validated...
			{
				$data = array(
					'email' => $this->input->post('email')
				);
				$this->log_in('email', $data['email'], 'site/members_area');
			}
			else // incorrect username or password
			{
				header('HTTP/1.1 400 Validation error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
					'errors' => array(
						'__error__'	=> '<div class="dynamic-response text-danger">Invalid user credentials</div>'
					)
				)));
			}
		}
	}

	/**
	 * Check session data
	 * break run if not logged in
	 */
	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';
			redirect('site', redirect);
			die();
			//$this->load->view('login_form');
		}
	}

	/**
	 * Load signup view
	 */
	public function signup()
	{
		$data['main_content'] = 'signup_form';
		$this->load->view('site/page', $data);
	}

	/**
	 * Validate signup form
	 * Create new member
	 * Called by AJAX
	 *
	 * Success: Send email verification and give redirect url to new_member view
	 * Failure: Display errors in JSON string
	 */
	public function create_member()
	{
		$this->load->library('form_validation');

		// REQUIRED info
		// field name, error message, validation rules
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|is_unique[members.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

		$this->form_validation->set_message('is_unique', 'This email is already taken.');

		if($this->form_validation->run() == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
				'errors'	=> array(
					'email_address' => form_error('email_address'),
					'password'			=> form_error('password'),
					'password2'			=> form_error('password2')
				)
			)));
		}

		else
		{
			$this->load->model('membership_model');

			if($query = $this->membership_model->create_member())
			{
				// TODO:  send verification email
				$email = $this->input->post('email_address');
				Modules::run('emails/send_verification_email', $email);

				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'redirect_url'	=> base_url('login/new_member')
				)));
			}
			else
			{
				header('HTTP/1.1 400 Registration error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
					'errors' => array(
						'__error__'	=> '<div class="dynamic-response text-danger">Couldn\'t create your profile</div>'
					)
				)));
			}
		}
	}

	/**
	 * Display information for newly registered members
	 * Ask them to verify their email address
	 */
	public function new_member()
	{
		$data['main_content'] = 'signup_successful';
		$this->load->view('site/page', $data);
	}

	/**
	 * Display information for visitor about the failure of registration
	 */
	public function registration_failed()
	{
		$data['main_content'] = 'signup_failed';
		$this->load->view('site/page', $data);
	}

	// FACEBOOK
	/**
	 * Facebook login / signup / connect
	 *
	 * Called via web redirect
	 * Ask permission on Facebook
	 * Authenticate user
	 * Login / Connect or Register user
	 */
	public function fb_login()
	{
		$data['fb_user'] = array();

		// Check if user is logged in
		if ($this->facebook->is_authenticated())
		{
			// User logged in, get user details
			$user = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,picture.width(100).height(100)');
			if (!isset($user['error']))
			{
				$data['fb_user'] = $user;
			}
		}

		// check requested data (id, email)
		if (isset($data['fb_user']['id'])) {
			$this->load->model('membership_model');
			if ($this->membership_model->fb_validate('ID', $data['fb_user']['id']))
			{
				// LOGIN
				$this->log_in('facebook_id', $data['fb_user']['id'], 'site/members_area', TRUE);
			}

			// CHECK EMAIL
			if (isset($data['fb_user']['email']) && filter_var($data['fb_user']['email'], FILTER_VALIDATE_EMAIL))
			{
				if ($this->membership_model->fb_validate('EMAIL', $data['fb_user']['email']))
				{
					// email already saved CONNECT
					$data['verify_email_address'] = TRUE;
					$this->load_facebook_form($data);
				}
				else
				{
					// REGISTERING
					if ($member = $this->membership_model->create_facebook_member($data['fb_user']))
					{
						// succesfully registered / new_member
						$this->new_member();
					}
					else
					{
						$this->registration_failed();
					}
				}
			}
			else
			{
				// missing email - request email
				$this->load_facebook_form($data);
			}
		}
	}

	 /**
	 * During Facebook connect verify registered account's email
	 * ask user to enter the registered email address
	 * if entered and facebook email are equal => connect accounts
	 */
	public function request_email()
	{
		$this->verify_email();

		// GET cached fb info
		$fb_user = $this->session->flashdata('fb_user');
		$email = $this->input->post('email_address');
		// finish connect
		if(strtolower($email) === strtolower($fb_user['email']))
		{
			$this->load->model('membership_model');
			// TODO error handling
			$this->membership_model->connect_facebook_account($fb_user);
		}
		// login
		$this->log_in('facebook_id', $fb_user['id'], 'site/members_area');
	}

	/**
	 * During Facebook registration email is not granted
	 * so ask user to enter a valid email address
	 *
	 * call register, login and show new_user view
	 */
	public function missing_email()
	{
		$this->verify_email();

		// GET cached fb info
		$fb_user = $this->session->flashdata('fb_user');

		// finish registration
		$this->load->model('membership_model');
		$this->membership_model->create_facebook_member($fb_user);
		// login and redirect to new user view
		$this->log_in('facebook_id', $fb_user['id'], 'login/new_member', TRUE);
	}

	/**
	 * Logout
	 *
	 * Destroy session and redirect user to mainpage
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('site', redirect);
	}

	/**
	 * Log in user
	 * Set session and redirect or give response for AJAX
	 *
	 * @param array $data - information stored in session about the logged in user
	 * @param string $url - next url after log in
	 * @param boolean $redirect - need to redirect user to given url string or display JSON string for JAX request
	 */
	private function log_in($key, $value, $url, $redirect = FALSE)
	{
		$this->load->model('membership_model');
		$this->load->model('user/user_model');

		$data['is_logged_in'] = TRUE;

		$data['memberinfo'] = $this->membership_model->get_member_data($key, $value);
		$data['userinfo'] = $this->user_model->get_user_data($data['member']->id);
		$this->session->set_userdata($data);

		if ($redirect === TRUE)
		{
			redirect($url, redirect);
		}
		else
		{
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'redirect_url'	=> base_url($url)
			)));
		}
	}

	/**
	 * Load facebook form to ask missing email address or verify email address
	 * Stored fb_user data in session flashdata
	 *
	 * @param array $data - view data with fb_user information
	 */
	private function load_facebook_form($data)
	{
		$this->session->set_flashdata('fb_user', $data['fb_user']);

		// ASK email load form
		$data['main_content'] = 'login/fb_login_form';
		$this->load->view('site/page', $data);
	}

	/**
	 * Verifying given email address in facebook form
	 * AJAX call
	 *
	 * Failure: break run with error as JSON string
	 */
	private function verify_email()
	{
		$this->load->library('form_validation');

		// field name, error message, validation rules
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');

		// validation
		if($this->form_validation->run() == FALSE)
		{
			$this->session->keep_flashdata('fb_user');
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'errors'	=> array(
							'email_address' => form_error('email_address')
					)
			)));
		}
	}

	/**
	 * Facebook logout webhook
	 * redirected from facebook->logout_url()
	 */
	public function fb_logout()
	{
		redirect('site', redirect);
	}
}