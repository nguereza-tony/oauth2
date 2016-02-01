<?php
require_once('config.php');
$form = true;
if(isset($_POST['register_api'])){
extract($_POST);
$message = array();

	if(empty($name)){
		$message['name'] = '<p>Please enter your app name</p>';
	}
	
	if(empty($description)){
		$message['description'] = '<p>Please enter your app description</p>';
	}

	if(count($message) == 0){
		
		$id =random();
		$secret_show = random(50,false) ;
		$secret = sha1($secret_show);
		$client = new Client(array(
								'name' => $name,
								'id' => $id,
								'secret' => $secret,
								'user_id' => 'nguereza',
								'redirect_url' => $callback,
								'description' => $description
							));
		if($server->addClient($client)){
			$message['finish'] = '<p>Your App ID : <b>'.$id.'</b></p>';
			$message['finish'] .= '<p>Your App secret : <b>'.$secret_show.'</b></p>';
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
	<?php echo isset($message['name'])?$message['name']:null;?>
	<label for = 'name'>Your App name:</label>
	<input type = 'text' name = 'name' />
</div>
<div>
	<?php echo isset($message['description'])?$message['description']:null;?>
	<label for = 'name'>App description:</label>
	<input type = 'text' name = 'description' />
</div>
<div>
	<?php echo isset($message['callback'])?$message['callback']:null;?>
	<label for = 'name'>Callback URL:</label>
	<input type = 'text' name = 'callback' />
</div>
<div>
	<input type = 'submit' name = 'register_api' value = "Register" />
</div>
</form>	
<?php
}

?>