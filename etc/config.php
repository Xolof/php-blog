<?php

spl_autoload_register(function ($class) {
  $parts = explode('\\', $class);
  include dirname(__DIR__) . "/class/" . implode($parts, DIRECTORY_SEPARATOR) . '.php';
});

session_start();
if (!isset($_SESSION["theme"])) {
  $_SESSION["theme"] = "light";
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 
$viewDir = "view/";

$pageTitle = "Fish Site";

$host = $_SERVER['HTTP_HOST'];

$Parsedown = new Erusev\Parsedown();

