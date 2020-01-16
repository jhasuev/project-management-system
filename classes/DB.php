<?php

class DB {
	public $mysqli;
	
	public function __construct(){
		// conecting with db
		$this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	}

	public function __destruct(){
		// disconecting with db
		$this->mysqli->close();
	}
}