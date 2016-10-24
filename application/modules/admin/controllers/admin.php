<?php

class Admin extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		if($this->is_logged_in())
		{
			$data = array();
			$this->load->view('admin_page', $data);
		} else {
			$this->load->view('admin_login');
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
			$this->load->model('admin_model');
			// esisting member
			if($query = $this->admin_model->validate()) // if the user's credentials validated...
			{
				header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array(
						'redirect_url'	=> base_url('admin')
				)));
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


	private function is_logged_in()
	{
		return true;
	}
}