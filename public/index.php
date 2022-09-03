<?php

require("../etc/config.php");

require("../etc/functions.php");

if (strrpos($request, "api") == false) {
  require("../templates/header.php");
}

require("../etc/router.php");

require("../templates/sidebar.php");

require("../templates/footer.php");
