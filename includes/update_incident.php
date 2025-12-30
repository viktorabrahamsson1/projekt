<?php

require_once "redirect_by_role.php";
require_once "db.php";

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

$mysqli->begin_transaction();

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
        "SELECT severity_id FROM severity WHERE severity = ?"
    );
    $stmt->bind_param("s", $_POST['severity']);
    $stmt->execute();
    $severityId = $stmt->get_result()->fetch_assoc()['severity_id'];

    $stmt = $mysqli->prepare(
        "UPDATE incident SET severity_id = ? WHERE incident_id = ?"
    );
    $stmt->bind_param("ii", $severityId, $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "SELECT incident_type_id FROM incident_type WHERE incident_type = ?"
    );
    $stmt->bind_param("s", $_POST['incident_type']);
    $stmt->execute();
    $typeId = $stmt->get_result()->fetch_assoc()['incident_type_id'];

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

    $assets = array_map('trim', explode(',', $_POST['assets']));

    foreach ($assets as $asset) {
        $stmt = $mysqli->prepare(
            "SELECT asset_id FROM asset WHERE asset = ?"
        );
        $stmt->bind_param("s", $asset);
        $stmt->execute();
        $assetId = $stmt->get_result()->fetch_assoc()['asset_id'];

        $stmt = $mysqli->prepare(
            "INSERT INTO incident_asset (incident_id, asset_id)
             VALUES (?, ?)"
        );
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
