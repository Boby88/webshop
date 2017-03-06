<?php

class Product extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function details($id)
	{
		$data['main_content'] = 'product';
		$data['product'] = array('id' => $id);

		$this->load->view('site/page', $data);
	}

}
