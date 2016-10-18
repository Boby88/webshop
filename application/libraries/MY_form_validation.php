<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	function run($module = '', $group = '') {
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);
	}

	public function validate_phone_number($number)
	{
		return preg_match('/^([0-9-+.\/()\s]{8,16}|\s*)$/', $number ) == 1;
	}
}