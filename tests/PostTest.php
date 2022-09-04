<?php

declare(strict_types=1);

namespace Xolof;

use PHPUnit\Framework\TestCase;

final class PostTest extends TestCase
{
  public function testTryToGetAllPostsWhenPostsFileDoesntExist (): void
  {
    $postObj = new Post("invalid-file.json");
    $allPosts = $postObj->getAllPosts();

    $this->assertEquals(
      [],
      $allPosts
    );
  }

  public function testGetAllTags (): void
  {
    $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");
    $tags = $postObj->getAllTags();

    $this->assertEquals(
      "array",
      gettype($tags)
    );

    foreach($tags as $tag) {
      $this->assertEquals(
        "string",
        gettype($tag)
      );
    }
  }

  public function testGetExistingPost (): void
  {
    $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");
    $post = $postObj->getPost(0);

    $this->assertEquals(
      "object",
      gettype($post)
    );
  }

  public function testGetNonExistingPost (): void
  {
    $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");
    $post = $postObj->getPost(100);

    $this->assertEquals(
      false,
      $post
    );
  }

  public function testSlugify (): void
  {
    $postObj = new Post(dirname(__DIR__) . "/tests/assets/posts.json");
    $slug = $postObj->slugify("Test title");

    $this->assertEquals(
      "test-title",
      $slug
    );
  }
}