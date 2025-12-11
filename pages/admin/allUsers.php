<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
require_once "../../includes/db.php";
requireRoles(["admin"]);

$sql = "
SELECT user.user_id, user.first_name, user.last_name, user.email, role.role
FROM user
JOIN role ON user.role_id = role.role_id
";

$content = '
<div class="all_users_wrapper">
  <section class="all_users" id="allUsers">
    <div class="all_users_header">
      <p>First name</p>
      <p>Last name</p>
      <p>Email address</p>
      <p>Role</p>
    </div>
    <ul class="user-list">
';

$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $content .= "
      <li>
        <p>{$row['first_name']}</p>
        <p>{$row['last_name']}</p>
        <p>{$row['email']}</p>
        <p>{$row['role']}</p>
        <div>
          <button class='edit-btn' data-userid={$row['user_id']} data-first-name={$row['first_name']} data-role={$row['role']}>Edit</button>
          <button class='delete-btn' data-userid={$row['user_id']}>Delete</button>
        </div>
      </li>
    ";
  }
}

$content .= '
    </ul>
  </section>
  <button class="add-user-button" onclick="window.location.href=\'add_user.php\'">Add User</button>
</div>
';

require "../../includes/layout.php";
?>