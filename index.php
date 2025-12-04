<?php require_once "includes/session.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign in</title>
  <script src="./js/index.js"></script>
  <link rel="stylesheet" href="./css/index.css" />
  <link rel="stylesheet" href="./css/global.css" />
</head>

<body>
  <section class="loginsection" id="login-section">
    <p>Sign in</p>
    <?php
    if (isset($_GET["error"]) && $_GET["error"] == 1) {
      echo "<span>Invalid credentials</span>";
    }
    ?>
    <form action="auth/login.php" method="POST">
      <input type="text" placeholder="Username" id="username" name="username" />
      <input type="password" placeholder="Password" id="password" name="password" />
      <button onclick="login()">Log in</button>
    </form>
  </section>
</body>

</html>