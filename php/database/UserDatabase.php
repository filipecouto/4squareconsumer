<?php

require_once '/php/database/Database.php';
require_once '/php/database/MySQLDatabase.php';
require_once '/php/models/User.php';
require_once '/php/models/SaltPassword.php';

class UserDatabase extends Database {

	private $databaseImplementation;

	function __construct() {
		$this->databaseImplementation = new MySQLDatabase();
	}

	public function registerUser($email, $password) {
		$salter = new SaltPassword();
		$salter->setLogin($email);
		$salter->setPassword($password);
		$salter->generatePassword();

		$saltedPassword = $salter->getSaltedPassword();
		$salt = $salter->getSalt();

		$connection = $this->databaseImplementation->retrieveConnection();

		$insertQuery = "INSERT INTO users(login, password, salt) VALUES (:login, :password, :salt);";

		$preparedStatement = $connection->prepare($insertQuery);

		$preparedStatement->bindValue(':login', $email, PDO::PARAM_STR);
		$preparedStatement->bindValue(':password', $saltedPassword, PDO::PARAM_STR);
		$preparedStatement->bindValue(':salt', $salt, PDO::PARAM_STR);

		$preparedStatement->execute();

		return $connection->lastInsertId();
	}

	public function isUserRegistered($email) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$query = "SELECT * FROM users WHERE users.login = :email LIMIT 1";

		$preparedStatement = $connection->prepare($query);

		$preparedStatement->bindValue(':email', $email, PDO::PARAM_STR);

		$preparedStatement->execute();

		return $preparedStatement->rowCount() > 0;
	}

	public function createSession($userId) {
		if(is_null($userId)) {
			return null;
		}

		$connection = $this->databaseImplementation->retrieveConnection();

		$query = "SELECT * FROM users WHERE users.id = :user_id LIMIT 1";
		$preparedStatement = $connection->prepare($query);
		$preparedStatement->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$preparedStatement->execute();

		if($preparedStatement->rowCount() == 0) return null;

		$username = "";
		while($user = $preparedStatement->fetch()) {
			$username = isset($user["login"]) ? $user["login"] : "";
		}

		if($username == "") return null;

		$token = hash("sha512", $username . time() . $userId);

		$insertQuery = "INSERT INTO sessions(user_id, token) VALUES (:user_id, :token);";

		$preparedStatement = $connection->prepare($insertQuery);
		$preparedStatement->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$preparedStatement->bindValue(':token', $token, PDO::PARAM_STR);
		$preparedStatement->execute();

		return $token;
	}

	public function getUserFromSession($sessionId) {
		if(is_null($sessionId)) {
			return null;
		}

		$connection = $this->databaseImplementation->retrieveConnection();

		$query = "SELECT users.* FROM users INNER JOIN sessions ON users.id = sessions.user_id AND sessions.token = :session_id AND sessions.validity = 1 LIMIT 1;";
		$preparedStatement = $connection->prepare($query);
		$preparedStatement->bindValue(':session_id', $sessionId);
		$preparedStatement->execute();

		if($preparedStatement->rowCount() == 0) return null;

		$userObj = new User();

		while($user = $preparedStatement->fetch()) {
			$userObj->setId(isset($user["id"]) ? $user["id"] : null);
			$userObj->setLogin(isset($user["login"]) ? $user["login"] : "");
		}

		return $userObj;
	}
}

?>