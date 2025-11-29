<?php
require_once "../includes/session.php";
require_once "../includes/users.php";

$username = $_POST["username"];
$password = $_POST["password"];

$found_user = null;

foreach ($users as $user) {
  if ($user["username"] === $username && $user["password"] === $password) {
    $found_user = $user;
    break;
  }
}



if (!$found_user) {
  header("Location: ../index.php?error=1");
  exit;
}

$_SESSION["username"] = $found_user["username"];
$_SESSION["logged_in"] = true;
header("Location: ../main.php")

  ?>