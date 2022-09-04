<?php
    require("../templates/header.php");
    $Parsedown = new Erusev\Parsedown();
?>

<?php
      if (!isset($_GET["query"]) && !isset($_GET["tag"])) {
          $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Query or tag need to be set."];
          redirect("/");
      }

      if (isset($_GET["query"]) && isset($_GET["tag"])) {
          $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Both query and tag can't be set at the same time."];
          redirect("/");
      }

      if (isset($_GET["query"])) {
          $query = strtolower((string) htmlspecialchars($_GET["query"]));
      } else {
          $query = false;
      }

      if (isset($_GET["tag"])) {
          $tag = strtolower((string) htmlspecialchars($_GET["tag"]));
      } else {
          $tag = false;
      }
    ?>

<div class="articles">

<?php
      $postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");
    $allPosts = $postObj->getAllPosts();

    usort(
        $allPosts,
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

    $resultsExist = false;

    if ($query) {
        $searchParam = $query;
    } else {
        $searchParam = $tag;
    };
    ?>

<h1 class="pageHeading pageTitleHeader">Results for: "<?= $searchParam ?>"</h1>

<?php foreach ($allPosts as $postId => $post): ?>
  <?php
        $metaData = $post->metadata;
    $title = $post->title;
    $author = $post->metadata->author;
    $markdown = $postObj->getIngress($post->content);
    $metaDataToSearch = "";
    $slug = $post->slug;
    foreach ($metaData as $k => $v) {
        $metaDataToSearch .= $v;
    }

    ?>
  <?php if (matchFunction($query, $tag, $markdown, $metaData, $metaDataToSearch)): ?>
    <?php $resultsExist = true; ?>

    <?php
        $username = getUsername();
      if ($username && $author && $username === $author) {
          require("../templates/crudLinks.php");
      }
      ?>

    <article>
      <h1><a href="/<?= $post->slug ?>"><?= $post->title ?></a></h1>

      <?php
          require("../templates/metadata.php");
      ?>

      <?= $Parsedown->text($markdown) ?>

      <a href="/<?= $slug ?>">Read more</a>
    </article>
  <?php endif; ?>
<?php endforeach; ?>

<?php if (!$resultsExist): ?>
  <p>Nothing found</p>
<?php endif; ?>

</div>

<?php
require("../templates/sidebar.php");
require("../templates/footer.php");
?>