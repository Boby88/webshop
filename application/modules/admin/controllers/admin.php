<?php

class Admin extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$userdata = $this->session->userdata('admin');
		$is_logged_in = $this->session->userdata('is_admin_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			$this->load->view('admin_login');
		} else {
			$data['admin'] = $userdata;
			$this->load->view('admin_page', $data);
		}
	}

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
			$this->load->model('login/membership_model');
			// esisting member
			if($query = $this->membership_model->validate()) // if the user's credentials validated...
			{
				$data = array(
					'email'	=> $this->input->post('email_address')
				);
				$this->log_in('email', $data['email'], 'admin');
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


	public function is_admin_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_admin_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			echo 'You don\'t have permission to access this page. <a href="../admin">Login</a>';
			redirect('admin', redirect);
			die();
			//$this->load->view('login_form');
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin', redirect);
	}

	private function log_in($key, $value, $url, $redirect = FALSE)
	{
		$this->load->models(array(
			'login/membership_model',
			'user/user_model'
		));

		$data['is_logged_in'] = TRUE;
		$data['is_admin_logged_in'] = TRUE;

		$data['memberinfo'] = $this->membership_model->get_member_data($key, $value);
		$data['userinfo'] = $this->user_model->get_user_data($data['memberinfo']->member_id);
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
}