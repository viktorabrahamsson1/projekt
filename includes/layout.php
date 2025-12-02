<?php require_once "session.php" ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/incident_form.css">
  <title>main</title>
  <script src="../js/index.js"></script>

</head>

<body>
  <header>
    <p>Incident Report Portal
    </p>
    <div>
      <button>Account</button>
      <button onclick=" window.location.href='/logout.php'">Sign out</button>
    </div>
  </header>

  <aside>
    <?php
    $buttons = "";
    if ($_SESSION["role"] === "reporter") {
      $buttons .= <<<HTML
        <div class="aside-button-container" id="reporter">
          <p>Reporter</p>
          <button onclick="window.location.href='/pages/reporter/incident_form.php'">Report incident</button>
          <button>My incidents</button>
          <button>Add evidence</button>
        </div>
      HTML;
    }


    if ($_SESSION["role"] === "responder") {
      $buttons .= <<<HTML
          <div class=" aside-button-container" id="responder">
            <p>Responder</p>
            <button>All indcidents</button>
            <button>Update status</button>
            <button>Add comment</button>
          </div>
      HTML;
    }


    if ($_SESSION["role"] === "admin") {
      $buttons .= <<<HTML
        <div class="aside-button-container" id="admin">
          <p>Admin</p>
          <button onclick="window.location.href='/pages/admin/incidents.php'">View incidents</button>
          <button onclick="window.location.href='/pages/admin/allUsers.php'">All users</button>
          <button onclick="window.location.href='/pages/admin/analytics.php'">Analytics</button>
          <button onclick="window.location.href='/pages/admin/visitLogs.php'">Visit logs</button>
        </div>
      HTML;
    }
    echo $buttons;
    ?>



  </aside>

  <main>
    <?php
    echo $content;
    ?>
  </main>

</body>

</html>