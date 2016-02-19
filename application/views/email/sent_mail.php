<?php
date_default_timezone_set('Asia/Kolkata');
require_once '/root/mount_point/skilld/apps/tutortube/public/web/qustn/mandrill/src/Mandrill.php'; //Not required with Composer
class SentMail{
	function __construct(){
        
       // $this->sendMail("smriti@qustn.com ","smriti","m@qustn.com","Test Demo","welcome to qustn","Mandrill applied");
    }

    public function sendMail($to, $to_name, $from, $from_name, $subject, $message, $isBulk ){
			$message =   str_replace('"','\\"',$message);
                  
				  $bukEmailList = "";
                  if($isBulk){
                  	for($email_index = 0; $email_index < count($to); $email_index++){

                  		$bukEmailList .= "{";
                  		$bukEmailList .= '"email": "'.$to[$email_index].'",';
                  		$bukEmailList .= '"name": "'.$to_name[$email_index].'"';
                  		$bukEmailList .= "},";

                  	} 
                  	$bukEmailList =  rtrim($bukEmailList,",");

                  }else{
                  		$bukEmailList .= "{";
                  		$bukEmailList .= '"email": "'.$to.'",';
                  		$bukEmailList .= '"name": "'.$to_name.'"';
                  		$bukEmailList .= "}";

                  }
                    try {
                       

                $postString = '{
                    "key": "t6gfJPkrhADq8fOOkcJH-Q",
                    "message": {
                        "html": "'.$message.'",
                        "text": "this is the emails text content",
                        "subject": "'.$subject.'",
                        "from_email": "'.$from.'",
                        "from_name": "'.$from_name.'",
                        "to": [
                            '.$bukEmailList.'
                        ],
                        "headers": {

                        },
                        "track_opens": true,
                        "track_clicks": true,
                        "auto_text": true,
                        "url_strip_qs": true,
                        "preserve_recipients": true,

                        "merge": true,
                        "global_merge_vars": [

                        ],
                        "merge_vars": [

                        ],
                        "tags": [

                        ],
                        "google_analytics_domains": [

                        ],
                        "google_analytics_campaign": "...",
                        "metadata": [

                        ],
                        "recipient_metadata": [

                        ],
                        "attachments": [

                        ]
                    },
                    "async": false
                    }';
					
                    $response_mandrill = $this->curlExecute($postString);
					
					file_put_contents('/root/mount_point/log/logToEmails.txt', date('Y-m-d H:i:s').'!~! log emails on web !~!'.$to.'!~!'.$to_name.'!~!'.$from.'!~!'.$from_name.'!~!'.$subject.'!~!'.$message.'!~!'.$response_mandrill."\n",FILE_APPEND);
					
					return $response_mandrill;
              
                    } catch(Mandrill_Error $e) {
						// Mandrill errors are thrown as exceptions
                        // echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                        // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                        throw $e;
                    }

    }


    public function sendAllMail($bukEmailList, $from, $from_name, $subject, $message ){
			$message =   str_replace('"','\\"',$message);
                
				// if($isBulk){
                  	// for($email_index = 0; $email_index < count($to); $email_index++){

                  		// $bukEmailList .= "{";
                  		// $bukEmailList .= '"email": "'.$to[$email_index].'",';
                  		// $bukEmailList .= '"name": "'.$to_name[$email_index].'",';
                  		// $bukEmailList .= '"type": "'.$type[$email_index].'"';
                  		// $bukEmailList .= "},";

                  	// } 
                  	// $bukEmailList =  rtrim($bukEmailList,",");

                  // }else{
                  		// $bukEmailList .= "{";
                  		// $bukEmailList .= '"email": "'.$to.'",';
                  		// $bukEmailList .= '"name": "'.$to_name.'"';
                  		// $bukEmailList .= "}";

                  // }
                    try {
                       

                $postString = '{
                    "key": "t6gfJPkrhADq8fOOkcJH-Q",
                    "message": {
                        "html": "'.$message.'",
                        "text": "this is the emails text content",
                        "subject": "'.$subject.'",
                        "from_email": "'.$from.'",
                        "from_name": "'.$from_name.'",
                        "to": [
                            '.$bukEmailList.'
                        ],
                        "headers": {

                        },
                        "track_opens": true,
                        "track_clicks": true,
                        "auto_text": true,
                        "url_strip_qs": true,
                        "preserve_recipients": true,

                        "merge": true,
                        "global_merge_vars": [

                        ],
                        "merge_vars": [

                        ],
                        "tags": [

                        ],
                        "google_analytics_domains": [

                        ],
                        "google_analytics_campaign": "...",
                        "metadata": [

                        ],
                        "recipient_metadata": [

                        ],
                        "attachments": [

                        ]
                    },
                    "async": false
                    }';


                    return $this->curlExecute($postString);
              
                    } catch(Mandrill_Error $e) {
						// Mandrill errors are thrown as exceptions
                        // echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                        // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                        throw $e;
                    }

    }
	
	
	    public function sendMailBCC($to, $to_name, $from, $from_name, $subject, $message, $isBulk, $mail_type ){
         $message =   str_replace('"','\\"',$message);
                  
				  $bukEmailList = "";
                  if($isBulk){
                  	for($email_index = 0; $email_index < count($to); $email_index++){

                  		$bukEmailList .= "{";
                  		$bukEmailList .= '"email": "'.$to[$email_index].'",';
                  		$bukEmailList .= '"name": "'.$to_name[$email_index].'"';
						$bukEmailList .= '"type": "'.$mail_type.'"';
                  		$bukEmailList .= "},";

                  	} 
                  	$bukEmailList =  rtrim($bukEmailList,",");

                  }else{
                  		$bukEmailList .= "{";
                  		$bukEmailList .= '"email": "'.$to.'",';
                  		$bukEmailList .= '"name": "'.$to_name.'"';
                  		$bukEmailList .= "}";

                  }
                    try {
                       

                $postString = '{
                    "key": "t6gfJPkrhADq8fOOkcJH-Q",
                    "message": {
                        "html": "'.$message.'",
                        "text": "this is the emails text content",
                        "subject": "'.$subject.'",
                        "from_email": "'.$from.'",
                        "from_name": "'.$from_name.'",
                        "to": [
                            '.$bukEmailList.'
                        ],
                        "headers": {

                        },
                        "track_opens": true,
                        "track_clicks": true,
                        "auto_text": true,
                        "url_strip_qs": true,
                        "preserve_recipients": true,

                        "merge": true,
                        "global_merge_vars": [

                        ],
                        "merge_vars": [

                        ],
                        "tags": [

                        ],
                        "google_analytics_domains": [

                        ],
                        "google_analytics_campaign": "...",
                        "metadata": [

                        ],
                        "recipient_metadata": [

                        ],
                        "attachments": [

                        ]
                    },
                    "async": false
                    }';


                    return $this->curlExecute($postString);
              
                    } catch(Mandrill_Error $e) {
						// Mandrill errors are thrown as exceptions
                        // echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                        // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                        throw $e;
                    }

    }
	
	


	public function curlExecute($postString){
		try{
			$uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
			//echo $uri."<br>";
			//echo $postString."<br>";
			
			$ch = curl_init();
			$headers = array("Content-type: application/json;charset=\"utf-8\""); 
			curl_setopt($ch, CURLOPT_URL, $uri);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

			$result = curl_exec($ch);
		
			return $result;
		} catch(Exception $e){
			//	
		}
    }
}
$test = new SentMail();
?>