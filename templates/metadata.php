<ul class='metaData'>
  <?php foreach($metaData as $k => $v): ?>
    <?php if ($v): ?>
      <li>
        <?php if ($k === "tags"): ?>
          <?php
            $metaTags = (explode(" ", $v));
            ?>
          <?= ucwords($k) ?>:
          <?php foreach ($metaTags as $metaTag): ?>
            <a href="/search?tag=<?= $metaTag ?>"><?= $metaTag ?></a>
          <?php endforeach; ?>
        <?php else: ?>
          <?= ucwords($k) ?>: <?= ucwords($v) ?>
        <?php endif; ?>
        </li>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>