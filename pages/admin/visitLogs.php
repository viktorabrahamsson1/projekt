<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);


$content = <<<HTML
<p>VISIT LOGS</p>
HTML;


require "../../includes/layout.php"
  ?>