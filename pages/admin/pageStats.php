<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);
require_once "../../includes/db.php";

// Query page visit counts
$sql = "
    SELECT p.page_url, COUNT(v.visit_log_id) AS total_visits
    FROM visit_log v
    LEFT JOIN page_url p ON v.page_url_id = p.page_url_id
    GROUP BY p.page_url
    ORDER BY total_visits DESC
";

$result = $mysqli->query($sql);

$content = <<<HTML
<section class="visitlogs_container">

    <div class="visitlogs_navigation">
        <a href="visitLogs.php">All Logs</a> |
        <a href="userSummary.php">User Summary</a> |
        <a href="pageStats.php">Page Stats</a>
    </div>

    <div class="visitlogs_card">
        <div class="visitlogs_table_wrapper">

            <table class="visitlogs_table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Total Visits</th>
                    </tr>
                </thead>
                <tbody>
HTML;

while ($row = $result->fetch_assoc()) {
    $content .= "
        <tr>
            <td>{$row["page_url"]}</td>
            <td>{$row["total_visits"]}</td>
        </tr>
    ";
}

$content .= <<<HTML
                </tbody>
            </table>

        </div>
    </div>

</section>
HTML;

require "../../includes/layout.php";
?>
