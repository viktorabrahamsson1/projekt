<?php
require_once 'db.php';
require_once 'alert.php';
require_once "redirect_by_role.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectBackByRole("There was no incident details", "error");
}

$incidentId = (int) $_GET['id'];

$sql = "
SELECT
    i.incident_id,
    i.description,
    i.occurrence_datetime,
    s.severity,
    it.incident_type,
    GROUP_CONCAT(DISTINCT ass.asset ORDER BY ass.asset SEPARATOR ', ') AS assets,
    i.updated_at,
    stat.status
FROM incident i
JOIN severity s ON i.severity_id = s.severity_id
JOIN incident_type it ON i.incident_type_id = it.incident_type_id

LEFT JOIN incident_asset ia ON i.incident_id = ia.incident_id
LEFT JOIN asset ass ON ia.asset_id = ass.asset_id

LEFT JOIN incident_status istat ON i.incident_id = istat.incident_id
LEFT JOIN `status` stat ON istat.status_id = stat.status_id

WHERE i.incident_id = ?
GROUP BY
    i.incident_id,
    i.description,
    i.occurrence_datetime,
    s.severity,
    it.incident_type,
    i.updated_at,
    stat.status;
";

$stmt = $mysqli->prepare($sql);
$stmt->execute([$incidentId]);
$incident = $stmt->get_result()->fetch_assoc();

if (!$incident) {
    redirectBackByRole("There was no incident details", "error");
}

$severity = htmlspecialchars($incident['severity']);
$incidentType = htmlspecialchars($incident['incident_type']);
$assets = htmlspecialchars($incident['assets']);
$description = htmlspecialchars($incident['description']);
$status = htmlspecialchars($incident['status']);

$occurrenceValue = date(
    'Y-m-d\TH:i',
    strtotime($incident['occurrence_datetime'])
);

$role = $_SESSION['role'];

$canEdit   = in_array($role, ['admin', 'responder'], true);
$canDelete = ($role === 'admin');
$disabled  = $canEdit ? '' : 'disabled';

$content = <<<HTML
<div class="report_container">

    <h1 id="incident_form_header">Incident {$incidentId}</h1>

    <div class="form_container">
        <form method="post" action="/includes/update_incident.php">

            <input type="hidden" name="incident_id" value="{$incidentId}">

            <label>Severity</label>
            <input type="text" name="severity" value="{$severity}" {$disabled}>

            <label>Incident Type</label>
            <input type="text" name="incident_type" value="{$incidentType}" {$disabled}>

            <label>Affected assets</label>
            <input type="text" name="assets" value="{$assets}" {$disabled}>

            <label>Date of occurrence</label>
            <input type="datetime-local"
                   name="occurrence_datetime"
                   value="{$occurrenceValue}"
                   {$disabled}>

            <label>Description</label>
            <textarea name="description" rows="5" {$disabled}>{$description}</textarea>

            <label>Status</label>
            <input type="text" name="status" value="{$status}" {$disabled}>
HTML;

if ($canEdit) {
    $content .= <<<HTML
            <button type="submit" class="btn-primary">
                Save changes
            </button>
HTML;
}

$content .= <<<HTML
        </form>
HTML;

if ($canDelete) {
    $content .= <<<HTML
        <form method="post"
              action="delete_incident.php"
              onsubmit="return confirm('Are you sure you want to delete this incident?');">

            <input type="hidden" name="incident_id" value="{$incidentId}">

            <button type="submit" class="btn-danger">
                Delete incident
            </button>
        </form>
HTML;
}

require_once "layout.php";
