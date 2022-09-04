<?php

  $postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");
  $posts = $postObj->getAllPosts();

  usort($posts,
    function ($a, $b) {
      if ($a->metadata->created < $b->metadata->created) {
        return 1;
      }

      if ($a->metadata->created > $b->metadata->created) {
        return -1;
      }

      return 0;
    }
  );

  $posts = array_slice($posts, 0, 5);
?>

<aside class="sidebar">
  <section>
  <h2>Latests posts</h2>
    <?php if ($posts): ?>
      <ul>
        <?php foreach ($posts as $post): ?>
          <li>
            <a href="/<?= $post->slug ?>"><?= $post->title ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>There are not yet any posts.</p>
    <?php endif; ?>
  </section>
  
  <?php
      $tags = $postObj->getAllTags();
  ?>
  <section>
    <h2>Tags</h2>
    <?php if ($tags): ?>
      <ul>
        <?php foreach ($tags as $tag): ?>
            <a href="/search?tag=<?= $tag ?>"><?= $tag ?></a>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>There are not yet any tags.</p>
    <?php endif; ?>
  </section>
  

</aside>