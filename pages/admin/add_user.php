<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
require_once "../../includes/db.php";
requireRoles(["admin"]);


if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST["id"])) {

  $first = $_POST["firstname"] ?? "";
  $last = $_POST["lastname"] ?? "";
  $username = $_POST["username"] ?? "";
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";
  $roleName = $_POST["role"] ?? "";

  if (!$first || !$last || !$username || !$email || !$password || !$roleName) {
    $error = "All fields are required.";
  } else {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("SELECT role_id FROM role WHERE role = ?");
    $stmt->bind_param("s", $roleName);
    $stmt->execute();
    $roleResult = $stmt->get_result()->fetch_assoc();

    if (!$roleResult) {
      $error = "Invalid role selected.";
    } else {
      $roleId = $roleResult["role_id"];

      // 5. Insert user into database
      $stmt = $mysqli->prepare("
                INSERT INTO user (first_name, last_name, username, email, password_hash, role_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

      $stmt->bind_param("sssssi", $first, $last, $username, $email, $passwordHash, $roleId);

      if ($stmt->execute()) {
        header("Location: allUsers.php");
        exit;
      } else {
        $error = "Failed to create user: " . $mysqli->error;
      }
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
  // game on
}

$user_id = null;
$title = "Add New User";
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
  $user_id = $_GET["id"];
  if (isset($user_id)) {
    $title = "Edit User: {$_GET['first_name']} ({$_GET['role']})";
    //TODO ändra knapp innehåll från create till edit user när man ska edita. 
  }

}




$content = <<<HTML
<section class="add_user_form_container">
  <h2>$title</h2>

HTML;

if (!empty($error)) {
  $content .= "<p style='color:red; font-weight:bold;'>$error</p>";
}

$content .= <<<HTML
  <form class="add_user_form" id="addUserForm" method="POST" action="add_user.php">
    
    <div class="form-row">
      <label for="firstname">First Name*</label>
      <input type="text" id="firstname" name="firstname" required>
    </div>

    <div class="form-row">
      <label for="lastname">Last Name*</label>
      <input type="text" id="lastname" name="lastname" required>
    </div>

    <div class="form-row">
      <label for="username">Username*</label>
      <input type="text" id="username" name="username" required>
    </div>

    <div class="form-row">
      <label for="email">Email*</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-row">
      <label for="password">Password*</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-row">
      <label for="role">Role*</label>
      <select id="role" name="role" required>
        <option value="">Select a role</option>
        <option value="admin">Admin</option>
        <option value="responder">Responder</option>
        <option value="reporter">Reporter</option>
      </select>
    </div>

    <button type="submit" class="add-user-submit">Create User</button>
  </form>
</section>
HTML;

require "../../includes/layout.php";
?>