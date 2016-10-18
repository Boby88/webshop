<?php

class User_model extends CI_Model {

	function update_member_email($member_id, $email)
	{
		$this->db->set('email', $email);
		$this->db->set('activated', 0);
		$this->db->where('member_id', $member_id);
		return $this->db->update('members');
	}

	function get_user_data($member_id)
	{
		$this->db->where('member_id', $member_id);
		$query = $this->db->get('users', 1);

		return $query->row();
	}

	function activate_user($member_id)
	{
		$this->db->set('activated', 1);
		$this->db->where('member_id', $member_id);
		return $this->db->update('members');
	}
}