<?php

$host = $_SERVER['HTTP_HOST'];

if (user_is_logged_in()) {
  $theme = $_SESSION["theme"];
  unset($_SESSION["username"]);
  session_destroy();
  session_start();
  $_SESSION["theme"] = $theme;
  $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "You have been logged out"];
  redirect("/");
}

redirect("/");
exit;