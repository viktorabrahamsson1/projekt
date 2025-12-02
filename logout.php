<?php
require_once "includes/session.php";

$_SESSION = [];
session_destroy();

if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 3600, '/');
}

header("Location: /index.php");
exit;
