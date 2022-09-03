<?php

use Xolof\Router as Router;

$router = new Router;
$router->get();

$routes = [
  "/"                 => "../view/start.php",
  "/contact"          => "../view/contact.php",
  "/blog"             => "../view/blog.php",
  "/login"            => "../view/login.php",
  "/login-process"    => "../protected/login/process.php",
  "/logout-process"   => "../protected/logout/process.php",
  "/create"           => "../view/create.php",
  "/update"           => "../view/update.php",
  "/delete"           => "../view/delete.php",
  "/search"           => "../view/searchresults.php",
  "/contact-send"     => "../protected/contact-send.php",
  "/add-comment"      => "../protected/comment-process.php",
  "/create-process"   => "../protected/create-process.php",
  "/update-process"   => "../protected/update-process.php",
  "/delete-process"   => "../protected/delete-process.php",
  "/api/save-image"   => "../protected/save-image.php",
  "/post"             => "../view/post.php",
  "/404"              => "../view/404.php"
];

$exploded = explode("/", $request);

if (array_key_exists(3, $exploded)) {
  redirect("/404");
}

if (array_key_exists($request, $routes)) {
    require($routes[$request]);
} else {
  require($routes["/post"]);
}
