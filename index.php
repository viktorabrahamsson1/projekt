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
        <input type="text" placeholder="Username" id="username" name="username"/>
        <input type="password" placeholder="Password" id="password" name="password"/>
        <button onclick="login()">Login</button>
      <button onclick="window.location.href='register.php'">
         Don't have an account? Register here! 
      </button>
    </section>
  </body>
</html>
