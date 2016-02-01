<?php

class PdoStorage implements StorageInterface{
	
	public $dsn = null;
	public $username = null;
	public $password = '';
	public $options = array();
	public $connexion = null;
	
	public function __construct(Array $config){
		if(!empty($config)){
			
			if(isset($config['dsn'])){
				$this->setDsn($config['dsn']);
			}
			else{
				throw new Exception('Missing parameter dsn');
			}
			
			if(isset($config['username'])){
				$this->setUsername($config['username']);
			}
			else{
				throw new Exception('Missing parameter username');
			}
			
			if(isset($config['options']) && is_array($config['options'])){
				$this->setOptions($config['options']);
			}
			
			try{
				$this->connexion = new PDO($this->getDsn(), $this->getUsername(), $this->getPassword(),$this->getOptions());
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}
	}
	
	
	public function setDsn($dsn){
		$this->dsn = $dsn;
		return $this;
	}
	
	
	public function setUsername($username){
		$this->username = $username;
		return $this;
	}
	
	public function setPassword($password){
		$this->password = $password;
		return $this;
	}
	
	public function setOptions(array $options){
		$this->options = $options;
		return $this;
	}
	
	
	
	public function getDsn(){
		return $this->dsn;
	}
	
	
	public function getUsername(){
		return $this->username;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	
	public function execute($query){
		return $this->getConnexion()->exec($query);
	}
	
	public function count($query){
		$return = $this->getConnexion()->query($query);
		return $return->rowCount();
	}


	 public function data($query){
		$return = $this->getConnexion()->query($query);
		return $return->rowCount()==0?null:$return->fetch();
	}


	public function getConnexion(){
		return $this->connexion;
	}
}

?>