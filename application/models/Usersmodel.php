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
	public function getUserDetaillsByEmail($email)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('status', 1);
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
	
	 function Token()
    {
      $token=  bin2hex(openssl_random_pseudo_bytes(16));
      $time_start = microtime(true);
      $time_end = microtime(true);
      $time = $time_end - $time_start;
      $today = date("Y-m-d H:i:s.u");
      $microdatetime=$today.$time;
      return $act= sha1(bin2hex($token.$microdatetime));
    }

/*Reset password link*/
function create_new_password_token($data)
{
  $ins_data['email']= $data['email'];
  $ins_data['token']= $data['token'];
  $this->db->insert('changePasswordTokens',$ins_data);
  return $this->db->insert_id();
}

function resetPassword($token,$email)
{
  $this->db->select('changePasswordTokens.*');
  $this->db->where("changePasswordTokens.token",$token);
  $this->db->where("changePasswordTokens.email",$email);
  $this->db->where("changePasswordTokens.isUsed",'0');
  $result = $this->db->get('changePasswordTokens');
  if($result -> num_rows() > 0)
        {
              return true;
        }
        else
        {
          return false;
        }

}
function updateToken($token,$email)
{
	$upd_data['isUsed']='1';
	$this->db->where("changePasswordTokens.email",$email);
	$this->db->where("changePasswordTokens.token",$token);
	$this->db->update('changePasswordTokens',$upd_data);
}

function check_valid_resetLink($token,$email)
{
  $this->db->select('changePasswordTokens.*');
  $this->db->where("changePasswordTokens.token",$token);
  $this->db->where("changePasswordTokens.email",$email);
  $this->db->where("changePasswordTokens.isUsed",'0');
  $result = $this->db->get('changePasswordTokens');
  if($result -> num_rows() > 0)
        {
            return true;
        }
        else
        {
          return false;
        }

}


  public function change_password($email,$password)
  {
       $upd_data['password']=md5($password);
       $this->db->where("users.email",$email);
       $this->db->update('users',$upd_data);
       return true; 
  }

 }
 ?>