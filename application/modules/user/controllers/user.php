<?php

class User extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function profile()
	{
		Modules::run('login/is_logged_in');

		$userdata = $this->session->userdata;
		$data['userinfo'] = $userdata['userinfo'];
		$data['memberinfo'] = $userdata['memberinfo'];

		$data['main_content'] = 'profile';
		$this->load->view('site/page', $data);
	}

	public function create_profile_info()
	{
		$this->load->library('form_validation');

		// REQUIRED info
		// field name, error message, validation rules
		// OPTIONAL info
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|min_length[2]|max_length[255]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|min_length[2]|max_length[255]');
		// profile image

		$this->form_validation->set_rules('city', 'City', 'trim|min_length[2]|max_length[255]');
		$this->form_validation->set_rules('zip', 'Zip Code', 'trim|min_length[2]|max_length[6]|is_natural');
		$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[255]');

		if($this->form_validation->run() == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'errors'	=> array(
							'first_name'		=> form_error('first_name'),
							'last_name'			=> form_error('last_name'),
							'city'					=> form_error('city'),
							'zip'						=> form_error('zip'),
							'address'				=> form_error('address')
					)
			)));
		}

		else
		{
			$this->load->model('membership_model');

			if($query = $this->membership_model->save_userinfo())
			{

				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'redirect_url'	=> base_url('login/profile')
				)));
			}
			else
			{
				header('HTTP/1.1 400 Registration error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'errors' => array(
								'__error__'	=> '<div class="dynamic-response text-danger">Couldn\'t save your profile</div>'
						)
				)));
			}
		}

	}

	public function change_email()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|is_unique[members.email]');
		$this->form_validation->set_rules('confirm_email_address', 'Confirm Email Address', 'trim|required|valid_email|matches[email_address]');

		if($this->form_validation->run() == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'errors'	=> array(
							'email_address' 					=> form_error('email_address'),
							'confirm_email_address'		=> form_error('confirm_email_address')
					)
			)));
		}

		else
		{
			$this->load->model('user_model');
			$email = $this->input->post('email_address');
			$userdata = $this->session->userdata;
			$memberinfo = $userdata['memberinfo'];

			if($query = $this->user_model->update_member_email($memberinfo->member_id, $email))
			{
				// TODO:  send verification email
				Modules::run('emails/send_verification_email', $email);

				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'success_messages'	=> array(
							'email_address'		=> '<div class="dynamic-reponse text-info">Succesfully updated</div>',
							'__message__'			=> '<div class="dynamic-response text-info">Your account is deactivated cause of your email change. A Verification email has been sent to your new email address. Please click the link in it to confirm your changes!</div>'
						)
				)));
			}
			else
			{
				header('HTTP/1.1 400 Registration error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'errors' => array(
								'__error__'	=> '<div class="dynamic-response text-danger">Couldn\'t update your email address</div>'
						)
				)));
			}
		}
	}

	public function verify_email($email, $code)
	{
		$this->load->helper('security');
		$code = $this->security->xss_clean(trim($code));
		$email = urldecode($this->security->xss_clean(trim($email)));

		$this->load->model('emails_model');
		$query = $this->emails_model->verify_email($email, $code);

		if ($query)
		{
			$member_id = $query->member_id;
			$this->load->model('user_model');
			if ($response = $this->user_model->activate_user($member_id))
			{
				//TODO fix response
			}

			$data = array(
				'verified'			=> true,
				'main_content'	=> 'verify_email'
			);

			$this->load->view('site/page', $data);
		}
	}

	public function change_password()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

		if($this->form_validation->run() == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'errors'	=> array(
							'password' 						=> form_error('password'),
							'confirm_password'		=> form_error('confirm_password')
					)
			)));
		}

		else
		{
			$this->load->model('user_model');
			$password = md5($this->input->post('password'));
			$userdata = $this->session->userdata;
			$memberinfo = $userdata['memberinfo'];

			if($query = $this->user_model->update_member_password($memberinfo->member_id, $password))
			{
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'success_messages'	=> array(
								'password'		=> '<div class="dynamic-reponse text-info">Succesfully updated</div>',
								'__message__'			=> '<div class="dynamic-response text-info">Your password is successfully updated. You must use it when log in next time.</div>'
						)
				)));
			}
			else
			{
				header('HTTP/1.1 400 Registration error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'errors' => array(
								'__error__'	=> '<div class="dynamic-response text-danger">Couldn\'t update your password</div>'
						)
				)));
			}
		}
	}

	public function change_userinfo()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('zip', 'Zip Code', 'trim|required|min_length[4]|max_length[6]|is_numeric');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'validate_phone_number');

		$this->form_validation->set_message('validate_phone_number', 'Not a valid format.');

		if($this->form_validation->run($this) == FALSE)
		{
			// error ajax handle
			header('HTTP/1.1 400 Validation error');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array(
					'errors'	=> array(
							'first_name' 			=> form_error('first_name'),
							'last_name' 			=> form_error('last_name'),
							'zip' 						=> form_error('zip'),
							'city' 						=> form_error('city'),
							'address'					=> form_error('address'),
							'phone'						=> form_error('phone')
					)
			)));
		}

		else
		{
			$this->load->model('user_model');
			$userdata = $this->session->userdata;
			$memberinfo = $userdata['memberinfo'];
			$data = array(
				'member_id'				=> $memberinfo->member_id,
				'first_name'			=> $this->input->post('first_name'),
				'last_name'				=> $this->input->post('last_name'),
				'zip'							=> $this->input->post('zip'),
				'city'						=> $this->input->post('city'),
				'address'					=> $this->input->post('address'),
				'phone'						=> $this->input->post('phone')
			);

			if($query = $this->user_model->save_userinfo($data))
			{
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'success_messages'	=> array(
								'__message__'			=> '<div class="dynamic-response text-info">Userinfo successfully updated.</div>'
						)
				)));
			}
			else
			{
				header('HTTP/1.1 400 Registration error');
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'errors' => array(
								'__error__'	=> '<div class="dynamic-response text-danger">Couldn\'t update informations</div>'
						)
				)));
			}
		}
	}

}
