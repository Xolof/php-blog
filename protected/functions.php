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

function getIngress ($content) {
  $markdown = substr($content, 0, 500);
  $exploded = explode(".", $markdown);
  $lastItem = $exploded[array_key_last($exploded)];

  if (!(preg_match('/.*\)$/', $lastItem) || preg_match('/.*\/a>.*/', $lastItem))) {
    $trimmed = array_slice($exploded, 0, count($exploded) -1);
    $markdown = implode(".", $trimmed) . ".";
  }

  return $markdown;
}

function getAllPosts () {
  $postsFile = dirname(__DIR__) . "/content/posts/posts.json";
  if (file_exists($postsFile)) {
    $handle = fopen($postsFile, "rb");
    $posts = fread($handle, filesize($postsFile));
    fclose($handle);
    return (array) json_decode($posts);
  }
  return [];
}

function getPost ($id) {
  $allPosts = getAllPosts();
  foreach ($allPosts as $post) {
    if (intval($post->id) === intval($id)) {
      return $post;
    }
  }
  return false;
}

function getPostBySlug ($slug) {
  $allPosts = getAllPosts();
  $post = false;
  foreach ($allPosts as $p) {
    if ($p->slug === $slug) {
      $post = $p;
    }
  }
  return $post;
}

function slugify($title) {
  return strtolower(str_replace(" ", "-", $title));
};

function titleAlreadyExists($posts, $title, $postId) {
  foreach($posts as $post) {
    if ($post->title === $title && $post->id != $postId) {
      return true;
    }
  }
  return false;
}

function getAllTags() {
  $posts = getAllPosts();
  $tags = [];
  foreach($posts as $post) {
    $postTags = explode(" ", $post->metadata->tags);
    foreach($postTags as $tag) {
      if (!in_array($tag, $tags)) {
        $tags[] = $tag;
      }
    }
  }
  return $tags;
}

function addComment($postId, $name, $comment) {
  $posts = getAllPosts();

  $commentObj = new \stdClass();

  $currentDate = new DateTime();
  $dateStr = $currentDate->format("Y-m-d H:i");
  $commentObj->date = $dateStr;

  $commentObj->comment = $comment;
  $commentObj->name = $name;

  foreach($posts as $post) {
    if ($post->id == $postId) {
      $post->comments[] = $commentObj;
    }
  }

  try {
    savePosts($posts);
  } catch (\Exception $e) {
    return false;
  }
  return true;
}

function savePosts($posts) {
  $handle = fopen(dirname(__DIR__) . "/content/posts/posts.json", "wb");
  fwrite($handle, json_encode($posts));
  fclose($handle);
}

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