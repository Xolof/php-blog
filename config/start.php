<?php

$envFile = require("../config/env.php");
$siteConfig = require("../config/siteConfig.php");

DEFINE("PAGETITLE", $siteConfig["pageTitle"]);
DEFINE("ENV", $envFile["env"]);

include("../vendor/autoload.php");

session_start();
if (!isset($_SESSION["theme"])) {
    $_SESSION["theme"] = "light";
}

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
