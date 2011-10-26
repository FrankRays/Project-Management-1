<?php

class Employees extends Users
{
	function Employees() {
		parent :: Users();
	}
	
//Returns the Employee with the given id# as an array
	function getEmployee($eid)
	{
		$data = array('eid' => $eid);

		$uid = $this->getUid($eid);
		$user = parent::getUser($uid);
		$result = array_merge($data,$user);
		return $result;
	}
	
	function deleteEmployee($eid)
	{
		$uid = $this->getUid($cid);
		
		$this->db->delete('Employees', array('eid' => $eid));
		parent::deleteUser($uid);
		
		return true;
	}
	
	function addEmployee($last,$first,$email,$pass)
	{
		$uid = parent :: addUser($last,$first,$email,$pass);
		$this->db->insert('Employees', array('uid' => $uid));
	}
	
	function updateEmployee($eid,$last,$first,$email,$pass)
	{
		$uid = $this->getUid($eid);
		$data = array(
			'Last Name'		=> $last,
			'First Name'	=> $first,
			'Email'			=> $email,
			'Password'		=> md5($pass)
		);
		
		$this->db->update('Users', $data, array('uid' => $uid));
	}
	
	//Get the uid of the Customer
	private function getUid($eid)
	{
		$this->db->select('uid');
		$this->db->get('Employees');
		$query = $this->db->where('eid',$eid);
		
		$result = $query->row_array();
		$uid = $result['uid'];
		return $uid;
	}
	
}

?>