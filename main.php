<?php
require_once "./includes/session.php";
require_once "./auth/auth.php";
requireLogin();

$content = "";

if ($_SESSION["role"] === "admin") {
  $content .= "
<div class='admin-dashboard'>
  <div class='dashboard-card total-users'>
    <h3>Total Users</h3>
    <p class='big-number'>8</p>
    <ul>
      <li><span>Admin</span><span>3</span></li>
      <li><span>Responder</span><span>5</span></li>
      <li><span>Reporter</span><span>1</span></li>
    </ul>
  </div>

  <div class='dashboard-card open-incidents'>
    <h3>Open Incidents</h3>
    <p class='big-number warning'>7</p>
    <p class='subtext'>Requires attention</p>
  </div>

  <div class='dashboard-card incidents-today'>
    <h3>Incidents Today</h3>
    <p class='big-number'>3</p>
    <p class='subtext'>Last 24 hours</p>
  </div>

  <div class='dashboard-card critical-incidents'>
    <h3>Critical Severity</h3>
    <p class='big-number danger'>2</p>
    <p class='subtext'>High priority</p>
  </div>

  <div class='dashboard-card last-visited-users'>
    <h3>Last visited users</h3>
    <ul>
      <li>bob</li>
      <li>bob</li>
      <li>bob</li>
    </ul>
  </div>

</div>
";
} else if ($_SESSION["role"] === "responder") {
  $content .= "RESPONDER";
} else if ($_SESSION["role"] === "reporter") {
  $content .= "REPORTER";
}






require_once "./includes/layout.php";

?>