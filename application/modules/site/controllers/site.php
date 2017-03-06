<?php

class Site extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['main_content'] = 'mainpage';
		$this->load->view('page', $data);
	}

	function members_area()
	{
		modules::run('login/is_logged_in');

		$data['main_content'] = 'logged_in_area';
		$this->load->view('page', $data);
	}

	function another_page() // just for sample
	{
		echo 'good. you\'re logged in.';
	}

}
