<?php

class Server {
	const CODE_EXPIRE = 3600 ; //in second
	const TOKEN_EXPIRE = 3600 ; //in second
	protected $storage = null;
	protected $grants = array();
	protected $requests = array();
	protected $responses = array();
	protected $scopes = array();
	
	public function __construct(StorageInterface $storage){
		$this->setStorage($storage);
	}
	
	
	public function setStorage($storage){
		$this->storage = $storage;
	}
	
	public function getStorage(){
		return $this->storage;
	}
	
	public function addGrant(GrantInterface $grant){
		if(!in_array($grant->name(), $this->grants)){
			$this->grants[] = $grant->name();
		}
	}
	
	public function grant($name){
		if(in_array($name, $this->grants)){
			return true;
		}
		return false;
	}
	
	public function getGrants(){
		return $this->grants;
	}
	
	public function addScope($scope){
		if(is_array($scope)){
			foreach($scope as $key => $value){
				if(!array_key_exists($key, $this->getScopes())){
					$this->scopes[$key] = $value;
				}
			}
		}
		
	}
	
	public function scope($name){
		return isset($this->scopes[$name])?$this->scopes[$name]:null;
	}
	
	public function getScopes(){
		return $this->scopes;
	}
	
	public function run(){
		$this->requests = $_REQUEST;
		$this->deleteExpireToken(time());
		$this->deleteExpireCode(time());
	}
	
	public function request($name){
		return isset($this->requests[$name])?$this->requests[$name]:null;
	}

	public function getRequests(){
		return $this->requests;
	}
	
	public function addClient(Client $client){
		return $this->storage->execute('INSERT INTO client(id,secret,name,redirect_url,user_id,description) VALUES ("'.$client->getId().'" , "'.$client->getSecret().'" ,"'.$client->getName().'" ,"'.$client->getRedirectUrl().'" ,"'.$client->getUserId().'","'.$client->getDescription().'")');
	}
	
	public function addUser(User $user){
		return $this->storage->execute('INSERT INTO user(id,password,username,email,nom,prenom) VALUES ("'.$user->getId().'" , "'.$user->getPassword().'" ,"'.$user->getUsername().'" ,"'.$user->getEmail().'" ,"'.$user->getNom().'","'.$user->getPrenom().'")');
	}
	
	public function addCode($code, $client_id , $user_id, $scope = null){
		
		return $this->storage->execute('INSERT INTO code(id,expire,client_id,user_id,scope) VALUES ("'.$code.'" , "'.(time() + self::CODE_EXPIRE).'" ,"'.$client_id.'" ,"'.$user_id.'", "'.$scope.'") ');
	}

	public function addToken($token, $client_id , $user_id){
		return $this->storage->execute('INSERT INTO token(id,client_id,expire,user_id) VALUES ("'.$token.'" , "'.$client_id.'" ,"'.( time() + self::TOKEN_EXPIRE ).'" ,"'.$user_id.'") ');
	}

	public function deleteExpireToken($time){	
		return $this->storage->execute('DELETE FROM token WHERE expire + 60 <= "'.$time.'"');
	}

	public function deleteExpireCode($time){
		return $this->storage->execute('DELETE FROM code WHERE expire + 10 <= "'.$time.'"');
	}

	
	public function checkClientId($id){
		return $this->storage->count("SELECT * FROM client WHERE id = '$id'");
	}

	public function checkTokenId($id){
		return $this->storage->count("SELECT * FROM token WHERE id = '$id'");
	}
	
	public function generateToken(){
		return  sha1(uniqid(mt_rand(), true));
	}

	public function generateCode(){
		return  md5(uniqid(mt_rand(), true));
	}
	
	public function checkClient($id,$secret){
		return $this->storage->count("SELECT * FROM client WHERE id = '$id' AND secret = '".sha1($secret)."' ");
	}
	
	public function checkUser($username,$password){
		return $this->storage->count("SELECT * FROM user WHERE username = '$username' AND password = '$password' ");
	}

	public function checkReadyCode($client_id,$user_id){
		$time = time();
		$return = $this->storage->data("SELECT id FROM code WHERE client_id = '$client_id' AND user_id = '$user_id' AND expire > '$time' ");
		return isset( $return['id'] )? $return['id']:null;
	}
	
	public function userData($search){
		$return = $this->storage->data("SELECT * FROM user WHERE id = '$search' OR username = '$search' ");
		return $return ? $return:null;
	}
	
	public function userIsConnected(){
		return isset($_SESSION['user_id']) && isset($_SESSION['username']);
	}
	
	
	public function signature($client_id){
		$data = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$data = substr($data,0,strpos($data,'&signature'));
		$return = $this->storage->data("SELECT * FROM client WHERE id = '$client_id' ");
		$secret=  isset( $return['secret'] )? $return['secret']:null;
		return base64_encode(hash_hmac('sha256',$data,$secret ));
	}


	public function checkReadyToken($client_id,$user_id){
		$time = time();
		$return = $this->storage->data("SELECT * FROM token WHERE client_id = '$client_id' AND user_id = '$user_id' AND expire > '$time' ");
		if($return){
			return $return;
		}
		return false;
	}
	
	public function getClientName($client_id){
		$return = $this->storage->data("SELECT name FROM client WHERE id = '$client_id' ");
		if($return){
			return $return['name'];
		}
		return false;
	}
	
	public function checkCodeId($code){
		return $this->storage->count("SELECT * FROM code WHERE id = '$code'");
	}

	public function checkCodeExpired($code){
		return $this->storage->count("SELECT * FROM code WHERE id = '$code' AND expire <= '".time()."' ");
	}

	public function checkTokenExpired($token){
		return $this->storage->count("SELECT * FROM token WHERE id = '$token' AND expire <= '".time()."' ");
	}

	
	public function checkCode($id,$client){
		return $this->storage->count("SELECT * FROM code WHERE id = '$id' AND client_id = '$client'");
	}
		
}
?>