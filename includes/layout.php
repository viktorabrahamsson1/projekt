<?php
require_once "session.php";
require_once "track_visit.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/incident_form.css">
  <link rel="stylesheet" href="/css/incidents.css">
  <link rel="stylesheet" href="/css/global.css">
  <link rel="stylesheet" href="/css/all_users.css">
  <link rel="stylesheet" href="/css/add_user.css">
  <link rel="stylesheet" href="/css/visitLogs.css">
  <link rel="stylesheet" href="/css/error.css">
  <link rel="stylesheet" href="/css/analytics.css">
  <link rel="stylesheet" href="/css/comment.css">


  <title>main</title>
  <script src="/js/deleteModal.js" defer></script>
  <script src="/js/editUser.js" defer></script>
  <script src="/js/alert-box.js" defer></script>
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
          <p>Incident Reporter</p>
          <button onclick="window.location.href='/pages/reporter/incident_form.php'">Report incident</button>
          <button onclick="window.location.href='/pages/reporter/my_incidents.php'">My incidents</button>
        </div>
      HTML;
    }


    if ($_SESSION["role"] === "responder") {
      $buttons .= <<<HTML
          <div class=" aside-button-container" id="responder">
            <p>Incident Responder</p>
            <button onclick="window.location.href='/pages/responder/pending_incidents.php'">Pending incidents</button>
            <button onclick="window.location.href='/pages/responder/assigned_incidents.php'">Assigned incidents</button>
          </div>
      HTML;
    }


    if ($_SESSION["role"] === "admin") {
      $buttons .= <<<HTML
        <div class="aside-button-container" id="admin">
          <p>System Administrator</p>
          <button onclick="window.location.href='/main.php'">Dashboard</button>
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
  <div id="deleteModal" class="modal-overlay">
    <div class="modal-box">
      <p>Are you sure you want to delete this user?</p>
      <div class="modal-actions">
        <button id="cancelDelete">Cancel</button>
        <button id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>

  <?php
  if (isset($_SESSION['alert'])) {

    $type = $_SESSION['alert']['type'];
    $message = htmlspecialchars($_SESSION['alert']['message']);

    if ($type === 'success') {
      $class = 'success';
      $icon = '
        <svg class="alert-box-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0
                     m-3.97-3.03a.75.75 0 0 0-1.08.022
                     L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06
                     1.06L6.97 11.03a.75.75 0 0 0 1.079-.02
                     l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>';
    } else {
      $class = 'error';
      $icon = '
        <svg class="alert-box-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"      fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
      </svg>';
    }

    echo '
    <div class="alert-box ' . $class . '" id="alert-box">
        ' . $icon . '
        <p>' . $message . '</p>
    </div>';

    unset($_SESSION['alert']);
  }
  ?>


</body>

</html>