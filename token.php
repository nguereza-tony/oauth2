<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
header('content-type : application/json');
	echo json_encode(array('error' => ' invalid request' ,'error_description ' => ' you must use POST method to get an access token' ));
}
else{
	require_once('config.php');

	
	$error = null;
	$error_description = null;
	$grant_type = $server->request('grant_type');
	$client_id = $server->request('client_id');
	$client_secret = $server->request('client_secret');
	$redirect_uri = $server->request('redirect_uri');
	$code = $server->request('code');

	
	if(!$grant_type){
		$error = 'invalid request';
		$error_description = 'missing parameter grant_type';
	}
	else if(!$server->grant($grant_type)){
		$error = 'invalid parameter';
		$error_description = 'invalid parameter grant_type, it must be ('.implode('|',$server->getGrants()).')';
	}
	
	if(!$client_id){
		$error = 'invalid request';
		$error_description = 'missing parameter client_id';
	}
	else if(!$client_secret){
		$error = 'invalid request';
		$error_description = 'missing parameter client_secret';
	}
	else if(!$server->checkClientId($client_id)){
		$error = 'invalid parameter';
		$error_description = 'invalid client_id';
	}
	 else if(!$server->checkClient($client_id ,$client_secret)){
		$error = 'invalid client credential';
		$error_description = 'the client id and client secret do not match';
	}


	 if(!$code){
		$error = 'invalid request';
		$error_description = 'missing parameter code';
	}
	else if(!$server->checkCodeId($code)){
		$error = 'invalid parameter';
		$error_description = 'invalid authorization code';
	}
	else if($server->checkCodeExpired($code)){
		$error = 'invalid parameter';
		$error_description = 'the authorization code has expired';
	}


	

	if($error){
		header('content-type : application/json');
		echo json_encode(array('error' => $error,'error_description ' => $error_description));
	}
	else{
		//good job
		// $_SESSION['oauth'] = $_REQUEST;

			// $token = $server->checkReadyCode($client_id,$_SESSION['user_id']);

		
			$params = array();
			$token = $server->generateToken();
			if($server->addToken($token,$client_id,'3')){
				$params = array(
							'access_token' => $token,
							'refresh_token' => null,
							'token_type' => 'bear',
							'expire_at' => time()+Server::TOKEN_EXPIRE
							);
			}
			else{
				$params = array(
							'error' => 'error encored',
							'error_description' => 'an error has encored'
							);
			}
			header('content-type:application/json');
			echo json_encode($params);
		}
		
	}
	
?>