<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller {

	public $mediaFilePath;
	public $mediaFileBasePath;
	public $enterprise;
	function __construct() 
	{
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('commonfunctions');
		$this->load->library('layout');
		$this->load->model('usersmodel');
    }
	
	public function login()
	{
		if($this->session->userdata('logged_in'))
		{
			redirect(base_url('dashboard'));
		}
		
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('pwd', 'Password', 'required|min_length[5]|callback_checkLoginDetails');
		if ($this->form_validation->run() == FALSE)
		{
			
		}
		else
		{
			redirect(base_url('dashboard'));
		}
		$data['pagename']='login';
		$this->layout->view('users/login',$data);
		
	}
	
	function checkLoginDetails()
	{
		$loginQueries='';
		$result=$this->usersmodel->verifyLoginDtails($_POST['email'],$_POST['pwd']);
		$loginQueries.=$this->db->last_query()." \n\n ";
		if(empty($result))
		{
			$this->form_validation->set_message('checkLoginDetails', 'Email / Password is not correct');
			return false;
		}else
		{
			$results=$this->usersmodel->updateLastLoginDtails($result);
			$loginQueries.=$this->db->last_query()." \n ";
			/* setting cookies*/
			if( isset($_POST['remember']) && trim($_POST['remember']) != '' ) {               
                setcookie('username', $_POST['email'], time() + 1*24*60*60);
                setcookie('password', $_POST['pwd'], time() + 1*24*60*60);
            } else if( isset($_COOKIE['username']) && trim($_COOKIE['username']) ==$_POST['email'] ){                
               setcookie("username", $_POST['email'], time()-1);
               setcookie("password", $_POST['pwd'], time()-1);               
            }
			/* setting cookies ends*/
			$sess_array = array(
                   'email'  => trim($result['email']),
                   'username' => trim($result['name']),
                   'enterprisename' => trim($result['comp_name']),
                   'compurl' => trim($result['comp_url']),
                   'enterpriseurl' => trim(strtolower($result['enterprise_url']))
               );
			 $this->session->set_userdata('logged_in', $sess_array);
				$result['log_query']=$loginQueries;
			$this->commonfunctions->generateLog($result,'login',APPPATH  . "/logs/useractivity/log".date('Y-m-d').".php");
			 return true;
		}
	}
	public function register()
	{	

		if($this->session->userdata('logged_in'))
		{
			redirect(base_url('dashboard'));
		}
		
		$this->form_validation->set_rules('regname', 'Name', 'required');
		$this->form_validation->set_rules('regpwd', 'Password', 'required|min_length[5]');
		$this->form_validation->set_rules('compname', 'compname', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_checkRegistrationDetails');
		$this->form_validation->set_rules('regphone', 'regphone', 'trim');
		$this->form_validation->set_rules('compurl', 'compurl', 'required|callback_checkDuplicateCompanyUrl');
		if ($this->form_validation->run() == FALSE)
		{
			
		}
		else
		{
			$logQueries='';
			$regname = (isset($_POST['regname']) && !empty($_POST['regname']))? trim($_POST['regname']): '';
			$regpwd = (isset($_POST['regpwd']) && !empty($_POST['regpwd']))? trim($_POST['regpwd']): '' ;
			$compname = (isset($_POST['compname']) && !empty($_POST['compname']))? trim($_POST['compname']): '' ;
			$regemail = (isset($_POST['email']) && !empty($_POST['email']))? trim($_POST['email']): '' ;
			$regphone = (isset($_POST['regphone']) && !empty($_POST['regphone']))? trim($_POST['regphone']): '' ;
			$compurl = (isset($_POST['compurl']) && !empty($_POST['compurl']))? trim($_POST['compurl']): '' ;
			if($regname != '' && $regpwd != '' && $compname !='' && $regemail !='' && $compurl !='' ) {
			   $ipaddress = trim($_SERVER['REMOTE_ADDR']);
			   //$parse = parse_url($compurl);
			   //$enterprise_url =  str_replace('.','', trim($parse['host']));
			   $chkurl=preg_replace('/^www\./', '', preg_replace( "#^[^:/.]*[:/]+#i", "", $_POST['compurl']));
			   $enterprise_url =  str_replace('.','', trim($chkurl));
			   $pwd = md5($regpwd);
			   $data = array(
				   'name' => $regname,
				   'comp_name' => $compname,
				   'email' => $regemail,
				   'password' => $pwd,
				   'phone' => $regphone,
				   'comp_url' => $compurl,
				   'enterprise_url' => strtolower($enterprise_url),
				   'last_login_ip' => $ipaddress,
				   'created_on' => date('Y-m-d H:i:s')
			   );			   
			 $response=$this->usersmodel->saveRegistraionDetails($data);
			 $logQueries.=$this->db->last_query()." \n ";
             $enterpriseList=FCPATH . "assets/registered_enterprise.txt"; // enterprise log file path
				if(file_exists($enterpriseList))
				{
					$file=file_get_contents($enterpriseList);
				    if(!$file)
						{
							file_put_contents($enterpriseList,$enterprise_url);
						}else
						{
							$file=$file.','.$enterprise_url;
							file_put_contents($enterpriseList,$file);
						}
				}else
				{
					file_put_contents($enterpriseList,$enterprise_url);
				}
			// $this->session->set_flashdata('flashData', '<div class="alert alert-success">Successfully registered!</div>');
				$sess_array = array(
                   'email'  => trim($regemail),
                   'username' => trim($regname),
                   'enterprisename' => trim($compname),
                   'compurl' => trim($compurl),
                   'enterpriseurl' => trim(strtolower($enterprise_url))
               );
			 $this->session->set_userdata('logged_in', $sess_array);
			 /* registration email */
			    $data['email_to']=$regemail;
				$data['subject']='User registration';
				$data['username']=trim(ucfirst($regname));
				$data['domain']=trim($compurl);
				$data['enterprisename']=trim($compname);
				$data['email_template_name']='registration.php';
				$this->commonfunctions->sendmail($data);
			 /* registration email */
			 /* registration log*/
				$data['log_query']=$logQueries;
				$this->commonfunctions->generateLog($data,'register',APPPATH  . "/logs/useractivity/log".date('Y-m-d').".php");
			 /* registration log*/
			 redirect(base_url('dashboard'));
			}
		
	}
	$data['pagename']='registration';
	$this->layout->view('users/register',$data);
	}
	function checkRegistrationDetails()
	{
		$result=$this->usersmodel->checkDuplicateEmail($_POST['email']);
		if(!empty($result))
		{
			$this->form_validation->set_message('checkRegistrationDetails', 'Email already in use !');
			return false;
		}else
		{
			 return true;
		}
	}
	function checkValidEmail()
	{
		$result=$this->usersmodel->checkDuplicateEmail($_POST['email']);
		if(empty($result))
		{
			$this->form_validation->set_message('checkValidEmail', 'Email address not found!');
			return false;
		}else
		{
			 return true;
		}
	}
	function checkDuplicateEmail()
	{
		$result=$this->usersmodel->checkDuplicateEmail($_POST['email']);
		if(!empty($result))
			{
				die(json_encode(false));
			}
			else
			{
				die(json_encode(true));
			}
	}

	function checkDuplicateCompanyUrl()
	{
		$chkurl=preg_replace('/^www\./', '', preg_replace( "#^[^:/.]*[:/]+#i", "", $_POST['compurl']));
	    $enterprise_url =  str_replace('.','', trim($chkurl));
		$result=$this->usersmodel->checkDuplicateCompany($enterprise_url);
		if(!empty($result))
		{
			$this->form_validation->set_message('checkDuplicateCompanyUrl', 'Enterprise domain already in use !');
			return false;
		}else
		{
			 return true;
		}
	}

	function checkDuplicateCompanyUrlByAjax()
	{
			$compurl=$_POST['compurl'];
			$chkurl=preg_replace('/^www\./', '', preg_replace( "#^[^:/.]*[:/]+#i", "", $_POST['compurl']));
			$enterprise_url =  str_replace('.','', trim($chkurl));
			$result=$this->usersmodel->checkDuplicateCompany($enterprise_url);			
			if(!empty($result))
			{
				die(json_encode(false));
			}else
			{
				die(json_encode(true));
			}
	}

	function checkValidLoginDetails()
	{
		$result=$this->usersmodel->verifyLoginDtails($_POST['email'],$_POST['pwd']);
		if(empty($result))
		{
			$this->form_validation->set_message('checkLoginDetails', 'Email / Password is not correct');
			die(json_encode(false));
		}else
			{
			  die(json_encode(true));
		   }
	}

	function logout()
	{
		$this->session->unset_userdata('logged_in');
		redirect(base_url().'users/login');
	}
	
	function forgotPassword()
	{
		
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_checkValidEmail');				
		if ($this->form_validation->run() == FALSE)
		{
			
		}else
		{
			if(!empty($_POST)){
			
			$data['email']=$_POST['email'];
			$data['token']=$this->usersmodel->Token();
			$this->usersmodel->create_new_password_token($data);
			/* reset password email */
			$data['email_to']=$data['email'];
			$validUser=$this->usersmodel->getUserDetaillsByEmail($data['email']);
			if(!empty($validUser))
			{
				$data['subject']='Reset password';
				$data['username']=$validUser['name'];
				$data['email_template_name']='reset_password.php';				
				$this->commonfunctions->sendmail($data);
				$this->session->set_flashdata('flashData', '<div class="alert alert-success">Password Reset link has been sent to your mail!</div>');
				redirect(base_url().'users/login');	
			}else{
				$this->session->set_flashdata('flashData', '<div class="alert alert-danger">Email address not found!</div>');
				redirect(base_url().'users/login');	
			}
			
			/* reset password email */
			
			}
		}
		$data['pagename']='Forgot Password';
		$this->layout->view('users/forgot_password',$data);
		
	}
	
	function resetPassword($token='',$email='')
	{
		if(empty($token) || empty($email)){ show_404(); }
		$valid=$this->usersmodel->check_valid_resetLink($token,base64_decode($email));
		if($valid)
		{
			$changed=$this->usersmodel->resetPassword($token,base64_decode($email));
			if($changed)
			{
				if(!empty($_POST['password']))
				{
					$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
					$this->form_validation->set_rules('pwd_confirm', 'Confirm Password', 'required|min_length[5]|matches[password]');					
					if ($this->form_validation->run() == FALSE)
					{
						
					}else
					{
						$this->usersmodel->change_password(base64_decode($_POST['email']),$_POST['password']);
						$this->usersmodel->updateToken($_POST['token'],base64_decode($_POST['email']));
						$this->session->set_flashdata('flashData', '<div class="alert alert-success">Password Successfully changed!</div>');
						redirect(base_url().'users/login');
					}
				}
				$data['pagename']='Change Password';
				$data['token']=$token;
				$data['email']=$email;
				$this->layout->view('users/change_password',$data);
				// allow file to be displayed
			}else{
				die('Link already used or expired');
			}
			
		}else{
			die('Invalid or link expired');
		}
		
		
	}
	
	
}