<?php

use Xolof\Router as Router;
use Xolof\Post as Post;

$postsFile = dirname(__DIR__) . "/content/posts/posts.json";

$postObj = new Post($postsFile);

$router = new Router($request, $_SERVER["REQUEST_METHOD"], $postObj);

$router->validate();

$router->get("/",               "../view/start.php");
$router->get("/contact",        "../view/contact.php");
$router->get("/blog",           "../view/blog.php");
$router->get("/login",          "../view/login.php");
$router->get("/create",         "../view/create.php");
$router->get("/update",         "../view/update.php");
$router->get("/delete",         "../view/delete.php");
$router->get("/search",         "../view/searchresults.php");
$router->get("/404",            "../view/404.php");
$router->get("/logout-process", "../protected/logout/process.php");

$router->post("/login-process",   "../protected/login/process.php");
$router->post("/contact-send",    "../protected/contact-send.php");
$router->post("/add-comment",     "../protected/comment-process.php");
$router->post("/create-process",  "../protected/create-process.php");
$router->post("/update-process",  "../protected/update-process.php");
$router->post("/delete-process",  "../protected/delete-process.php");
$router->post("/api/save-image",  "../protected/save-image.php");

$post = $router->getSpecialPage();
if ($post) {
  $postId = $post->id;
}

$requireFile = $router->getRequireFile();
if ($requireFile) {
  require($requireFile);
  exit;
}

$redirectPath = $router->getRedirectPath();
if ($redirectPath) {
  redirect($redirectPath);  
  exit;
}

redirect("/404");
exit;