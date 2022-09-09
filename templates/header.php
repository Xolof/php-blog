<?php

$envFile = require("../config/env.php");
$siteConfig = require("../config/siteConfig.php");

$pageTitle = $siteConfig["pageTitle"];
$env = $envFile["env"];

if (isset($_SESSION["flash_message"])) {
    $flash = $_SESSION["flash_message"];
    unset($_SESSION["flash_message"]);
    $message = $flash["message"];
    $cssClass = $flash["cssClass"];
    echo "<p class='$cssClass flashMessage' id='flashMessage'>$message</p>";
}

if (isset($_GET["theme"])) {
    $theme = $_GET["theme"];
    if ($theme === "dark") {
        $_SESSION["theme"] = "dark";
    } elseif ($theme === "light") {
        $_SESSION["theme"] = "light";
    }
}

$colorStyles = $_SESSION["theme"] === "dark" ? "darkThemeColors.css" : "lightThemeColors.css";

if ($env === "dev") {
  $styles = "styles.css";
} else if ($env === "prod") {
  $styles = "styles.min.css";
} else {
  echo "You have to set an environment in the config file. Valid values are 'dev' and 'prod'.";
  exit;
}
?>

<!DOCTYPE html>
<html id="top">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= $pageTitle ?></title>
    <?php
      $baseUrl = $_SERVER["HTTP_HOST"];
?>
    <link rel="stylesheet" href="<?php $baseUrl ?>/css/normalize_8.0.1.css" />
    <link rel="stylesheet" href="<?php $baseUrl ?>/css/colors/<?= $colorStyles ?>" />
    <link rel="stylesheet" href="<?php $baseUrl ?>/css/styles.css" />
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
  </head>
  <body>
    <div class="container">
      <header>
        <div class="header-inner">
          <h1 class="siteTitleHeader"><a href="/"><?= $pageTitle ?></a></h1>
          <?php require("../templates/themeToggler.php"); ?>
          <nav class="header-nav">
            <ul>
              <li class="homeLinkLi"><a href="/" class="homeLink">
              <?php require("../templates/svg/homeIcon.php") ?>
              <li><a href="/blog">Blog</a></li>
              <li><a href="/contact">Contact</a></li>
              <?php if(!user_is_logged_in()): ?>
                <li><a href="/login">Log in</a></li>
              <?php else: ?>
                <li><a href="/logout-process">Log out</a></li>
              <?php endif; ?>
            </ul>
            <form action="/search" class="searchForm">
              <input type="search" name="query" placeholder="Search">
              <?php require("../templates/svg/searchIcon.php") ?>
            </form>
          </nav>
        </div>
      </header>
      <?php $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
      <main <?= $request === '/' ? ' class="homeContainer"' : '' ?>>
        <div class="flexContainer">

