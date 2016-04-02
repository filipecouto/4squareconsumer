<?php

require_once '/php/controllers/Controller.php';
require_once '/php/external/FourSquareApi.php';
require_once '/php/database/PlacesDatabase.php';
require_once '/php/database/UserDatabase.php';
require_once '/php/models/Response.php';
require_once '/php/models/Venue.php';

class PlacesController extends Controller {

	private $api;
	private $latitude;
	private $longitude;
	private $place;
	private $query;

	private $thisDatabase;
	private $userDatabase;

	function __construct() {
		$this->api = new FourSquareApi();
		$this->thisDatabase = new PlacesDatabase();
		$this->userDatabase = new UserDatabase();
	}

	/*
	endpoint: /places/search
	params: {
		where
		latitude
		longitude
		query
	}
	this endpoint should receive the parameters via GET
	*/
	public function search() {
		$response = new Response();

		if(!isset($_GET["query"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "Query parameter not inserted"
				)
			);
		}
		$query = $_GET["query"];
		$latitude = "";
		$longitude = "";
		$where = "";

		$latandlon = false;

		if(isset($_GET["latitude"]) && isset($_GET["longitude"])) {
			$latandlon = true;

			$latitude = $_GET["latitude"];
			$longitude = $_GET["longitude"];
		} else {
			$where = $_GET["where"];
		}

		$this->api->setQuery($query);

		$venues = array();

		if($latandlon) {
			$this->api->setLatitude($latitude);
			$this->api->setLongitude($longitude);
			$venues = $this->api->searchCoordinates();
		} else {
			$this->api->setPlace($where);
			$venues = $this->api->searchPlace();
		}

		// save $venues in database
		$this->thisDatabase->saveVenues($venues);

		$venuesJsonData = Venue::toArray($venues);

		$response->setStatus(
			array(
				"status" => "Ok",
				"message" => ""
			)
		);
		$response->setData($venuesJsonData);

		echo $response->getJsonData();
	}

	/*
	endpoint: /places/addDescription
	params: {
		session_id
		place_external_id
		description
	}
	this endpoint should receive the parameters via POST
	*/
	public function addDescription() {
		$response = new Response();

		if(!isset($_POST["session_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "User isn't authenticated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["place_external_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No place associated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["description"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No description given"
				)
			);
			echo $response->getJsonData();
			return;
		}

		$sessionId = $_POST["session_id"];
		$externalId = $_POST["place_external_id"];
		$description = $_POST["description"];

		if($this->thisDatabase->hasPermission($sessionId)) {
			$this->thisDatabase->addDescription($externalId, $description);
			$response->setStatus(
				array(
					"status" => "Ok",
					"message" => "Description added"
				)
			);
		} else {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No admin permissions"
				)
			);
		}

		echo $response->getJsonData();
	}

	/*
	endpoint: /places/editDescription
	params: {
		session_id
		description_id
		description
	}
	this endpoint should receive the parameters via POST
	*/
	public function editDescription() {
		$response = new Response();

		if(!isset($_POST["session_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "User isn't authenticated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["description_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No description id associated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["description"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No description given"
				)
			);
			echo $response->getJsonData();
			return;
		}

		$sessionId = $_POST["session_id"];
		$descriptionId = $_POST["description_id"];
		$description = $_POST["description"];

		if($this->thisDatabase->hasPermission($sessionId)) {
			$this->thisDatabase->editDescription($descriptionId, $description);
			$response->setStatus(
				array(
					"status" => "Ok",
					"message" => "Description edited"
				)
			);
		} else {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No admin permissions"
				)
			);
		}

		echo $response->getJsonData();
	}

	/*
	endpoint: /places/comment
	params: {
		session_id
		description_id
		comment
	}
	this endpoint should receive the parameters via POST
	*/

	public function comment() {
		$response = new Response();

		if(!isset($_POST["session_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "User isn't authenticated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["description_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No description id associated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		if(!isset($_POST["comment"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No comment given"
				)
			);
			echo $response->getJsonData();
			return;
		}

		$sessionId = $_POST["session_id"];
		$descriptionId = $_POST["description_id"];
		$comment = $_POST["comment"];

		$userObj = $this->userDatabase->getUserFromSession($sessionId);

		if(is_null($userObj)) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "Session not valid"
				)
			);
		}

		$userId = $userObj->getId();
		$this->thisDatabase->addComment($userId, $descriptionId, $comment);

		$response->setStatus(
			array(
				"status" => "Ok",
				"message" => "Comment placed"
			)
		);

		echo $response->getJsonData();
	}

}

?>