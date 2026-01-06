<?php

require_once "redirect_by_role.php";
require_once "db.php";
require_once "alert.php";

if (!in_array($_SESSION['role'] ?? '', ['admin', 'responder'], true)) {
    http_response_code(403);
    exit('Unauthorized');
}

$required = [
    'incident_id',
    'severity',
    'incident_type',
    'assets',
    'occurrence_datetime',
    'description',
    'status'
];

foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        $_SESSION['alert'] = [
            'message' => 'Missing form data.',
            'type' => 'error'
        ];
    }
}

$incidentId = (int) $_POST['incident_id'];
$severityId = (int) $_POST['severity'];
$typeId     = (int) $_POST['incident_type'];

$mysqli->begin_transaction();

/* echo '<pre>';
var_dump($_POST);
exit; */

try {
    $stmt = $mysqli->prepare(
        "UPDATE incident
         SET description = ?, occurrence_datetime = ?
         WHERE incident_id = ?"
    );
    $stmt->bind_param(
        "ssi",
        $_POST['description'],
        $_POST['occurrence_datetime'],
        $incidentId
    );
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "UPDATE incident SET severity_id = ? WHERE incident_id = ?"
    );
    $stmt->bind_param("ii", $severityId, $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "UPDATE incident SET incident_type_id = ? WHERE incident_id = ?"
    );
    $stmt->bind_param("ii", $typeId, $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident_asset WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident_asset WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $assets = $_POST['assets'];

    $stmt = $mysqli->prepare(
        "INSERT INTO incident_asset (incident_id, asset_id)
     VALUES (?, ?)"
    );

    foreach ($assets as $assetId) {
        $assetId = (int) $assetId;
        $stmt->bind_param("ii", $incidentId, $assetId);
        $stmt->execute();
    }

    $stmt = $mysqli->prepare(
        "SELECT status_id FROM status WHERE status = ?"
    );
    $stmt->bind_param("s", $_POST['status']);
    $stmt->execute();
    $statusId = $stmt->get_result()->fetch_assoc()['status_id'];

    $stmt = $mysqli->prepare(
        "UPDATE incident_status
         SET status_id = ?
         WHERE incident_id = ?"
    );
    $stmt->bind_param("ii", $statusId, $incidentId);
    $stmt->execute();

    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    redirectBackByRole("Failed to update incident.", "error");
}

redirectBackByRole("Successfully updated incident", "success");
