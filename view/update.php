<?php
    require("../templates/header.php");
?>

<div class="articles">
  <article>

  <h1 class="pageTitleHeader">Update</h1>

  <?php
  if(!user_is_logged_in()) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to update a post"];
    redirect("/");
  }

  $postId = $_GET["id"];

  if (!is_numeric($postId)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
    redirect("/blog");
  }

  $postObj = new Xolof\Post(dirname(__DIR__) . "/content/posts/posts.json");
  $post = $postObj->getPost($postId);

  ?>

  <?php if ($post): ?>
    <?php
      isUsersFile($postId);
      $markdown = $post->content;
      $metaData = $post->metadata;  
    ?>
    <form action="/update-process" method="post" class="updateForm">
      <input type="hidden" name="postId" value="<?= $postId ?>">
      <label for="title">Title</label>
      <input type="text" name="title" value="<?= $post->title ?>">
      <label for="tags">Tags separated by space</label>
      <input type="text" name="tags" value="<?= $metaData->tags ?? null ?>" class="update">
      <label for="content">Content in markdown</label>
      <textarea name="content" rows="10" cols="70"><?= $markdown ?></textarea>
      <input type="submit" name="update" id="update" value="Update">
    </form>
    
    <?php
      require("../templates/gallery.php");
    ?>

  <?php else: ?>
    <?php
      redirect("/404");
    ?>
  <?php endif; ?>

  </article>
  </div>

<?php
  require("../templates/sidebar.php");
  require("../templates/footer.php");
?>