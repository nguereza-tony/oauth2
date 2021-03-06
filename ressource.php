<?php
	require_once('config.php');

	$error = null;
	$error_description = null;
	$client_id = $server->request('client_id');
	$client_secret = $server->request('client_secret');
	$token = $server->request('access_token');



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


	if(!$token){
		$error = 'invalid request';
		$error_description = 'missing parameter access_token';
	}
	else if(!$server->checkTokenId($token)){
		$error = 'invalid parameter';
		$error_description = 'invalid authorization access token';
	}
	else if($server->checkTokenExpired($token)){
		$error = 'invalid parameter';
		$error_description = 'the authorization access token has expired';
	}


	 header('content-type : application/json');

	if($error){
		echo json_encode(array('error' => $error,'error_description ' => $error_description));
	}
	else{
		//good job
		

		$params = array('tony'=>6+8);
		
	
				

		echo json_encode($params);
	}
			
		
		
		
		
	
	


?>