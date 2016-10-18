<?php

class Membership_model extends CI_Model {

	function validate()
	{
		$this->db->where('email', $this->input->post('email_address'));
		$this->db->where('password', md5($this->input->post('password')));
		$query = $this->db->get('members');

		if($query->num_rows() == 1)
		{
			return true;
		}

	}

	function fb_validate($key, $field)
	{
		if ($key === 'ID')
		{
			$this->db->where('facebook_id', $field);
		}
		elseif ($key === 'EMAIL')
		{
			$this->db->where('email', $field);
		}
		$query = $this->db->get('members');

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
	}

	function create_member()
	{
		$new_member_insert_data = array(
			'email' => $this->input->post('email_address'),
			'password' => md5($this->input->post('password'))
		);

		return $this->db->insert('members', $new_member_insert_data);;
	}

	function create_facebook_member($data)
	{
		// REGISTRATION
		$new_member_insert_data = array(
				'email'					=> $data['email'],
				'facebook_id'		=> $data['id']
		);

		return $this->db->insert('members', $new_member_insert_data);
	}

	function connect_facebook_account($data)
	{
		$this->db->set('facebook_id', $data['id']);
		$this->db->where('email', $data['email']);
		return $this->db->update('members');
	}

	function get_member_data($key, $value)
	{
		$this->db->where($key, $value);
		$query = $this->db->get('members');

		return $query->row();
	}
}