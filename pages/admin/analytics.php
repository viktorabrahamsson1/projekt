<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireLogin();
requireRoles(["admin"]);

$content = <<<HTML
<div>
  <p>ANALYTICS</p>
</div>
HTML;

require "../../includes/layout.php"
  ?>