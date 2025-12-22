<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
require_once "../../includes/db.php";
require_once "../../includes/alert.php";
requireRoles(["admin"]);

$user_id = $_GET["id"] ?? null;

if ($user_id) {
  try {
    $mysqli->query("DELETE FROM user WHERE user.user_id = $user_id");
    setAlert("Successfully deleted", "success", "allUsers.php");
  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to delete user", "error", "allUsers.php");
  }
}

?>