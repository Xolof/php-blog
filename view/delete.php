<?php
    require("../templates/header.php");
?>

<div class="articles">
	<article>

    <h1 class="pageTitleHeader">Delete</h1>

    <p>Do you really want to delete this post?</p>

    <?php

    if(!user_is_logged_in()) {
      $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to remove a post"];
      redirect("/");
    }

    $postId = $_GET["id"];

    if (!is_numeric($postId)) {
      $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "postId must be numeric"];
      redirect("/blog");
    }

    $post = getPost($postId);

    ?>

    <?php if ($post): ?>
      <?php
        isUsersFile($postId);
        $markdown = $post->content;
        $metaData = $post->metadata;  
      ?>
      <form action="/delete-process" method="post" class="deleteForm">
        <input type="hidden" name="postId" value="<?= $postId ?>">
        <label for="title">Title</label>
        <input type="text" name="title" value="<?= $post->title ?>" readonly>
        <label for="tags">Tags</label>
        <input type="text" name="tags" value="<?= $metaData->tags ?? null ?>" readonly class="delete">
        <label for="content">Content</label>
        <textarea name="content" rows="10" cols="70" readonly><?= $markdown ?></textarea>
        <input type="submit" name="delete" id="delete" value="Delete">
      </form>
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