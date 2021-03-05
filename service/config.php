<?php

	define('DB_NAME', 'test');	//MySQL database
	define('DB_USER', 'root');		//MySQL database username 
	define('DB_PASSWORD', '');		//MySQL database password 
	define('DB_HOST', 'localhost'); // MySQL hostname 
	
	$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	
	if(!$mysqli){
	
			echo 'MYSQLI connection failed ';
			exit();
	}
	
	
	if(isset($_REQUEST['action'])  ){
	  
	  
		
		$status_code 	= "1";
		$action 	 	= $_REQUEST['action'];
		
		
		$pubkey          = isset($_REQUEST['pub_key'])?stripslashes($_REQUEST['pub_key']):'';
		$prikey          = isset($_REQUEST['pri_key'])?stripslashes($_REQUEST['pri_key']):'';
		$pass            = isset($_REQUEST['pass'])?stripslashes($_REQUEST['pass']):'';
		$email           = isset($_REQUEST['email'])?stripslashes($_REQUEST['email']):'';
		$username        = isset($_REQUEST['U_name'])?stripslashes($_REQUEST['U_name']):'';
		$msg        = isset($_REQUEST['msg'])?stripslashes($_REQUEST['msg']):'';
		
		
		$todate		 	= date('Y:m:d H:m:s');
		$name  		 	= isset($_REQUEST['name'])?stripslashes($_REQUEST['name']):'';
			
		switch($action){
			
			
			case 'Req_Con':
						SendPUK();	
					break;
	
			case 'Signup':
				if($pass != '' && $email != '' && $username != ''){
					saveuserdata($pass ,$email ,$username);	
					}
					else{
						missing();
					}
					break;
	
			case 'Login':
			
					if($email != '' && $pass != ''){
					
						login($email,$pass);	
					}
					else{
						missing();
					}
					break;
					
								
			case 'Send_Message':
					Message($msg,$email);
					break;
					
			case 'Save_user_keys':
					savekeys($pubkey,$prikey,$email);
					break;
					
			case 'generatekey':
					genratekeys();
					break;
			case 'Send_Session':
					Send_Session($msg,$email);
					break;
					
					
		}
	
  	

	}
	else{
	
		header('Content-type: application/json');
		echo json_encode(array('State'=>'false','Response'=>'Action Missing'));
		
	}
	
?>