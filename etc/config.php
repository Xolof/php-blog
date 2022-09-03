<?php

session_start();
if (!isset($_SESSION["theme"])) {
  $_SESSION["theme"] = "light";
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 
$viewDir = "view/";

$pageTitle = "Fish Site";

$host = $_SERVER['HTTP_HOST'];

require(dirname(__DIR__) . "/vendor/parsedown-1.7.4/Parsedown.php");
$Parsedown = new Parsedown();
