<?php
require_once('config.php');
$form = true;
if(isset($_POST['register_user'])){
extract($_POST);
$message = array();

	if(empty($username)){
		$message['username'] = '<p>Please enter your username</p>';
	}
	
	if(empty($email)){
		$message['email'] = '<p>Please enter your E-mail</p>';
	}
	
	if(empty($password)){
		$message['password'] = '<p>Please enter your password</p>';
	}
	
	if(empty($lastname)){
		$message['lastname'] = '<p>Please enter your lastname</p>';
	}
	
	if(empty($firstname)){
		$message['firstname'] = '<p>Please enter your firstname</p>';
	}

	if(count($message) == 0){
		
		$id =random();
		$password = sha1($password);
		$user = new User(array(
								'username' => $username,
								'id' => $id,
								'password' => $password,
								'nom' => $lastname,
								'prenom' => $firstname,
								'email' => $email,
							));
		if($server->addUser($user)){
			$message['finish'] = '<p>Register successfully</p>';
			$form = false;
		}
		else{
			$message['finish'] = '<p>An error has encored please try again</p>';
		}
							
	}

}


echo isset($message['finish'])?$message['finish']:null;

if($form){
?>
<form action = '' method = 'post'>
<div>
	<?php echo isset($message['username'])?$message['username']:null;?>
	<label for = 'name'>Your  username:</label>
	<input type = 'text' name = 'username' />
</div>
<div>
	<?php echo isset($message['password'])?$message['password']:null;?>
	<label for = 'name'>Your  password:</label>
	<input type = 'password' name = 'password' />
</div>

<div>
	<?php echo isset($message['email'])?$message['email']:null;?>
	<label for = 'name'>Your  E-mail:</label>
	<input type = 'text' name = 'email' />
</div>

<div>
	<?php echo isset($message['firstname'])?$message['firstname']:null;?>
	<label for = 'name'>Your  firstname:</label>
	<input type = 'text' name = 'firstname' />
</div>

<div>
	<?php echo isset($message['lastname'])?$message['lastname']:null;?>
	<label for = 'name'>Your  lastname:</label>
	<input type = 'text' name = 'lastname' />
</div>


<div>
	<input type = 'submit' name = 'register_user' value = "Register" />
</div>
</form>	
<?php
}

?>