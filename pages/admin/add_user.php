<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);

$content = <<<HTML
<section class="add_user_form_container">
  <h2>Add New User</h2>

  <form class="add_user_form" id="addUserForm" method="POST" action="/pages/admin/addUserHandler.php">
    
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