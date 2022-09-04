<?php

/**
 * Honeypot for bots.
 */
if ($_POST["website"] !== "") {
    echo "exiting";
    exit;
}

if (!isset($_POST["submit"])) {
    exit;
}

$name = htmlspecialchars($_POST["name"]);
$comment = htmlspecialchars($_POST["comment"]);
$postId = htmlspecialchars($_POST["postId"]);
$postSlug = htmlspecialchars($_POST["postSlug"]);

if (!$name) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Name can't be empty."];
    redirect("/blog");
}

if (!$comment) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Comment can't be empty."];
    redirect("/blog");
}

if (!isset($postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "PostID can't be empty."];
    redirect("/blog");
}

if (!isset($postSlug)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postSlug can't be empty."];
    redirect("/blog");
}

$postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");

if ($postObj->addComment($postId, $name, $comment)) {
    $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Comment added."];
} else {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Could not add comment."];
};

redirect("/$postSlug#comments");
