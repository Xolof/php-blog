<section class="commentFormSection" id="comments">
  <form action="/add-comment" class="commentForm" method="post">

    <label for="name">Name</label>
    <input type="text" name="name" id="name" required>

    <input type="text" name="website" class="websiteField">

    <input type="number" value="<?= $postId ?>" name="postId" id="postId" hidden required>
    <input type="text" value="<?= $post->slug ?>" name="postSlug" id="postSlug" hidden required>

    <label for="comment">Comment</label>
    <textarea name="comment" rows="7" cols="70" required></textarea>  

    <input type="Submit" value="Add comment" name="submit" id="submit">
  </form>
</section>