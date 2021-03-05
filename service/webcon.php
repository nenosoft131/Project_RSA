<?php

include('lib/Math/BigInteger.php');
include('lib/Crypt/Hash.php');
include('lib/Crypt/RSA.php');
include"config.php";

	
////////////////////////////////////////////////////////
############### User SignUp ###########################
//////////////////////////////////////////////////////

function savekeys($pubkey,$prikey,$email){

	$email = Decrypt($email);
	
	
	global $mysqli;		
	$row =  mysqli_query($mysqli,"update clientkey set privatekey = '".$prikey."' , publickey = '".$pubkey."' where Email ='".$email."'");
	echo $row;
	}

function checkemail($email){
	global $mysqli;
	$result = $mysqli->query("SELECT email FROM clientkey WHERE email = '".$email."'");
	if($result->num_rows == 0) {
		return false;
	}else{
		return true;
	}

}	
	
function missing(){

		header('Content-type: application/json');
		echo json_encode(array('State'=>"Result",'Response'=>"Please Fill all Fields"));
}
function saveuserdata($pass ,$email ,$username){
	
		$pass = Decrypt($pass);
		$email = Decrypt($email);
		$username = Decrypt($username);
		
		if(checkemail($email)){
			header('Content-type: application/json');
			echo json_encode(array('State'=>"Result",'Response'=>"Already Register"));
			return;
		}

		global $mysqli;		
		$result = mysqli_query($mysqli,"insert into clientkey (Email,Username,password) values ('".$email."','".$username."','".$pass."') ");
		
		if($result >= 1)
		{
			header('Content-type: application/json');
			echo json_encode(array('State'=>"Result",'Response'=>"Successfully Register"));
		}else{
			header('Content-type: application/json');
			echo json_encode(array('State'=>"Result",'Response'=>"Something went wrong"));
		}


	
}


function getkey($email){

	header("Content-Type: text/html; charset=UTF-8");
    global $mysqli;
	$result = mysqli_query($mysqli,"Select s_key FROM session where email = '".$email."' ");
	$storeArray = Array();
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeArray[] =  $row['s_key'];  
}
	return $storeArray[0];

 
}

function Message($msg,$email){
	
	$email = Decrypt($email);
	$key=getkey($email);
	
	$msg = AES_decrypt($msg,$key);
	
	$msg = AES_encrypt($msg,$key);

	
	header('Content-type: application/json');

	
	echo json_encode(array('State'=>"Result",'Response'=> $msg));
}
function Send_Session($msg,$email){
	$msg = Decrypt($msg);
	$email = Decrypt($email);
	global $mysqli;		
	
	$result = mysqli_query($mysqli,"Select * FROM session where email = '".$email."'");
		if($result->num_rows > 0){
			$result = mysqli_query($mysqli,"UPDATE session SET s_key = '".$msg."' WHERE email='".$email."'");		
		}else{
			$result = mysqli_query($mysqli,"insert into session (s_key,email) values ('".$msg."','".$email."') ");
		}
	
	header('Content-type: application/json');
	echo json_encode(array('State'=>"Result",'Response'=>"Session Created"));
}

function login($email,$pass){
	
		$email = Decrypt($email);
		$pass= Decrypt($pass);
		global $mysqli;		
		$c = array();
		
		$result = mysqli_query($mysqli,"Select * FROM clientkey where email = '".$email."' and password = '".$pass."'");
		if($result->num_rows > 0){
			$storeArray = Array();
	        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$storeArray[0] =  $row['publickey'];
			$storeArray[1] =  $row['privatekey'];
			echo json_encode(array('State'=>"Result",'Response'=>"Successfully Login",'pub_key'=>$storeArray[0],'pri_key'=>$storeArray[1]));
			}		
		}else{
		header('Content-type: application/json');
		echo json_encode(array('State'=>"Result",'Response'=>"Sorry Record not found"));
		}
		}
	
function SendPUK(){
	
    global $mysqli;
	//$result = mysqli_query($mysqli,"Select publickey FROM serverkeys");
	$result = mysqli_query($mysqli,"Select publickey FROM serverkeys ");
	$storeArray = Array();
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeArray[] =  $row['publickey'];  
	}
	//header('Content-type: application/json');
	echo json_encode(array('State'=>"Result",'Response'=>"Connection Established",'S_PUK'=>$storeArray[0]));
	//return $storeArray[0];

}

function sendusrpubkey($email){
	global $mysqli;
	$result = mysqli_query($mysqli,"Select publickey FROM clientkey where email = '".$email."' ");
	$storeArray = Array();
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeArray[] =  $row['publickey'];  
}
	return $storeArray[0];

}

function Encryption($data,$email){

		header("Content-Type: text/html; charset=UTF-8");
        $rsa = new Crypt_RSA();
		
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$rsa->loadKey(public_key($email));
		$ciphertext = $rsa->encrypt($data);
		
		return $ciphertext;
 
}
	 
function Decrypt($ciphertext){
	
		header("Content-Type: text/html; charset=UTF-8");
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $cipher = base64_decode($ciphertext);
		 $privateKey = base64_decode(passkey());
        // Decryption
        $rsa->loadKey($privateKey);
        return $rsa->decrypt($cipher);
} 

function passkey(){
	global $mysqli;
	$result = mysqli_query($mysqli,"Select privatekey FROM serverkeys ");
	$storeArray = Array();
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeArray[] =  $row['privatekey'];  
}
	return $storeArray[0];

}

function public_key($id){
	
	global $mysqli;
	$result = mysqli_query($mysqli,"Select publickey FROM clientkey WHERE Email = '".$id."'");
	
	$storeArray = Array();
	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $storeArray[] =  $row['publickey'];  
}
	
	return $storeArray[0];
}

function genratekeys(){

	if(!existsserverkey()){
		$rsa = new Crypt_RSA();
		$rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS8);
		$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS8);
		$ple =implode(" ",$rsa->createKey(1024));
		$pieces = explode("-----", $ple);
		global $mysqli;		
		$result = mysqli_query($mysqli,"insert into serverkeys values ('".$pieces[6]."','".$pieces[2]."') ");
		echo "Successfully Generated";
	}
	else
	{
		echo "Already Exist";
	}

}

function existsserverkey(){
        global $mysqli;		
		$c = array();
		$result = mysqli_query($mysqli,"Select * FROM serverkeys");
		while($row = mysqli_fetch_array($result)) 
		{
			array_push($c,$row['publickey']);
			return $c;	
		}	
		return false;
}

function keyreq($pubkey,$prikey,$pass ,$email ,$username){
	generatekey();
	$ResponseStatus = $pass;	
	$status='pubkey';	
    
	$serverkey =existsserverkey(); 
	if($serverkey != null && $serverkey != false )
	{
	     
	    if(!userkeyexist($id)){
			save_user_key($id , $pass);
		}
		header('Content-type: application/json');
		echo json_encode(array('State'=>$status,'Response'=>$status));
		
	}else
	{
		generatekey();
	}
}

function userkeyexist($id=''){

		global $mysqli;		
		$c = array();
		$result = mysqli_query($mysqli,"Select * FROM clientkey where id = '".$id."'");
		if($result->num_rows > 0){
			return true;
		}
		return false;

}

function save_user_key($id='',$pass=''){
	
		global $mysqli;		
		$result = mysqli_query($mysqli,"insert into clientkey values ('".$id."','".$pass."') ");
		header('Content-type: application/json');
		echo json_encode(array('State'=>"Result",'Response'=>"Registration Completed Successfully"));
}

function AES_encrypt($plaintext, $key) {
    $plaintext = pkcs5_pad($plaintext, 16);
    return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, hex2bin($key), $plaintext, MCRYPT_MODE_ECB));
}

function AES_decrypt($encrypted, $key) {
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, hex2bin($key), hex2bin($encrypted), MCRYPT_MODE_ECB);
    $padSize = ord(substr($decrypted, -1));
    return substr($decrypted, 0, $padSize*-1);
}

function pkcs5_pad ($text, $blocksize)
{
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}



?>