<?php

require_once '/php/controllers/Controller.php';
require_once '/php/database/PlacesDatabase.php';
require_once '/php/models/Response.php';
require_once '/php/models/User.php';

class CommentController extends Controller {

	private $thisDatabase;

	function __construct() {
		$this->thisDatabase = new PlacesDatabase();
	}

	/*
	endpoint: /comment/hide
	params: {
		session_id
		comment_id
	}
	*/
	public function hide() {
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

		if(!isset($_POST["comment_id"])) {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No comment id associated"
				)
			);
			echo $response->getJsonData();
			return;
		}

		$commentId = $_POST["comment_id"];
		$userId = $_POST["session_id"];

		$isOwner = $this->thisDatabase->isCommentOwner($commentId, $userId);

		if($isOwner) {
			$this->thisDatabase->hideComment($commentId);

			$response->setStatus(
				array(
					"status" => "Ok",
					"message" => ""
				)
			);
		} else {
			$response->setStatus(
				array(
					"status" => "Error",
					"message" => "No permissions"
				)
			);
		}

		echo $response->getJsonData();
	}
}

?>