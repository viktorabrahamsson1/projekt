<?php require_once "includes/session.php";
if (!isset($_SESSION["logged_in"])) {
  header("Location: index.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>main</title>
  <link rel="stylesheet" href="./css/main.css" />
  <script src="./js/index.js"></script>
</head>

<body onload="asideButtons()">
  <header>
    <p>Incident report portal <?php echo $_SESSION["username"]; ?></p>
    <div>
      <button>Account</button>
      <button onclick="window.location.href='logout.php'">Sign out</button>
    </div>
  </header>

  <aside>
    <div class="aside-button-container" id="reporter">
      <p>Reporter</p>
      <button>Report incident</button>
      <button>My incidents</button>
      <button>Add evidence</button>
    </div>
    <div class=" aside-button-container" id="responder">
      <p>Responder</p>
      <button>All indcidents</button>
      <button>Update status</button>
      <button>Add comment</button>
    </div>
    <div class="aside-button-container" id="admin">
      <p>Admin</p>
      <button>View incidents</button>
      <button>All users</button>
      <button>Analytics</button>
      <button>Visit logs</button>
    </div>
  </aside>

  <main>
    <section class="allUsers mainContent" id="allUsers">
      <p>Users</p>
      <ul class="userList">
        <li>
          <p>bob</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
        <li>
          <p>Emma</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
        <li>
          <p>Jesper</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
        <li>
          <p>ben</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
        <li>
          <p>bobo</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
        <li>
          <p>bobo</p>
          <div>
            <button>Edit</button>
            <button>Delete</button>
          </div>
        </li>
      </ul>
    </section>
  </main>
</body>

</html>