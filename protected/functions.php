<?php

function user_is_logged_in() {
  return isset($_SESSION["username"]);
}

function getUsername() {
  if (isset($_SESSION["username"])) {
    return $_SESSION["username"];
  };
  return false;
}

function getUsersFiles ($user) {
  $users = getUsers();
  $match = array_filter($users,
  function ($userObj) use ($user) {
    return $userObj->username === $user;
  });
  return $match[0]->posts;
}

function getUsers () {
  $usersFile = dirname(__DIR__) . "/protected/users/users.json";

  $handle = fopen($usersFile, "rb");
  $users = fread($handle, filesize($usersFile));
  fclose($handle);

  return (array) json_decode($users);
}

function getAuthor($fileId) {
  $users = getUsers();
  $match = array_filter($users,
  function ($userObj) use ($fileId) {
    return in_array($fileId, (array) $userObj->posts);
  });
  if (count($match) > 0) {
    return $match[array_key_first($match)]->username;
  }
  return false;
}

function isUsersFile ($postId) {
  $host = $_SERVER['HTTP_HOST'];

  if (!in_array($postId, getUsersFiles(getUsername()))) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You are not allowed to edit that file"];
    redirect("/");
  };
}

function redirect($location) {
  $host = $_SERVER['HTTP_HOST'];
  header("Location: http://$host$location");
  exit;
};

function saveUsers($usersArr) {
  $usersFile = dirname(__DIR__) . "/protected/users/users.json";
  $handle = fopen($usersFile, "wb");
  fwrite($handle, json_encode($usersArr));
  fclose($handle);
}

function matchFunction ($query = false, $tag = false, $markdown, $metaData, $metaDataToSearch) {
  if ($query) {
    return strrpos(strtolower($markdown), strtolower($query)) !== false ||
      strrpos(strtolower($metaDataToSearch), strtolower($query)) !== false;
  }

  if ($tag) {
    return strrpos(strtolower($metaData->tags), strtolower($tag)) !== false;
  }

  return false;
};