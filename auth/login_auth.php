
<?php
  session_start();
  $username = $_POST['username'];
  $password = $_POST['password'];
  $_SESSION["username"] = $username;  
  header("main.php");
  exit
?>