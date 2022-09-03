<?php

$exploded = explode("/", $request);
if (array_key_exists(1, $exploded)) {
  $slug = $exploded[1];
  $post = getPostBySlug($slug);
  if (!$post) {
    redirect("/404");
  }
  $postId = $post->id;
} else {
  $postId = $_GET["id"];

  if (isset($postId) && !is_numeric($postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
    redirect("http://$host/blog");
  }
  
  $post = getPost($postId);
}

$allPosts = getAllPosts();

usort($allPosts,
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
?>

<?php if ($post): ?>

  <?php
  $markdown = $post->content;
  $metaData = $post->metadata;
  ?>

  <div class="articles">
    <article class="post">
      <h1 class="pageTitleHeader"><?= $post->title ?></h1>
      <?php
        $username = getUsername();
        $author = $post->metadata->author;
        if ($username && $author && $username === $author) {
          require("../templates/crudLinks.php");
        }
      ?>
      <?php
        require("../templates/metadata.php");
      ?>

      <?= $Parsedown->text($markdown) ?>

      <?php
        require("../templates/nextPrevLinks.php");
      ?>

    </article>
    <?php
      require("../templates/commentForm.php");
      require("../templates/comments.php");
    ?>
  </div>

<?php else: ?>
  <?php redirect("/404"); ?>
<?php endif; ?>
