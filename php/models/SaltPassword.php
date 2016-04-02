<?php

require_once '/php/models/Model.php';

class SaltPassword extends Model {

	private $login;
	private $password;
	private $salt;
	private $saltedPassword;

	function __construct() {

	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setSalt($salt) {
		$this->salt = $salt;
	}

	public function setSaltedPassword($saltedPassword) {
		$this->saltedPassword = $saltedPassword;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getSalt() {
		return $this->salt;
	}

	public function getSaltedPassword() {
		return $this->saltedPassword;
	}

	private function generateSalt() {
		$salt = hash("sha512", $this->getLogin() . time());
		$this->setSalt($salt);
	}

	public function generatePassword() {
		$this->generateSalt();

		$saltedPassword = hash("sha512", $this->getPassword() . $this->getSalt());
		$this->setSaltedPassword($saltedPassword);
	}
}

?>