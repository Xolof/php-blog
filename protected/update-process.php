<?php

if(!user_is_logged_in()) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to  do that"];
  redirect("http://$host");
}

$postId = htmlspecialchars(trim($_POST["postId"]));
$title = htmlspecialchars(trim($_POST["title"]));
$tags = htmlspecialchars(trim($_POST["tags"]));
$content = trim($_POST["content"]);

if (!is_numeric($postId)) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
  redirect("http://$host");
}

$post = getPost($postId);

if ($post) {
  isUsersFile($postId);

  if (gettype($title) != "string") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Title must be a string"];
    redirect("http://$host/create");
  }
  
  if (!gettype($tags) === "string" || !preg_match("/^[a-zA-Z0-9 ]*$/", $tags)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "tags can only contain alphanumeric characters and spaces"];
    redirect("http://$host/update?id=$postId");
    exit;
  }
  
  if (gettype($content) != "string") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content must be a string"];
    redirect("http://$host/update?id=$postId");
    exit;
  }
  
  if ($content === "") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content can not be empty"];
    redirect("http://$host/create");
    exit;
  }
  
  $currentDate = new DateTime();
  $dateStr = $currentDate->format("Y-m-d H:i");
  
  $posts = getAllPosts();

  if (titleAlreadyExists($posts, $title, $postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "That title is already used by another post"];
    redirect("http://$host/update?id=$postId");
  }

  $updatedPost = $posts[$postId];
  $updatedPost->metadata->updated = $dateStr;
  $updatedPost->metadata->tags = $tags;
  $updatedPost->title = $title;
  $updatedPost->slug = slugify($title);
  $updatedPost->content = $content;
  
  try {
    savePosts($posts);
  } catch (\Exception $e) {
    // $logger->log($e);
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Could not save the data"];
    redirect("http://$host/update?id=$postId");
    exit;
  }
  
  $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Post with id: $postId was updated"];
  redirect("http://$host/$updatedPost->slug");  
} else {
  redirect("http://$host/blog");
}
