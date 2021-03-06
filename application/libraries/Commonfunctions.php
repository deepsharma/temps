<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commonfunctions  
{
		private $CI;
        public function __construct()
        {
            $this->CI =& get_instance();
        }
	
	function copydir($src,$dst) 
	{ 
			$dir = opendir($src); 
			@mkdir($dst,0777); 
			while(false !== ( $file = readdir($dir)) ) { 
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( is_dir($src . '/' . $file) ) { 
						$this->copydir($src . '/' . $file,$dst . '/' . $file); 
					} 
					else { 
						copy($src . '/' . $file,$dst . '/' . $file); 
					} 
				} 
			} 
			closedir($dir); 
	}
	
	function checkDirectoryStructure($enterprise)
	{
		
		if(is_dir(FCPATH . "assets/enterprise/".$enterprise))
		{
			if(!is_dir(FCPATH . "assets/enterprise/".$enterprise."/json"))
					{
						// copy sample if json dir not available
						if(mkdir(FCPATH . "assets/enterprise/".$enterprise."/json",0777))
						{
							$src = FCPATH . "assets/enterprise/sample/json";
							$dst = FCPATH . "assets/enterprise/".$enterprise."/json";
							$this->copydir($src,$dst);
						}
					}
		}else
		{// copy sample dir if enterprise not available
			if(mkdir(FCPATH . "assets/enterprise/".$enterprise,0777))
			{
				$src = FCPATH . "assets/enterprise/sample";
				$dst = FCPATH . "assets/enterprise/".$enterprise;
				$this->copydir($src,$dst);
			}
			
		}
	}
	
	/* added for maintaining activity logs*/	
	function generateLog($data,$type,$path)
	{
		date_default_timezone_set("Asia/Kolkata");
		$logMessage='';
		if(!is_dir(APPPATH  . "/logs/useractivity/"))
		{
			@mkdir(APPPATH  . "/logs/useractivity/" ,0777);
		}
		if($type=='login')
		{			
			$logMessage.='***************** Activity time:'. date('d M Y H:i:s') .' starts************** '. "\n";
			$logMessage.='login attempt::'. "\n";
			$logMessage.='user id:'.$data['id']. "\n";
			$logMessage.='Name:'.$data['name']. "\n";
			$logMessage.='ip:'.$data['last_login_ip']. "\n";
			$logMessage.='ActivityTime:'.date('d M Y H:i:s'). "\n";
			$logMessage.='Queries:'.$data['log_query']. "\n";
			$logMessage.='***************** Activity time:'. date('d M Y H:i:s') .' ends************** '. "\n";
			file_put_contents($path, $logMessage. "\n \n", FILE_APPEND | LOCK_EX);
		}
		else if($type=='register')
		{			
			$logMessage.='***************** Activity time:'. date('d M Y H:i:s') .' starts************** '. "\n";
			$logMessage.='register attempt::'. "\n";
			$logMessage.='Name:'.$data['name']. "\n";
			$logMessage.='ip:'.$data['last_login_ip']. "\n";
			$logMessage.='ActivityTime:'.date('d M Y H:i:s'). "\n";
			$logMessage.='Queries:'.$data['log_query']. "\n";
			$logMessage.='***************** Activity time:'. date('d M Y H:i:s') .' ends************** '. "\n";
			file_put_contents($path, $logMessage. "\n \n", FILE_APPEND | LOCK_EX);
		}
		
	}
	/* added for maintaining activity logs*/	
	/* function to send email*/
	public function sendmail($data)
	{
		include '/root/mount_point/skilld/apps/tutortube/public/web/qustn/sent_mail.php';
		 $body = $this->CI->load->view('email_templates/'.$data['email_template_name'],$data,TRUE);
		$sent_mail = new SentMail();
		$response = $sent_mail->sendMail($data['email_to'], $data['username'], webMasterEmail, webMasterName, $data['subject'] ,$body, false );
		
    }
	/* function to send email*/
	
}
?>