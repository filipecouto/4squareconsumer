<?php

require_once '/php/external/ExternalApi.php';
require_once '/php/config/FourSquare.php';
require_once '/php/models/Venue.php';

class FourSquareApi extends ExternalApi {

	private $FourSquareConfig;
	private $query;
	private $place;
	private $longitude;
	private $latitude;
	private $baseQueryString;
	private $baseUrl = "https://api.foursquare.com/v2";
	private $endpoints = array(
		"venues_search" => "/venues/search"
	);

	function __construct() {
		$this->FourSquareConfig = new FourSquare();

		$this->baseQueryString = "?client_id=" . $this->FourSquareConfig->getClientId() . "&client_secret=" . $this->FourSquareConfig->getClientSecret() . "&v=" . $this->FourSquareConfig->getVersion() . "&m=" . $this->FourSquareConfig->getMode();
	}

	public function searchPlace() {
		$url = $this->baseUrl . $this->endpoints["venues_search"] . $this->baseQueryString . "&near=" . $this->getPlace() . "&query=" . $this->getQuery();

		$FourSquareContents = file_get_contents($url);

		return $this->parseVenuesSearch($FourSquareContents);
	}

	public function searchCoordinates() {
		$url = $this->baseUrl . $this->endpoints["venues_search"] . $this->baseQueryString . "&ll=" . $this->getLatitude() . "," . $this->getLongitude() . "&query=" . $this->getQuery();

		$FourSquareContents = file_get_contents($url);

		return $this->parseVenuesSearch($FourSquareContents);
	}


	/*
	expected format:
	[response]
		[venues]
			[0]
			...
			[n]
	*/
	private function parseVenuesSearch($results) {
		$decodedResults = json_decode($results);
		$foundVenues = array();

		$responseObj = isset($decodedResults->response) ? $decodedResults->response : null;

		if(!is_null($responseObj)) {
			$venuesObj = isset($responseObj->venues) ? $responseObj->venues : null;

			if(!is_null($venuesObj)) {
				foreach ($venuesObj as $venueKey => $venueValue) {
					$newVenue = new Venue();
					$newVenue->setExternalId($venueValue->id);
					$newVenue->setName($venueValue->name);
					$newLocation = isset($venueValue->location->address) ? $venueValue->location->address : null;
					$newVenue->setLocation($newLocation);
					$newCity = isset($venueValue->location->city) ? $venueValue->location->city : null;
					$newVenue->setCity($newCity);
					$newCountry = isset($venueValue->location->country) ? $venueValue->location->country : null;
					$newVenue->setCountry($newCountry);

					$foundVenues[] = $newVenue;
				}
			}
		}

		return $foundVenues;
	}

	public function setLatitude($latitude) {
		$this->latitude = urlencode($latitude);
	}

	public function setLongitude($longitude) {
		$this->longitude = urlencode($longitude);
	}

	public function setPlace($place) {
		$this->place = urlencode($place);
	}

	public function setQuery($query) {
		$this->query = urlencode($query);
	}

	public function getLatitude() {
		return $this->latitude;
	}

	public function getLongitude() {
		return $this->longitude;
	}

	public function getPlace() {
		return $this->place;
	}

	public function getQuery() {
		return $this->query;
	}
}

?>