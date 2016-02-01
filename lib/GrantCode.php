<?php

class GrantCode implements GrantInterface{


public function name(){
	return 'authorization_code';
}


public function requiredParams(){
	return array('authorization_code');
}



}