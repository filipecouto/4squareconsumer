<?php

require_once '/php/models/Model.php';

class Response extends Model {

	private $status;
	private $data;

	function __construct() {
		$this->setStatus(array());
		$this->setData(array());
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getData() {
		return $this->data;
	}

	public function getJsonData() {
		return json_encode(get_object_vars($this));
	}
}


?>