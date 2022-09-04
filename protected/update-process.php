<?php

if(!user_is_logged_in()) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to  do that"];
  redirect("/");
}

$postId = htmlspecialchars(trim($_POST["postId"]));
$title = htmlspecialchars(trim($_POST["title"]));
$tags = htmlspecialchars(trim($_POST["tags"]));
$content = trim($_POST["content"]);

if (!is_numeric($postId)) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
  redirect("/");
}

$post = getPost($postId);

if ($post) {
  isUsersFile($postId);

  if (gettype($title) != "string") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Title must be a string"];
    redirect("/create");
  }
  
  if (!gettype($tags) === "string" || !preg_match("/^[a-zA-Z0-9 ]*$/", $tags)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "tags can only contain alphanumeric characters and spaces"];
    redirect("/update?id=$postId");
    exit;
  }
  
  if (gettype($content) != "string") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content must be a string"];
    redirect("/update?id=$postId");
    exit;
  }
  
  if ($content === "") {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Content can not be empty"];
    redirect("/create");
    exit;
  }
  
  $currentDate = new DateTime();
  $dateStr = $currentDate->format("Y-m-d H:i");
  
  $posts = getAllPosts();

  if (titleAlreadyExists($posts, $title, $postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "That title is already used by another post"];
    redirect("/update?id=$postId");
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
    redirect("/update?id=$postId");
    exit;
  }
  
  $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Post with id: $postId was updated"];
  redirect("/$updatedPost->slug");  
} else {
  redirect("/blog");
}
