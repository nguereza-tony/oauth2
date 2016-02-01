<?php
	session_start();
	
	function loadClass($class){
		require_once 'lib/'.$class .'.php';
	}

	spl_autoload_register('loadClass');
	
	
	function random($length = 15, $numeric = true){
		$ch = $numeric ? '1234567890' : '1234567890azertyuiopqsdfghjklmwxcvbn';
		$rnd = null;
		for($i = 0;$i<$length;$i++){
			$rnd .= $ch[mt_rand()%strlen($ch)];
		}
		return $rnd;
	}
	
	
	
	
	$code = new GrantCode();
	$pdo = new PdoStorage(array('dsn'=> 'mysql:host=localhost;dbname=oauth2','username' => 'root'));



	$server = new Server($pdo);


	$server->addGrant($code);

	$server->addScope(array(
							'basic' => 'Vos données publiques',
							'email' => 'Votre adresse E-mail',
							'photo' => 'Vos photos',
							));

	$server->run();










?>