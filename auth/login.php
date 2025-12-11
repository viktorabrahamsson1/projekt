<?php
require_once "../includes/session.php";
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../index.php");
  exit;
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if (!$username || !$password) {
  header("Location: ../index.php?error=1");
  exit;
}

$stmt = $mysqli->prepare("
    SELECT 
        user.user_id,
        user.username,
        user.first_name,
        user.last_name,
        user.password_hash,
        role.role
    FROM user
    JOIN role ON user.role_id = role.role_id
    WHERE user.username = ?
");

$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
  header("Location: ../index.php?error=1");
  exit;
}

if (!password_verify($password, $user["password_hash"])) {
  header("Location: ../index.php?error=1");
  exit;
}

$_SESSION["logged_in"] = true;
$_SESSION["username"] = $user["username"];
$_SESSION["first_name"] = $user["first_name"];
$_SESSION["last_name"] = $user["last_name"];
$_SESSION["role"] = strtolower($user["role"]);
$_SESSION["user_id"] = $user["user_id"];

header("Location: ../main.php");
exit;

?>