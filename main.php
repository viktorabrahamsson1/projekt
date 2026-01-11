<?php
require_once "./includes/session.php";
require_once "./auth/auth.php";
require_once "./includes/db.php";
require_once "./includes/alert.php";
requireLogin();

$content = "";

if ($_SESSION["role"] === "admin") {

  $sql_all_users = "SELECT
    COUNT(*) AS total_users,
    SUM(role.role = 'Admin') AS admins,
    SUM(role.role = 'Responder') AS responders,
    SUM(role.role = 'Reporter') AS reporters
    FROM user
    JOIN role ON user.role_id = role.role_id;
";

  try {
    $stmt = $mysqli->prepare($sql_all_users);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total_users = (int) $result["total_users"] ?? 0;
    $total_admins = (int) $result["admins"] ?? 0;
    $total_responders = (int) $result["responders"] ?? 0;
    $total_reporters = (int) $result["reporters"] ?? 0;
  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to fetch users", "error", "/pages/admin/allUsers.php");
  }

  $sql_critical_count = "SELECT SUM(incident.severity_id = 4) AS critical_count FROM incident;";
  try {
    $stmt = $mysqli->prepare($sql_critical_count);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $critical_count = (int) $result["critical_count"] ?? 0;

  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to fetch critical count", "error", "/pages/admin/allUsers.php");
  }

  $sql_incidents_today =
    "SELECT COUNT(*) AS incidents_today
    FROM incident
    WHERE occurrence_datetime >= CURDATE()
    AND occurrence_datetime < CURDATE() + INTERVAL 1 DAY;";

  try {
    $stmt = $mysqli->prepare($sql_incidents_today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $incidents_today_count = (int) $result["incidents_today"] ?? 0;

  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to fetch todays incident count", "error", "/pages/admin/allUsers.php");
  }

  $sql_open_incidents =
    "SELECT COUNT(*) AS open_incidents
    FROM incident_status 
    WHERE incident_status.status_id = 2
    OR incident_status.status_id = 1; ";

  try {
    $stmt = $mysqli->prepare($sql_open_incidents);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $open_incidents_count = (int) $result["open_incidents"] ?? 0;

  } catch (mysqli_sql_exception $e) {
    setAlert("Failed to fetch open incident count", "error", "/pages/admin/allUsers.php");
  }




  $content .= "
<div class='admin-dashboard'>
  <div class='dashboard-card total-users'>
    <h3>Total Users</h3>
    <p class='big-number'>{$total_users}</p>
    <ul>
      <li><span>Admin</span><span>{$total_admins}</span></li>
      <li><span>Responder</span><span>{$total_responders}</span></li>
      <li><span>Reporter</span><span>{$total_reporters}</span></li>
    </ul>
  </div>

  <div class='dashboard-card open-incidents'>
    <h3>Open Incidents</h3>
    <p class='big-number warning'>{$open_incidents_count}</p>
    <p class='subtext'>Requires attention</p>
  </div>

  <div class='dashboard-card incidents-today'>
    <h3>Incidents Today</h3>
    <p class='big-number'>{$incidents_today_count}</p>
    <p class='subtext'>Last 24 hours</p>
  </div>

  <div class='dashboard-card critical-incidents'>
    <h3>Critical Severity</h3>
    <p class='big-number danger'>{$critical_count}</p>
    <p class='subtext'>High priority</p>
  </div>

  <div class='dashboard-card last-visited-users'>
    <h3>Last visited users</h3>
    <div class='last-visited-users-header'>
      <p>First name</p>
      <p>Last name</p>
      <p>Email address</p>
      <p>Role</p>
    </div>
    <ul class='last-visited-user-list'>";

  $sql = "
  SELECT
    u.user_id,
    u.first_name,
    u.last_name,
    u.email,
    r.role,
    MAX(uvl.visit_log_id) AS last_visit_id
FROM user_visit_log uvl
JOIN user u ON uvl.user_id = u.user_id
JOIN role r ON u.role_id = r.role_id
GROUP BY
    u.user_id,
    u.first_name,
    u.last_name,
    u.email,
    r.role
ORDER BY last_visit_id DESC
LIMIT 5; ";

  $result = $mysqli->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $content .= "
      <li>
        <p>{$row['first_name']}</p>
        <p>{$row['last_name']}</p>
        <p>{$row['email']}</p>
        <p>{$row['role']}</p>
        
      </li>
    ";
    }
  }

  $content .= "
    </ul>
  </div>
</div>
";
} else if ($_SESSION["role"] === "responder") {
  $content .= "";
} else if ($_SESSION["role"] === "reporter") {
  $content .= "";
}

require_once "./includes/layout.php";

?>