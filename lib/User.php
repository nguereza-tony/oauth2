<?php
	class User{
		
		protected $id = null;
		protected $nom = null;
		protected $prenom = null;
		protected $email = null;
		protected $password = null;
		protected $username = null;
		
		
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
		
		public function setNom($nom){
			$this->nom = $nom;
		}
		
		public function setPrenom($prenom){
			$this->prenom = $prenom;
		}
		
		public function setEmail($email){
			$this->email = $email;
		}
		
		
		public function setPassword($password){
			$this->password = $password;
		}
		
		public function setUsername($username){
			$this->username = $username;
		}
		
		public function getId(){
			return $this->id;
		}
		
		public function getNom(){
			return $this->nom;
		}
		
		
		public function getPrenom(){
			return $this->prenom;
		}
		
		public function getPassword(){
			return $this->password;
		}
		
		public function getUsername(){
			return $this->username;
		}
		
		public function getEmail(){
			return $this->email;
		}
	}
?>