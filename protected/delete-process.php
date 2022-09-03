<?php

if(!user_is_logged_in()) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to do that."];
  redirect("http://$host");
}

$postId = htmlspecialchars($_POST["postId"]);

if (!is_numeric($postId)) {
  $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
  redirect("http://$host/");
}

$post = getPost($postId);

if ($post) {
  isUsersFile($postId);

  $usersArr = getUsers();
  
  foreach($usersArr as $user) {
    if ($user->username === getUsername()) {
      $postsArr = [];
      foreach($user->posts as $post) {
        if ($post != intval($postId)) {
          $postsArr[] = $post;
        }
      }
      $user->posts = $postsArr;
    }
  }

  $posts = getAllPosts();
  unset($posts[$postId]);
  
  try {
    /**
     * TODO: break it out to two try, catches
     */
    saveUsers($usersArr);
    savePosts($posts);
  } catch (\Exception $e) {
    // $logger->log($e);
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Post with id could not be deleted"];
    redirect("http://$host/update?id=$postId");
    exit;
  }
  
  $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Post with id: $postId was deleted"];
  redirect("http://$host/blog");
} else {
  redirect("http://$host/blog");
}
