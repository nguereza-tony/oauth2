<?php
	require_once('config.php');
	$error = null;
	$error_description = null;
	$http_code = 200;
	$response = new Response();
	



	$response_type = $server->request('response_type');
	$client_id = $server->request('client_id');
	$state = $server->request('state');
	$redirect_uri = $server->request('redirect_uri');
	$scope = $server->request('scope');
	$signature = $server->request('signature');
	

	$required = array('response_type', 'client_id', 'state', 'signature', 'redirect_uri');

	foreach($required as $value){
	if(!array_key_exists($value, $server->getRequests())){
		$error = 'invalid request';
		$error_description = 'missing parameter '.$value;
		$http_code = 400;
	}
	else{
		$$value = $server->request($value);
		switch($value){

		case 'response_type':
		if(!$server->grant($response_type)){
				$error = 'invalid parameter';
				$error_description = 'invalid parameter response_type it must be ('.implode('|',$server->getGrants()).')';
				$http_code = 406;
			}
		break;

		case 'client_id':
		if(!$server->checkClientId($client_id)){
				$error = 'invalid parameter';
				$error_description = 'invalid client_id';
				$http_code = 406;
			} 
		else if($server->signature($client_id) != $signature){
				$error = 'invalid request';
				$error_description = 'invalid request signature data is corrupted';
				$http_code = 400;
				}
		break;

		case 'redirect_uri':
			if(strtolower(substr($redirect_uri,0,7)) != 'http://' && strtolower(substr($redirect_uri,0,8)) != 'https://'){
				$error = 'invalid parameter';
				$error_description = 'the parameter redirect_uri must be an absolute url';
				$http_code = 406;
			}
		break;
		}

	}

	}

		
	
	if(!empty($server->request('scope'))){
		$scopes = explode(',' ,$server->request('scope'));
		foreach($scopes as $value){
			if(!$server->scope($value)){
				$error = 'invalid parameter';
				$error_description = 'invalid parameter scope check the documentation';
				$http_code = 406;
			}
		}
	}
	
	if(!empty($server->request('nonce'))){
		$nonce = $server->request('nonce');
		if($nonce > time()){
			$error = 'invalid request';
			$error_description = 'request expired or corrupted';
			$http_code = 400;
		}
	}

	
	if($error){
		$response->setBody(array(
								'error' =>$error,
								'error_description' => $error_description
								));
		$response->setStatutCode($http_code);
		$response->send();
	}
	else{
		if(!$server->userIsConnected()){
			header('location:login.php?next='.urlencode($_SERVER['REQUEST_URI']));
		}
		
		
		$user_id = $_SESSION['user_id'];
		$code = $server->checkReadyCode($client_id,$user_id);
		if($code){
		$params = array(
						'code' => $code,
						'state' => $state
						);
		$url_info = parse_url($redirect_uri);
		if(empty($url_info['query'])){
			$redirect_uri .= '?';
		}
		else{
			$redirect_uri .= '&';
		}
		$redirect_uri .= http_build_query($params);
			
		$response->redirect($redirect_uri);

		}
		
		$user_id = $_SESSION['user_id'];
		$code = $server->checkExistsCode($client_id,$user_id);
		if($code){
		$data = $server->getStorage()->data("SELECT * FROM code WHERE user_id = '$user_id'");
		$server->updateExpireCode($data['id']);
		$data = $server->getStorage()->data("SELECT * FROM code WHERE user_id = '$user_id'");
		$params = array(
						'code' => $data['id'],
						'state' => $state
						);
		$url_info = parse_url($redirect_uri);
		if(empty($url_info['query'])){
			$redirect_uri .= '?';
		}
		else{
			$redirect_uri .= '&';
		}
		$redirect_uri .= http_build_query($params);
		$response->redirect($redirect_uri);
		}


		if(isset($_POST['authorize'])){
			$authorize = $_POST['authorize'];
			$params = array();
			if($authorize == 'Autoriser'){
				$code = $server->generateCode();
				if($server->addCode($code,$client_id,$_SESSION['user_id'],$scope)){
					$params = array(
								'code' => $code,
								'state' => $state
								);
				}
				else{
					$params = array(
								'error' => 'error encored',
								'error_description' => 'an error has encored'
								);
				}
			}
			else{
				$params = array(
								'error' => 'access denied',
								'error_description' => 'user has denied your application'
				);
			}
		
			$url_info = parse_url($redirect_uri);
			if(empty($url_info['query'])){
				$redirect_uri .= '?';
			}
			else{
				$redirect_uri .= '&';
			}
			$redirect_uri .= http_build_query($params);
			$response->redirect($redirect_uri);
			}
		
		?>
		<form action = '' method = 'post'>
		<p>Voulez-vous autorisez l'application <b><?php echo $server->getClientName($client_id);?></b> ?</p>
		<p>il est en mesure de consulter :</p>
		<ul>
			<li>Vos donnees publiques</li>
			<?php
			if(!empty($server->request('scope'))){
				$scope = explode(',' ,$server->request('scope'));
				foreach($scope as $value){
					if($server->scope($value)){
						echo "<li>".$server->scope($value)."</li>";
					}
				}
			}	
			?>
		</ul>
		<input type = "submit" value = "Autoriser" name = "authorize" /> <input type = "submit" value = "Refuser" name = "authorize" />
		</form>
	<?php	
		
	}
	


?>