<?php

class Emails_model extends CI_Model {

	function log_verify_email($data)
	{
		return $this->db->insert('email_verification', $data);
	}

	function verify_email($email, $code)
	{
		$this->db->select('member_id');
		$this->db->where('verification_code', $code);
		$this->db->where('email', $email);
		$query = $this->db->get('email_verification', 1);

		return $query->row();
	}
}