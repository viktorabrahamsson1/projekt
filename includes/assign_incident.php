<?php
require_once "session.php";
require_once "db.php";
require_once "redirect_by_role.php";

if ($_SESSION['role'] !== 'responder') {
    redirectBackByRole("Unauthorized", "error");
}

if (!isset($_POST['incident_id'])) {
    redirectBackByRole("Missing incident id", "error");
}

$incident_id = (int)$_POST['incident_id'];
$user_id = (int) $_SESSION['user_id'];

$stmt = $mysqli->prepare(
    "UPDATE incident_status
     SET user_id = ?, status_id = 2
     WHERE incident_id = ?"
);

$stmt->bind_param("ii", $user_id, $incident_id);
$stmt->execute();

redirectBackByRole("Successfully assigned to incident", "success");
exit;
