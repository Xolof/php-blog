<section class="commentsSection">
  <?php if (isset($post->comments) && count($post->comments)): ?>
    <?php
      $comments = $post->comments;
      usort($comments,
      function ($a, $b) {
        if ($a->date < $b->date) {
          return 1;
        }
  
        if ($a->date > $b->date) {
          return -1;
        }
  
        return 0;
      }
    );
    ?>
    <?php foreach($comments as $commentObj): ?>
      <div class="comment">
        <h4 class="commentName">
          <?= $commentObj->name ?>
          <span class="commentDate"><?= $commentObj->date ?></span>
        </h4>
        <?= $Parsedown->text($commentObj->comment) ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>There are not yet any comments.</p>
  <?php endif; ?>
</section>
