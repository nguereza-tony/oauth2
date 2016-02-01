<?php

class Response{

	private $headers = array();
	public $httpStatutCode = 200;
	public $body = array();
	public $format = 'json';
	
	protected $http_code = array(
									100 => 'Continue',
									101 => 'Switching Protocols',
									200 => 'OK',
									201 => 'Created',
									202 => 'Accepted',
									203 => 'Non-Authoritative Information',
									204 => 'No Content',
									205 => 'Reset Content',
									206 => 'Partial Content',
									300 => 'Multiple Choices',
									301 => 'Moved Permanently',
									302 => 'Found',
									303 => 'See Other',
									304 => 'Not Modified',
									305 => 'Use Proxy',
									307 => 'Temporary Redirect',
									400 => 'Bad Request',
									401 => 'Unauthorized',
									402 => 'Payment Required',
									403 => 'Forbidden',
									404 => 'Not Found',
									405 => 'Method Not Allowed',
									406 => 'Not Acceptable',
									407 => 'Proxy Authentication Required',
									408 => 'Request Timeout',
									409 => 'Conflict',
									410 => 'Gone',
									411 => 'Length Required',
									412 => 'Precondition Failed',
									413 => 'Request Entity Too Large',
									414 => 'Request-URI Too Long',
									415 => 'Unsupported Media Type',
									416 => 'Requested Range Not Satisfiable',
									417 => 'Expectation Failed',
									418 => 'I\'m a teapot',
									500 => 'Internal Server Error',
									501 => 'Not Implemented',
									502 => 'Bad Gateway',
									503 => 'Service Unavailable',
									504 => 'Gateway Timeout',
									505 => 'HTTP Version Not Supported',
								);
		
		
	public function __construct($body = null, $http_code = 200, $format = 'json'){
		$this->setStatutCode($http_code);
		$this->body = $body;
		$this->format = $format;
	}
	
	public function send(){
		if(!headers_sent()){
			foreach($this->getHeaders() as $key => $value){
				header($key .':'.$value);
			}
			switch($this->format){
				case 'json':
					header('Content-type : application/json');
				break;
				default:
					header('Content-type : text/plain');
				break;
			}
			header('HTTP/1.1 '.$this->httpStatutCode.' '.$this->http_code[$this->httpStatutCode]);
			echo json_encode($this->body);
		}
	}
	
	public function getHeaders(){
		return $this->headers;
	}
	
	
	public function redirect($url){
		
if(!headers_sent()){
				 header('Location:'.$url);
		}
else{
return;
}
	}
	
	public function getHeader($name){
		return isset($this->headers[$name])?$this->headers[$name] : null;
	}
	
	
	public function setHeader($name,$value){
		$this->headers[$name] = $value;
	}
	
	public function setStatutCode($code){
		$this->httpStatutCode = $code;
	}
	
	public function setFormat($format){
		$this->format = $format;
	}
	
	public function setBody($body){
		$this->body = $body;
	}
	
}

?>