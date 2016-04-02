<?php

class FourSquare {

	private $clientId = "PME1KLGBNHQP5H2DSVSETRICSU4PB02EDNH155CFZFG0KR2O";
	private $clientSecret = "AJDQ4L0GQZLG2JM43TRLZWAGMIGNFVYDAAOTFP44TNACWRTM";
	private $version = "20151219"; // 19th December 2015 version of API
	private $mode = "foursquare"; // mode can be swarm or foursquare

	public function getClientId() {
		return $this->clientId;
	}

	public function getClientSecret() {
		return $this->clientSecret;
	}

	public function getVersion() {
		return $this->version;
	}

	public function getMode() {
		return $this->mode;
	}
}

?>