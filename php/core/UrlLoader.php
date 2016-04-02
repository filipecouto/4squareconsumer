<?php

/*

This class is meant to parse the URL and call the respective service

*/

class UrlLoader {

	private $url_information;
	private $current_controller = null;

	function __construct($url) {
		$this->url_information = $this->loadUrl($url);

		$this->process();
	}

	private function loadUrl($url) {
		$parameters = explode("/", $url);
		return $parameters;
	}

	private function setController($current_controller) {
		$this->current_controller = $current_controller;
	}

	public function getController() {
		return $this->current_controller;
	}

	private function process() {
		if(!is_array($this->url_information)) {
			return;
		}

		$arraySize = count($this->url_information);

		if($arraySize > 1) {
			$controller = $this->url_information[0];
			$method = $this->url_information[1];

			$controller = strtolower($controller);
			$controller = ucfirst($controller);
			$controller = $controller . "Controller";

			$fileName = "php/controllers/" . $controller . ".php";

			if(!file_exists($fileName)) {
				return;
			}

			require $fileName;

			$object = new $controller;

			$this->setController($object);

			if($arraySize > 2) { // I've got extra parameters
				$parameterList = array();
				
				for($i = 2; $i < $arraySize; $i++) {
					$parameterList[] = $this->url_information[$i];
				}

				if (method_exists($object, $method) && is_callable(array($object, $method))) {
					call_user_func_array(array($object, $method), $parameterList);
				}
			} else {
				if (method_exists($object, $method) && is_callable(array($object, $method))) {
					$object->$method();
				}
			}
		}
	}
}

?>