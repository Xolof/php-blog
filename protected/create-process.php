<?php

if(!user_is_logged_in()) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to  do that"];
  redirect("http://$host");
}

$title = htmlspecialchars(trim($_POST["title"]));
$tags = htmlspecialchars(trim($_POST["tags"]));
$content = trim($_POST["content"]);

$postsDir = dirname(__DIR__) . "/content/posts";

if (gettype($title) != "string") {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Title must be a string"];
  redirect("http://$host/create");
}

if (!gettype($tags) === "string" || !preg_match("/^[a-zA-Z0-9 ]*$/", $tags)) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "tags can only contain alphanumeric characters and spaces"];
  redirect("http://$host/create");
}

if (gettype($content) != "string") {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content must be a string"];
  redirect("http://$host/create");
}

if ($title === "") {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Title can not be empty"];
  redirect("http://$host/create");
}

if ($content === "") {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content can not be empty"];
  redirect("http://$host/create");
}

$currentDate = new DateTime();
$dateStr = $currentDate->format("Y-m-d H:i");

$posts = getAllPosts();

$newId = count($posts);

if (titleAlreadyExists($posts, $title, $newId)) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "That title is already used by another post"];
  redirect("http://$host/create");
}

$newPost = new \stdClass();
$newPost->metadata = new \stdClass();
$newPost->metadata->author = getUsername();
$newPost->metadata->created = $dateStr;
$newPost->metadata->tags = $tags;
$newPost->title = $title;
$newPost->slug = slugify($title);
$newPost->content = $content;
$newPost->id = $newId;
$posts[$newId] = $newPost;  

$usersArr = getUsers();
foreach($usersArr as $user) {
  if ($user->username === getUsername()) {
    $user->posts[] = intval($newId);
  }
}

try {
  saveUsers($usersArr);
  savePosts($posts);
} catch (\Exception $e) {
  // $logger->log($e);
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Could not save the data"];
  redirect("http://$host/create");
}

$_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Post with id: $newId was created"];
redirect("http://$host/blog");
