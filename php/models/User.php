<?php

require_once '/php/models/Model.php';

class User extends Model {
	
	private $id;
	private $login;
	private $password;
	private $salt;
	private $userType;

	public static function validateEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function validatePassword($password) {
		return strlen($password) >= 6;
	}

	public function getId() {
		return $this->id;
	}

	public function getLogin() {
		return $this->login;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setLogin($login) {
		$this->login = $login;
	}
}

?>