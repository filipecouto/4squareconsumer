<?php

class MySQL {
	private $server = "localhost";
	private $database = "desafiotp";
	private $username = "root";
	private $password = "";

	public function getServer() {
		return $this->server;
	}

	public function getDatabase() {
		return $this->database;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}
}

?>