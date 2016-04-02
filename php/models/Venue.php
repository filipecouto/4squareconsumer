<?php

require_once '/php/models/Model.php';

class Venue extends Model {
	private $id;
	private $externalId;
	private $name;
	private $location;
	private $city;
	private $country;

	function __construct() {

	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setExternalId($externalId) {
		$this->externalId = $externalId;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setLocation($location) {
		$this->location = $location;
	}

	public function setCity($city) {
		$this->city = $city;
	}

	public function setCountry($country) {
		$this->country = $country;
	}

	public function getId() {
		return $this->id;
	}

	public function getExternalId() {
		return $this->externalId;
	}

	public function getName() {
		return $this->name;
	}

	public function getLocation() {
		return $this->location;
	}

	public function getCity() {
		return $this->city;
	}

	public function getCountry() {
		return $this->country;
	}

	private function getObjectData() {
		return get_object_vars($this);
	}

	public static function getJsonData($venueObj) {
		if($venueObj instanceof Venue) {
			return json_encode($venueObj->getObjectData());
		}
		if(is_array($venueObj)) {
			$newArray = array();

			foreach($venueObj as $key => $value) {
				$newArray[] = $value->getObjectData();
			}

			return json_encode($newArray);
		}
	}

	public static function toArray($venueObj) {
		if($venueObj instanceof Venue) {
			return $venueObj->getObjectData();
		}
		if(is_array($venueObj)) {
			$newArray = array();

			foreach($venueObj as $key => $value) {
				$newArray[] = $value->getObjectData();
			}

			return $newArray;
		}	
	}
}

?>