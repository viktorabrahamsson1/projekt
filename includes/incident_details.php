<?php
require_once 'db.php';
require_once 'alert.php';
require_once "redirect_by_role.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectBackByRole();
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
JOIN incident_asset ia ON i.incident_id = ia.incident_id
JOIN asset ass ON ia.asset_id = ass.asset_id
JOIN incident_status istat ON i.incident_id = istat.incident_id
JOIN `status` stat ON istat.status_id = stat.status_id
WHERE i.incident_id = ?
GROUP BY
    i.incident_id,
    i.description,
    i.occurrence_datetime,
    s.severity,
    it.incident_type,
    i.updated_at,
    stat.status
";

$stmt = $mysqli->prepare($sql);
$stmt->execute([$incidentId]);
$incident = $stmt->get_result()->fetch_assoc();

if (!$incident) {
    redirectBackByRole();
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

$content = <<<HTML
<div class="report_container">

    <h1 id="incident_form_header">Incident {$incidentId}</h1>

    <div class="form_container">
        <form>

            <label for="severity">Severity</label>
            <input type="text" value="{$severity}" disabled>

            <label for="incident_type">Incident Type</label>
            <input type="text" value="{$incidentType}" disabled>

                <label>Affected assets</label>
                <input type="text" value="{$assets}" disabled>

            <label for="occurrence_datetime">Date of occurrence</label>
            <input type="datetime-local"
                   id="occurrence_datetime"
                   value="{$occurrenceValue}"
                   disabled>

            <label for="description">Description</label>
            <textarea rows="5" disabled>{$description}</textarea>

            <label>Status</label>
            <input type="text" value="{$status}" disabled>

        </form>
    </div>
</div>
HTML;

require_once "layout.php";
