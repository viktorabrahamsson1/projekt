<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireLogin();
requireRoles(["admin"]);
require_once "../../includes/db.php";
require_once "../../includes/alert.php";

$user_id = $_GET["id"] ?? null;

if ($user_id) {

  if ((int) $user_id === (int) $_SESSION["user_id"]) {
    setAlert("You cannot delete yourself", "error", "allUsers.php");
  }

  try {
    $mysqli->query("DELETE FROM user WHERE user.user_id = $user_id");
    setAlert("Successfully deleted", "success", "allUsers.php");
  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to delete user", "error", "allUsers.php");
  }
}

?>