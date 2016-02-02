<?php

	

	if(!function_exists('curl_init')){
		 throw new Exception('Programmation Informatique needs the PHP CURL extension');
	}
	
	if(!function_exists('json_decode')){
		 throw new Exception('Programmation Informatique needs the PHP JSON extension');
	}

	
	
	class PI{
		
		
		const VERSION = '1.0.0';
		const SIGNED_REQUEST_ALGORITHM = 'sha256';
		
		protected static $domain_maps = array(
												'user' => 'http://localhost/test/oauth2server/user.php',
												'token' => 'http://localhost/test/oauth2server/token.php',
												'code' => 'http://localhost/test/oauth2server/code.php',
											);
		
			

		protected $curl = null;	
		protected $appId = null;
		protected $accessToken = null;
		protected $appSecret = null;
		protected $state = null;
		 protected $code = null;
		
		protected $curlOptionsArray = array(
											CURLOPT_FAILONERROR => true,
											CURLOPT_RETURNTRANSFER => true,
											CURLOPT_HEADER => false,
											CURLOPT_ENCODING => 'UTF-8',
											CURLOPT_CONNECTTIMEOUT => 10,
											CURLOPT_FOLLOWLOCATION => true,
											CURLOPT_SSL_VERIFYPEER => false,
											);


		
		public function __construct(array $config = null){
			if(!empty($config['appSecret'])){
				$this->setAppSecret($config['appSecret']);
			}
			else{
				throw new Exception('You must set your app secret');
			}
			
			if(!empty($config['appId'] )){
				$this->setAppId($config['appId']);
			}
			else{
				throw new Exception('You must set your app ID');
			}
			
			if(!empty($config['accessToken'] )){
				$this->setAccessToken($config['accessToken']);
			}
		}

		public function setAppSecret($appSecret){
			$this->appSecret= $appSecret;
			return $this;
		}

		public function setAppId($appId){
			$this->appId= $appId;
			return $this;
		}

public function setCode($code){
			$this->code= $code;
			return $this;
		}

public function getCode(){
			return $this->code;
		}

		public function setAccessToken($accessToken){
			$this->accessToken= $accessToken;
$this->setSession('accessToken', $accessToken );
			return $this;
		}
		
		public function setState($state){
			$this->state = $state;
			return $this;
		}
		
		public function getAppSecret(){
			return $this->appSecret;
		}

		public function getAccessToken(){
if( $this->accessToken){
			return $this->accessToken;
}
return $this->getSession('accessToken');
		}
		
		public function getAppId(){
			return $this->appId;
		}

		public function getState(){
				return $this->state;
		}

public function csrf(){
$this->state = sha1(uniqid(mt_rand(), true));
$this->setSession('state', $this->state );
}

public function getSession($key){
return isset($_SESSION['pi_'.$this->getAppId().'_'.$key])? $_SESSION[ 'pi_'.$this->getAppId().'_'.$key] :null;
}

public function setSession($key, $value){
$_SESSION['pi_'.$this->getAppId().'_'.$key] = $value;
}

public function clearSession($key){
unset($_SESSION['pi_'.$this->getAppId().'_'.$key]) ;
}

public function clearAllSession(){
$parts = array('code','accessToken','state');
foreach($parts as $key){
$this->clearSession($key);
}
}
		
		public function get($name , array $params = array()){
			$this->authentification();
			$url = null;
			$config = array('nonce' => time());
			if(array_key_exists($name,self::$domain_maps)){
				$url = self::$domain_maps[$name];
			}
			else{
				$url = $name;
			}
			
			
			$url_info = parse_url($url);
			if(!empty($url_info['query'])){
				parse_str($url_info['query'],$config);
			}
			if(!empty($params)){
				$config = array_merge($config,$params);
			}
			extract($url_info);
			$url = $scheme.'://'.$host;
			$url .= (isset($port)) ? ':'.$port : '';
			$url .= $path;
			$url .= '?'.http_build_query($config,null,'&');
			$signature = $this->sign($url);
			
			$this->curlOptionsArray[CURLOPT_URL] = $url.'&signature='.$signature;
			$this->curl = curl_init();
			curl_setopt_array($this->curl,$this->curlOptionsArray);
			$exec = curl_exec($this->curl);
			if(curl_error($this->curl)){
				$exec = array('error' => 'curl error','error_description' =>curl_error($this->curl));
				$exec = json_encode($exec);
			}
			
			return json_decode($exec,true);
		}
		
		
		
		public function post($name , array $params = array()){
			//$this->authentification();
			$url = null;
			$config = array('nonce' => time());
			if(array_key_exists($name,self::$domain_maps)){
				$url = self::$domain_maps[$name];
			}
			else{
				$url = $name;
			}
			
			
			$url_info = parse_url($url);
			if(!empty($url_info['query'])){
				parse_str($url_info['query'],$config);
			}
			
			extract($url_info);
			$url = $scheme.'://'.$host;
			$url .= (isset($port)) ? ':'.$port : '';
			$url .= $path;
			$url .= '?'.http_build_query($config,null,'&');
			$signature = $this->sign($url);
			
			$this->curlOptionsArray[CURLOPT_URL] = $url.'&signature='.$signature;
			
			$this->curlOptionsArray[CURLOPT_POST] = true;
			if(!empty($params)){
				ksort($params);
			}
			$this->curlOptionsArray[CURLOPT_POSTFIELDS] = http_build_query($params);
			$this->curl = curl_init();
			curl_setopt_array($this->curl,$this->curlOptionsArray);
			$exec = curl_exec($this->curl);
			if(curl_error($this->curl)){
				$exec = array('error' => 'curl error','error_description' =>curl_error($this->curl));
				$exec = json_encode($exec);
			}
			
			return json_decode($exec,true);
		}
		
		
		
		
		public function getAuthorizationAccessToken($code,$callback = null){
			$callback_url = $callback;
			if($callback == null){
				$callback_url = 'http://'. $_SERVER['HTTP_HOST'];
				if($_SERVER['QUERY_STRING'] === ''){
						$callback_url .= $_SERVER['PHP_SELF'];
				}
				else{
					 $callback_url .= $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
				}
			}
			$exec = $this->post('token',array(
												'redirect_uri' => $callback_url,
												'code' => $code,
												'client_id' => $this->getAppId(),
												'client_secret' => $this->getAppSecret(),
												'grant_type' => 'authorization_code',
								
												));
												
			return $exec;
			
		}
		

		public function getAuthorizationCode(){
			if((isset($_REQUEST['code'] ))&&( isset($_REQUEST['state']) && $_REQUEST['state'] == $this->getSession('state') )){
				return $_REQUEST['code'];
			}
				return false;
		}
		


		
		public function authentification(){
			$config = array(
							'client_secret' => $this->getAppSecret(),
							'client_id' =>  $this->getAppId()
							);

			if($this->getAccessToken() != null){
				 $config['access_token'] = $this->getAccessToken();
			}
			$this->curlOptionsArray[CURLOPT_POSTFIELDS] = http_build_query($config);
		}
		
		
		public function getAuthorizationUrl(array $params = array()){
			$config = array();
			$callback_url = null;
			if(!isset($params['redirect_uri'])){
				$callback_url = 'http://'.$_SERVER['HTTP_HOST'];
				if($_SERVER['QUERY_STRING'] === ''){
						$callback_url .= $_SERVER['PHP_SELF'];
				}
				else{
					 $callback_url .= $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
				}
			}
			else{
				$callback_url = $params['redirect_uri'];
			}
			
			$url_info = parse_url($callback_url);
			$conf = array();
			if(!empty($url_info['query'])){
				parse_str($url_info['query'],$conf);
				if(isset($conf['code'])){
					unset($conf['code']);
				}
				if(isset($conf['state'])){
					unset($conf['state']);
				}
					
				
			}
			
			extract($url_info);
			$callback_url = $scheme.'://'.$host;
			$callback_url .= (isset($port)) ? ':'.$port : '';
			$callback_url .= $path;
			if(!empty($conf)){
				$callback_url .= '?'.http_build_query($conf,null,'&');
			}
$this->csrf();
			$defaults = array(
								'redirect_uri' => $callback_url,
								'client_id' => $this->getAppId(),
								'state' => $this->getState(),
								'version' => self::VERSION,
								'response_type' => 'authorization_code',
								'nonce' => time(),
							);
							
			$url = self::$domain_maps['code'];
			if(!empty($params)){
				$config = array_merge($defaults , $params);
			}	
			else{
				$config = $defaults;
			}
			
			
			
			ksort($config);
			$url .= '?'.http_build_query($config);
			$signature = $this->sign($url);
			return $url.'&signature='.$signature;
		}
		
		public function sign($data){
			return base64_encode(hash_hmac(self::SIGNED_REQUEST_ALGORITHM,$data,sha1($this->getAppSecret())));
		}
		
		
	}


?>
