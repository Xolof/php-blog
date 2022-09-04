<?php
require("../templates/header.php");
?>

<?php
if (!user_is_logged_in()) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You must be logged in to create a post"];
    redirect("/");
}
?>

<div class="articles">
	<article>

    <h1 class="pageTitleHeader">Create</h1>

    <form action="/create-process" method="post" class="createForm">
      <label for="title">Title</label>
      <input type="text" name="title" required>
      <label for="tags">Tags separated by space</label>
      <input type="text" name="tags" class="create">
      <label for="content">Content in markdown</label>
      <textarea name="content" rows="10" cols="70" required></textarea>
      <input type="submit" name="create" id="create" value="Create">
    </form>

    <?php
      require("../templates/gallery.php");
    ?>

  </article>
</div>

<?php
require("../templates/sidebar.php");
require("../templates/footer.php");
?>