<?php
    require("../templates/header.php");
    $Parsedown = new Erusev\Parsedown();
?>

<?php

$postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");
$allPosts = $postObj->getAllPosts();

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

<?php
  require("../templates/sidebar.php");
  require("../templates/footer.php");
?>