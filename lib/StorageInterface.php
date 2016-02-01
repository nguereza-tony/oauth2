<?php

interface StorageInterface{

public function __construct(Array $config);
public function getConnexion();

public function execute($query);

public function count($query);

public function data($query);

}

?>