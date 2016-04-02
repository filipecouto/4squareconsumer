<?php

require_once '/php/database/Database.php';
require_once '/php/database/MySQLDatabase.php';

class PlacesDatabase extends Database {

	private $databaseImplementation;

	function __construct() {
		$this->databaseImplementation = new MySQLDatabase();
	}

	public function saveVenues($venues = array()) {
		if(empty($venues) || !is_array($venues)) {
			return;
		}

		$connection = $this->databaseImplementation->retrieveConnection();

		$baseQuery = "REPLACE INTO venues(external_id, name, location, city, country) VALUES ";

		$valuesQuery = "";
		$totalRecords = count($venues);
		for($i = 0; $i < $totalRecords; $i++) {
			$valuesQuery .= "(:external_id_$i, :name_$i, :location_$i, :city_$i, :country_$i)";
			if(($i+1) < $totalRecords) {
				$valuesQuery .= ", ";
			}
		}

		$insertQuery = $baseQuery . $valuesQuery;
		$preparedStatement = $connection->prepare($insertQuery);

		$i = 0;
		foreach($venues as $venueKey => $venueValue) {
			$preparedStatement->bindValue(':external_id_' . $i, $venueValue->getExternalId(), PDO::PARAM_INT);
			$preparedStatement->bindValue(':name_' . $i, $venueValue->getName(), PDO::PARAM_STR);
			$preparedStatement->bindValue(':location_' . $i, $venueValue->getLocation(), PDO::PARAM_STR);
			$preparedStatement->bindValue(':city_' . $i, $venueValue->getCity(), PDO::PARAM_STR);
			$preparedStatement->bindValue(':country_' . $i, $venueValue->getCountry(), PDO::PARAM_STR);

			$i++;
		}

		$preparedStatement->execute();
	}

	public function hasPermission($sessionId) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$query = "SELECT * FROM sessions INNER JOIN users ON users.id = sessions.user_id AND users.user_type = 1 WHERE sessions.token = :token AND sessions.validity = 1 LIMIT 1;";

		$preparedStatement = $connection->prepare($query);

		$preparedStatement->bindValue(':token', $sessionId, PDO::PARAM_STR);

		$preparedStatement->execute();

		return $preparedStatement->rowCount() > 0;
	}

	public function addDescription($externalId, $description) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$insertQuery = "INSERT INTO venues_descriptions(external_id, description) VALUES (:external_id, :description);";

		$preparedStatement = $connection->prepare($insertQuery);
		$preparedStatement->bindValue(':external_id', $externalId, PDO::PARAM_STR);
		$preparedStatement->bindValue(':description', $description, PDO::PARAM_STR);

		$preparedStatement->execute();

		return $connection->lastInsertId();
	}

	public function editDescription($id, $description) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$editQuery = "UPDATE venues_descriptions SET description = :description WHERE id = :id;";

		$preparedStatement = $connection->prepare($editQuery);
		$preparedStatement->bindValue(':id', $id, PDO::PARAM_INT);
		$preparedStatement->bindValue(':description', $description, PDO::PARAM_STR);

		$preparedStatement->execute();
	}

	public function addComment($userId, $descriptionId, $comment) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$insertQuery = "INSERT INTO venue_description_comments(venue_description_id, user_id, comment) VALUES (:description_id, :user_id, :comment);";

		$preparedStatement = $connection->prepare($insertQuery);
		$preparedStatement->bindValue(':description_id', $descriptionId, PDO::PARAM_INT);
		$preparedStatement->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$preparedStatement->bindValue(':comment', $comment, PDO::PARAM_STR);

		$preparedStatement->execute();

		return $connection->lastInsertId();
	}

	public function isCommentOwner($commentId, $userId) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$query = "SELECT * FROM venue_description_comments, users WHERE venue_description_comments.id = :comment_id AND (venue_description_comments.user_id = :user_id OR (users.user_type = 1 AND users.id = :user_id));";

		$preparedStatement = $connection->prepare($query);
		$preparedStatement->bindValue(':user_id', $userId, PDO::PARAM_INT);
		$preparedStatement->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
		
		$preparedStatement->execute();

		return $preparedStatement->rowCount() > 0;
	}

	public function hideComment($commentId) {
		$connection = $this->databaseImplementation->retrieveConnection();

		$updateQuery = "UPDATE venue_description_comments SET visible = 0 WHERE id = :comment_id;";

		$preparedStatement = $connection->prepare($updateQuery);
		$preparedStatement->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
		$preparedStatement->execute();
	}
}

?>