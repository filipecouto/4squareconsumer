<?php

if(!isset($_GET["url"])) exit;

$url = $_GET["url"];

require "php/core/UrlLoader.php";

$loader = new UrlLoader($url);

?>
