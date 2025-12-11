<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
require_once "../../includes/db.php";
requireRoles(["admin"]);

$user_id = $_GET["id"] ?? null;

if ($user_id) {
  $mysqli->query("DELETE FROM user WHERE user.user_id = $user_id");
}

header("Location: allUsers.php");

?>