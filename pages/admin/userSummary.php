<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);
require_once "../../includes/db.php";

// Fetch all users for dropdown
$userResult = $mysqli->query("SELECT user_id, username FROM user ORDER BY username ASC");

// If user is selected
$selectedUser = $_GET["user"] ?? null;
$userLogs = [];

if ($selectedUser) {
    $stmt = $mysqli->prepare("
        SELECT v.visit_log_id, v.host_ip, b.browser, v.timestamp, p.page_url
        FROM visit_log v
        LEFT JOIN user_visit_log uv ON uv.visit_log_id = v.visit_log_id
        LEFT JOIN browser b ON v.browser_id = b.browser_id
        LEFT JOIN page_url p ON v.page_url_id = p.page_url_id
        WHERE uv.user_id = ?
        ORDER BY v.timestamp DESC
    ");
    $stmt->bind_param("i", $selectedUser);
    $stmt->execute();
    $userLogs = $stmt->get_result();
}

$content = <<<HTML
<section class="visitlogs_container">

    <a href="visitLogs.php">All Logs</a> |
    <a href="userSummary.php">User Summary</a> |
    <a href="pageStats.php">Page Stats</a>

    <form method="GET">
        <select name="user" onchange="this.form.submit()">
            <option value="">Select a user</option>
HTML;

// Create dropdown
while ($u = $userResult->fetch_assoc()) {
    $sel = ($selectedUser == $u["user_id"]) ? "selected" : "";
    $content .= "<option value='{$u["user_id"]}' $sel>{$u["username"]}</option>";
}

$content .= "</select></form><br>";

if ($selectedUser && $userLogs->num_rows > 0) {

    // ⭐⭐ WRAP TABLE IN SCROLLABLE DIV ⭐⭐
    $content .= "
    <div class='visitlogs_table_wrapper'>
        <table class='visitlogs_table'>
            <thead>
                <tr>
                    <th>Visit ID</th>
                    <th>Host IP</th>
                    <th>Browser</th>
                    <th>Timestamp</th>
                    <th>Visited Page</th>
                </tr>
            </thead>
            <tbody>
    ";

    while ($row = $userLogs->fetch_assoc()) {
        $content .= "
                <tr>
                    <td>{$row["visit_log_id"]}</td>
                    <td>{$row["host_ip"]}</td>
                    <td>{$row["browser"]}</td>
                    <td>{$row["timestamp"]}</td>
                    <td>{$row["page_url"]}</td>
                </tr>";
    }

    $content .= "
            </tbody>
        </table>
    </div>
    ";
}

$content .= "</section>";

require "../../includes/layout.php";
?>