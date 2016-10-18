<?php
class Emails extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('email');
	}

	public function send_verification_email($email)
	{
		$this->load->model('login/membership_model');
		$user = $this->membership_model->get_member_data('email', $email);

		$created_string = (string)$user->d_created;
		$verification_code = md5($created_string.$email);
		$data = array(
			'verification_url'	=> base_url('user/verify_email/'. urlencode($email) . '/' . $verification_code)
		);
		$message = $this->load->view('verification_email', $data);

		// SETTINGS
		// http://www.codeigniter.com/user_guide/libraries/email.html?highlight=email#sending-email

		$this->email->set_mailtype('html');
		$this->email->from($this->config->item('bot_email'), 'Email Verification');
		$this->email->to($email);
		$this->email->subject('Email Verification: please verify your email address');
		$this->email->message($message);
		if($this->email->send())
		{
			//save to log
			$this->load->model('emails_model');
			$log_verify_email_data = array(
				'member_id'					=> $user->member_id,
				'email'							=> $email,
				'verification_code'	=> $verification_code
			);
			$this->emails_model->log_verify_email($log_verify_email_data);
			log_message('info', $message);//TODO: remove it from production
		}
	}


}