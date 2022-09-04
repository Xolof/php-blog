<?php


  $created = $post->metadata->created;


  $allPreviousPosts = array_filter($allPosts, function ($p) use ($created) {
      return $p->metadata->created < $created;
  }, ARRAY_FILTER_USE_BOTH);



  if (count($allPreviousPosts) > 0) {
      $previousPost = $allPreviousPosts[array_key_first($allPreviousPosts)];
  }


  $allFollowingPosts = array_filter($allPosts, function ($p) use ($created) {
      return $p->metadata->created > $created;
  }, ARRAY_FILTER_USE_BOTH);


  if (count($allFollowingPosts) > 0) {
      $nextPost = $allFollowingPosts[array_key_last($allFollowingPosts)];
  }


  ?>

<div class="nextPrevLinks">
  <?php if (isset($previousPost)): ?>
    <a href="/<?= $previousPost->slug ?>" class="previous">Previous: <?= $previousPost->title ?></a>
  <?php endif; ?>

  <?php if (isset($nextPost)): ?>
    <a href="/<?= $nextPost->slug ?>" class="next">Next: <?= $nextPost->title ?></a>
  <?php endif; ?>
</div>