<?php
require_once __DIR__ . "/db.php";

$ua = $_SERVER["HTTP_USER_AGENT"] ?? "";
$browserId = 7; // default
$detected = "Unknown";

// EDGE
if (stripos($ua, "Edg") !== false) {
    $browserId = 3;
    $detected = "Edge";

// OPERA
} elseif (stripos($ua, "OPR") !== false || stripos($ua, "Opera") !== false) {
    $browserId = 4;
    $detected = "Opera";

// BRAVE
} elseif (stripos($ua, "Brave") !== false) {
    $browserId = 6;
    $detected = "Brave";

// FIREFOX
} elseif (stripos($ua, "Firefox") !== false) {
    $browserId = 2;
    $detected = "Firefox";

// CHROME
} elseif (stripos($ua, "Chrome") !== false) {
    $browserId = 1;
    $detected = "Chrome";

// SAFARI
} elseif (stripos($ua, "Safari") !== false) {
    $browserId = 5;
    $detected = "Safari";
}

// IP
$ip = $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0";
if ($ip === "::1") $ip = "127.0.0.1";

// PAGE
$page = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$pageMap = [
    "/index.php" => 1,
    "/incident/create.php" => 2,
    "/login.php" => 3,
    "/dashboard.php" => 4,
    "/account/settings.php" => 5,
    "/logout.php" => 6,
    "/reports/view.php" => 7,
    "/pages/admin/visitLogs.php" => 8,
    "/pages/admin/allUsers.php" => 9,
    "/pages/admin/incidents.php" => 10,
    "/pages/admin/analytics.php" => 11,
    "/pages/admin/add_user.php" => 12,
];

$pageId = $pageMap[$page] ?? null;

if ($pageId !== null) {
    $stmt = $mysqli->prepare("
        INSERT INTO visit_log (page_url_id, browser_id, host_ip, timestamp)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("iis", $pageId, $browserId, $ip);
    $stmt->execute();
}
?>
