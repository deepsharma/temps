<?php

 class usersmodel extends CI_Model
 { 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function verifyLoginDtails($email,$password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('status', 1);
		$this->db->where('password', md5($password));
		$result = $this->db->get();
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			 return $row;
		   }
		}return array();
	}
	public function checkDuplicateEmail($email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$result = $this->db->get();
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			 return $row;
		   }
		}return array();
	}
	public function checkDuplicateCompany($companyUrl)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('enterprise_url', $companyUrl);
		$result = $this->db->get();
		if ($result->num_rows() > 0)
		{
		   foreach ($result->result_array() as $row)
		   {
			 return $row;
		   }
		}return array();
	}
	function updateLastLoginDtails($data)
	{
		$ipaddress = trim($_SERVER['REMOTE_ADDR']);
		$loginArray['email']=$data['email'];
		$loginArray['last_login_ip']=$ipaddress;
		$loginArray['status']=1;
		$loginArray['last_login_on']=date('Y-m-d H:i:s');
		$this->db->where('email',$loginArray['email']);
		$this->db->update('users',$loginArray);
	}
	function saveRegistraionDetails($data)
	{
		$data['status']=1;
		$this->db->insert('users',$data);
		return $this->db->insert_id();
	}

 }
 ?>