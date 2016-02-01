<?php
require_once('config.php');



try{
$pi = new PI(array(
					'appId' => '259337193970440' , 
					'appSecret' => '7tbd501mt5bnnylb1u7mld4vhkwc25zrmlvxodcavob1m3rbm8',
				));

}catch(Exception $e){
	echo $e->getMessage();
	$pi = null;
}

$code = $pi->getAuthorizationCode();

if(!$code){
	$url = $pi->getAuthorizationUrl(array('scope'=>'email,photo'));
	echo "<a href = '$url'>login with API Web</a>";
}
else{
	$token = $pi->getAuthorizationAccessToken($code);
	if(isset($token['access_token'])){
		$pi->setAccessToken($token['access_token'] );
		$result = $pi->get('data');
		var_dump( $token);
	}
	else{
		
		var_dump($token);
		$url = $pi->getAuthorizationUrl(array('scope'=>'email,photo'));
		echo "<a href = '$url'>login with API Web</a>";
	}

}

?>