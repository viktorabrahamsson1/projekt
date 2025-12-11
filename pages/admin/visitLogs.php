<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);
require_once "../../includes/db.php"; 

// Fetch dynamic visit logs using JOIN
$sql = "
    SELECT 
        v.visit_log_id,
        v.host_ip,
        b.browser AS browser_name,
        v.timestamp,
        p.page_url
    FROM visit_log v
    LEFT JOIN browser b 
        ON v.browser_id = b.browser_id
    LEFT JOIN page_url p 
        ON v.page_url_id = p.page_url_id
    ORDER BY v.timestamp DESC
";


$result = $mysqli->query($sql);

if (!$result) {
    die("Query error: " . $mysqli->error);
}

// Page content
$content = <<<HTML

<section class="visitlogs_container">
    
    <div class="visitlogs_card">

        <div class="visitlogs_table_wrapper">
            <table class="visitlogs_table">
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
HTML;

// Loop through rows
while ($row = $result->fetch_assoc()) {

    $browser = $row["browser_name"] ?? "Unknown";
    $pageUrl = $row["page_url"] ?? "Unknown";

    $content .= "
        <tr>
            <td>{$row["visit_log_id"]}</td>
            <td>{$row["host_ip"]}</td>
            <td>{$browser}</td>
            <td>{$row["timestamp"]}</td>
            <td>{$pageUrl}</td>
        </tr>
    ";
}

// If no rows exist
if ($result->num_rows === 0) {
    $content .= '
        <tr class="placeholder_row">
            <td colspan="5">No visit logs found.</td>
        </tr>
    ';
}

// Close table + wrapper
$content .= <<<HTML
                </tbody>
            </table>
        </div>

    </div>

</section>

HTML;

require "../../includes/layout.php";
?>
