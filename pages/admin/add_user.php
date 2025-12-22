<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
require_once "../../includes/db.php";
require_once "../../includes/alert.php";
requireRoles(["admin"]);

$userId = $_POST["user_id"] ?? $_GET["id"] ?? "";
$isEditing = !empty($userId);
$title = !$isEditing ? "Add User" : "Edit User";
$btnText = !$isEditing ? "Create User" : "Edit User";

$first = $last = $username = $email = $roleName = "";

if ($isEditing && $_SERVER["REQUEST_METHOD"] !== "POST") {
  $stmt = $mysqli->prepare("
    SELECT u.first_name, u.last_name, u.username, u.email, r.role
    FROM user u
    JOIN role r ON u.role_id = r.role_id
    WHERE u.user_id = ?
  ");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();

  if (!$user) {
    header("Location: allUsers.php");
    exit;
  }

  $first = $user["first_name"];
  $last = $user["last_name"];
  $username = $user["username"];
  $email = $user["email"];
  $roleName = strtolower($user["role"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $first = $_POST["firstname"] ?? "";
  $last = $_POST["lastname"] ?? "";
  $username = $_POST["username"] ?? "";
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";
  $roleName = $_POST["role"] ?? "";

  if (!$first || !$last || !$username || !$email || !$roleName) {
    header("Location: allUsers.php");
    exit;
  } else {

    $stmt = $mysqli->prepare("SELECT role_id FROM role WHERE role = ?");
    $stmt->bind_param("s", $roleName);
    $stmt->execute();
    $roleResult = $stmt->get_result()->fetch_assoc();

    if (!$roleResult) {
      setAlert("Failed to fetch role", "error", "allUsers.php");
    } else {
      $roleId = $roleResult["role_id"];

      if ($isEditing) {
        if ($password) {

          $hash = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $mysqli->prepare("
            UPDATE user
            SET first_name=?, last_name=?, username=?, email=?, password_hash=?, role_id=?
            WHERE user_id=?");
          $stmt->bind_param("sssssii", $first, $last, $username, $email, $hash, $roleId, $userId);

        } else {

          $stmt = $mysqli->prepare("
          UPDATE user
          SET first_name=?, last_name=?, username=?, email=?, role_id=?
          WHERE user_id=?");
          $stmt->bind_param("ssssii", $first, $last, $username, $email, $roleId, $userId);

        }

        try {
          $stmt->execute();
          setAlert("You have successfully edited a user", "success", "allUsers.php");
        } catch (mysqli_sql_exception $e) {
          setAlert("failed to edit user", "error", "add_user.php?id=" . $userId);
        }

      } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("
                INSERT INTO user (first_name, last_name, username, email, password_hash, role_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

        $stmt->bind_param("sssssi", $first, $last, $username, $email, $hash, $roleId);

        try {
          $stmt->execute();
          setAlert("You have Successfully created a new user", "success", "allUsers.php");
        } catch (mysqli_sql_exception $e) {
          setAlert("Failed to create new user", "error", "add_user.php");
        }
      }
    }
  }
}


$adminSel = ($roleName === "admin") ? "selected" : "";
$responderSel = ($roleName === "responder") ? "selected" : "";
$reporterSel = ($roleName === "reporter") ? "selected" : "";

$passwordRequired = $isEditing ? "" : "required";


$content = <<<HTML
<section class="add_user_form_container">
  <h2>$title</h2>
HTML;



$content .= <<<HTML
  <form class="add_user_form" id="addUserForm" method="POST" action="add_user.php">
    <input type="hidden" name="user_id" value="$userId">
    
    <div class="form-row">
      <label for="firstname">First Name*</label>
      <input type="text" id="firstname" name="firstname" value="$first" required >
    </div>

    <div class="form-row">
      <label for="lastname">Last Name*</label>
      <input type="text" id="lastname" name="lastname" value="$last" required>
    </div>

    <div class="form-row">
      <label for="username">Username*</label>
      <input type="text" id="username" name="username" value="$username" required>
    </div>

    <div class="form-row">
      <label for="email">Email*</label>
      <input type="email" id="email" name="email" value="$email" required>
    </div>

    <div class="form-row">
      <label for="password">Password*</label>
      <input type="password" id="password" name="password" $passwordRequired>
    </div>

    <div class="form-row">
      <label for="role">Role*</label>
      <select id="role" name="role" required>
        <option value="">Select a role</option>
        <option value="admin" $adminSel >Admin</option>
        <option value="responder" $responderSel>Responder</option>
        <option value="reporter" $reporterSel>Reporter</option>
      </select>
    </div>

    <button type="submit" class="add-user-submit">$btnText</button>
  </form>
</section>
HTML;

require "../../includes/layout.php";
?>