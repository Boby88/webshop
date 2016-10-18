<?php

class User_model extends CI_Model {

	function update_member_email($member_id, $email)
	{
		$this->db->set('email', $email);
		$this->db->set('activated', 0);
		$this->db->where('member_id', $member_id);
		return $this->db->update('members');
	}

	function update_member_password($member_id, $password)
	{
		$this->db->set('password', $password);
		$this->db->where('member_id', $member_id);
		return $this->db->update('members');
	}

	function save_userinfo($data)
	{
		$sql = $this->_duplicate_insert('users', $data);
		return $this->db->query($sql);
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

	private function _duplicate_insert($table, $values)
	{
    $updatestr = array();
    $keystr    = array();
    $valstr    = array();

    foreach($values as $key => $val)
    {
        $updatestr[] = $key." = ".$this->db->escape($val);
        $keystr[]    = $key;
        $valstr[]    = $this->db->escape($val);
    }

    $sql  = "INSERT INTO ".$table." (".implode(', ',$keystr).") ";
    $sql .= "VALUES (".implode(', ',$valstr).") ";
    $sql .= "ON DUPLICATE KEY UPDATE ".implode(', ',$updatestr);

    return $sql;
	}
}