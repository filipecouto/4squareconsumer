<?php

require_once '/php/controllers/Controller.php';
require_once '/php/database/UserDatabase.php';
require_once '/php/models/Response.php';
require_once '/php/models/User.php';

class UsersController extends Controller {

	private $thisDatabase;

	function __construct() {
		$this->thisDatabase = new UserDatabase();
	}

	/*
	endpoint: /users/register
	params: {
		email
		password
	}
	this endpoint should receive the parameters via POST
	*/
	public function register() {
		$response = new Response();

		if(!isset($_POST["email"]) || !isset($_POST["password"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "Information is not complete"
				)
			);

			echo $response->getJsonData();
			return;
		}

		$email = $_POST["email"];
		$password = $_POST["password"];

		if(!User::validateEmail($email)) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "Email is not valid"
				)
			);

			echo $response->getJsonData();
			return;
		}

		if(!User::validatePassword($password)) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "Password should have at least 6 characters"
				)
			);

			echo $response->getJsonData();
			return;
		}

		if($this->thisDatabase->isUserRegistered($email)) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "That email is already used"
				)
			);

			echo $response->getJsonData();
			return;
		}

		$userId = $this->thisDatabase->registerUser($email, $password);

		$sessionToken = $this->thisDatabase->createSession($userId);

		if(is_null($sessionToken)) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "An error occurred"
				)
			);

			echo $response->getJsonData();
			return;
		}

		$response->setStatus(
			array(
				"status" => "Ok",
				"message" => "Registered"
			)
		);
		$response->setData(
			array(
				"session_id" => $sessionToken
			)
		);

		echo $response->getJsonData();
	}
}

?>