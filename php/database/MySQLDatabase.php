<?php

require_once '/php/database/DatabaseImplementation.php';
require_once '/php/config/MySQL.php';

class MySQLDatabase extends DatabaseImplementation {

	private $configuration;

	function __construct() {
		$this->configuration = new MySQL();
	}

	public function retrieveConnection() {
		$serverName = $this->configuration->getServer();
		$databaseName = $this->configuration->getDatabase();
		$username = $this->configuration->getUsername();
		$password = $this->configuration->getPassword();

		try {
			$connection = new PDO("mysql:host=$serverName;dbname=$databaseName;charset:utf8", $username, $password);
			$connection->exec("set names utf8");
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $connection;
		} catch(PDOException $exception) {
			return null;
		}
	}
}

?>