<?php

namespace Xolof;

class Post
{
  protected $id = false;
  protected $json = false;
  protected $postsFile;

  public function __construct ($postsFile)
  {
    $this->postsFile = $postsFile;
  }

  public function getAllPosts ()
  {
    if (file_exists($this->postsFile)) {
      $handle = fopen($this->postsFile, "rb");
      $posts = fread($handle, filesize($this->postsFile));
      fclose($handle);
      return (array) json_decode($posts);
    }
    return [];
  }
  
  public function findPostBySlug ($slug)
  {
    $allPosts = $this->getAllPosts();

    foreach ($allPosts as $p) {
      if ($p->slug === $slug) {
        $post = $p;
        $this->id = $post->id;
        $this->json = $post;
      }
    }
  }

  public function getId()
  {
    return $this->id;
  }

  public function getJson()
  {
    return $this->json;
  }
}