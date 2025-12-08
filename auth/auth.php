<?php


function requireLogin()
{
  if (!$_SESSION["logged_in"]) {
    header("Location: /index.php");
    exit;
  }
}
function requireRoles($roles = [])
{

  if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], $roles)) {
    header("Location: /main.php");
    exit;
  }
}
?>