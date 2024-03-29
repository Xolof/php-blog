<?php

if (!user_is_logged_in()) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to do that."];
    redirect("");
}

$postId = htmlspecialchars($_POST["postId"]);

if (!is_numeric($postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
    redirect("/");
}

$postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");
$post = $postObj->getPost($postId);

if ($post) {
    isUsersFile($postId);

    $usersArr = getUsers();

    foreach ($usersArr as $user) {
        if ($user->username === getUsername()) {
            $postsArr = [];
            foreach ($user->posts as $post) {
                if ($post != intval($postId)) {
                    $postsArr[] = $post;
                }
            }
            $user->posts = $postsArr;
        }
    }

    $posts = $postObj->getAllPosts();
    unset($posts[$postId]);

    try {
        /**
         * TODO: break it out to two try, catches
         */
        saveUsers($usersArr);
        $postObj->savePosts($posts);
    } catch (\Exception $e) {
        // $logger->log($e);
        $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Post with id could not be deleted"];
        redirect("/update?id=$postId");
        exit;
    }

    $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Post with id: $postId was deleted"];
    redirect("/blog");
} else {
    redirect("/blog");
}
