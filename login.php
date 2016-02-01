<?php
require_once('config.php');
$form = true;
if(isset($_POST['login'])){
extract($_POST);
$message = array();
if(empty($username)){
	$message['username'] = '<p>Please enter your username</p>';
}
else if(empty($password)){
	$message['password'] = '<p>Please enter your password</p>';
}
else if(!$server->checkUser($username, sha1($password))){
	$message['username'] = '<p>Invalid username or password</p>';
}

if(count($message) == 0){
	$data = $server->userData($username);
	$_SESSION['user_id'] = $data['id'];
	$_SESSION['username'] = $data['username'];
	if(!isset($_GET['next'])){
		header('location:./');
	}
	else{
		header('location:'.$_GET['next']);
	}
}
	
}


echo isset($message['finish'])?$message['finish']:null;
if($form){
?>
<form action = '' method = 'post'>
<div>
	<?php echo isset($message['username'])?$message['username']:null;?>
	<label for = 'name'>Nom d'utilisateur :</label>
	<input type = 'text' name = 'username' />
</div>
<div>
	<?php echo isset($message['password'])?$message['password']:null;?>
	<label for = 'name'>Mot de passe :</label>
	<input type = 'password' name = 'password' />
</div>
<div>
	<input type = 'submit' name = 'login' value = "Connect" />
</div>
</form>	
<?php
}

?>