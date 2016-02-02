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
$token = $pi->getAccessToken();
if( $token ){ 	
/*$r = $pi->post('http://localhost/test/oauth2server/register_user.php',array(
																			'register_user'=>'Register',
																			'username' => 'prestige',
																			'password' => 'azerty',
																			'email' => 'yaindeprestige@gmail.com',
																			'lastname' => 'YAINDE',
																			'firstname' => 'Prestige'
																			));
																			*/

//var_dump($r);

$result = $pi->get('user');
if(!isset($result['error'])){
	foreach($result as $key => $value){
		echo "$key : $value<br>";
	}
}
else{
var_dump($result);
$pi->clearAllSession();
}
 }
else{


$code = $pi->getAuthorizationCode();

if(!$code){
	$url = $pi->getAuthorizationUrl(array('scope'=>'photo'));
	echo "<a href = '$url'>login with API Web</a>";
}
else{
	$token = $pi->getAuthorizationAccessToken($code);
	if(isset($token['access_token'])){
		$pi->setAccessToken($token['access_token'] );
		$result = $pi->get('user');
		if(!isset($result['error'])){

extract($result);
echo "lastname : $prenom<br>";
echo "firstname : $nom<br>";
echo "email : $email<br>";
echo "username : $username<br>";
}
else{
var_dump($result);

}
	}
	else{
		
		var_dump($token);
	
	}

}
}
//var_dump($_SESSION);
?>