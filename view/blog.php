<?php
  require("../templates/header.php");
  $Parsedown = new Erusev\Parsedown();
?>

<div class="articles">

<h1 class="pageTitleHeader">Blog</h1>

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
?>

<?php if (user_is_logged_in()) { ?>
  <a href="/create" class="crudLink create">Create &#10133;</a>
<?php }; ?>

<?php foreach ($posts as $post): ?>
  <?php
    $metaData = $post->metadata;
    $username = getUsername();
    $author = $post->metadata->author;
    $markdown = $postObj->getIngress($post->content);
  ?>
  <article>
    <h1><a href="/<?= $post->slug ?>"><?= $post->title ?></a></h1>
    <?php
      if ($username && $author && $username === $author) {
        require("../templates/crudLinks.php");
      }
    ?>
    <?php
      require("../templates/metadata.php");
    ?>
    <?= $Parsedown->text($markdown) ?>
    <a href="/<?= $post->slug ?>">Read more</a>
  </article>
<?php endforeach; ?>

<?php
if (count($posts) < 1) {
  echo "<p>There are currently no posts.</p>";
}
?>

</div>

<?php
  require("../templates/sidebar.php");
  require("../templates/footer.php");
?>