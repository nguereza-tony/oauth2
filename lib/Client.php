<?php
	class Client{
		
		protected $id = null;
		protected $secret = null;
		protected $name = null;
		protected $redirectUrl = null;
		protected $userId = null;
		protected $description = null;
		
		
		public function __construct(array $config = array()){
			$this->hydrate($config);
		}
		
		
		public function hydrate(array $config){
			foreach($config as $key => $value){
				if(strpos($key, '_') != false){
					$temp = explode('_',$key);
					$keys = array();
					$i = 0;
					foreach($temp as $v){
						if($i != 0){
							$keys[] = ucfirst($v);
						}
						else{
							$keys[] = $v;
						}
					}
					$key = implode('',$keys);
				}
				
				$method = 'set'.ucfirst($key);
				if(method_exists($this, $method)){
					$this->$method($value);
				}
			}
		}
		
		public function setId($id){
			$this->id = $id;
		}
		
		public function setDescription($description){
			$this->description = $description;
		}
		
		
		public function setUserId($uid){
			$this->userId = $uid;
		}
		
		
		public function setSecret($secret){
			$this->secret = $secret;
		}
		
		public function setName($name){
			$this->name = $name;
		}
		
		public function setRedirectUrl($url){
			$this->redirectUrl = $url;
		}
		
		public function getId(){
			return $this->id;
		}
		
		public function getDescription(){
			return $this->description;
		}
		
		
		public function getUserId(){
			return $this->userId;
		}
		
		public function getSecret(){
			return $this->secret;
		}
		
		public function getName(){
			return $this->name;
		}
		
		public function getRedirectUrl(){
			return $this->redirectUrl;
		}
	}
?>