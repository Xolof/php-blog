<?php
  if (count($_GET)) {
    $previousGetQueries = "";
    foreach($_GET as $key => $value) {
      if ($key != "theme") {
        $previousGetQueries .= htmlspecialchars($key) . "=" . htmlspecialchars($value) . "&";
      } 
    }
  }
?>

<a
  class="themeToggler"
  href="?<?= isset($previousGetQueries) ? $previousGetQueries : null ?>theme=<?= $_SESSION["theme"] === "dark" ? "light" : "dark" ?>"
  title="<?= $_SESSION["theme"] === "dark" ? "Switch to light theme" : "Switch to dark theme" ?>"  
>
  <div
    class="<?= $_SESSION["theme"] === "dark" ? "moon" : "sun" ?>"
  >
</div>
</a>