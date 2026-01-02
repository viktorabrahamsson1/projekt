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

$severityOptions = "";
$severity_query = "SELECT severity_id, severity FROM severity";
$severity = $mysqli->query($severity_query);

while ($row = $severity->fetch_object()) {
    $severityOptions .= "<option value=\"{$row->severity_id}\">{$row->severity}</option>";
}

$typeOptions = "";
$type_query = "SELECT incident_type_id, incident_type FROM incident_type";
$type = $mysqli->query($type_query);

while ($row = $type->fetch_object()) {
    $typeOptions .= "<option value=\"{$row->incident_type_id}\">{$row->incident_type}</option>";
}

$assetOptions = "";
$asset_query = "SELECT asset_id, asset FROM asset";
$asset = $mysqli->query($asset_query);

while ($row = $asset->fetch_object()) {
    $assetOptions .= "<div>
    <input id=\"{$row->asset_id}\" type=\"checkbox\" name=\"assets[]\" value=\"{$row->asset_id}\"> 
    <label for=\"{$row->asset_id}\">{$row->asset}</label>
</div>";
}

$content = <<<HTML
<div class="report_container">

    <h1 id="incident_form_header">Incident {$incidentId}</h1>

    <div class="form_container">
        <form method="post" action="/includes/update_incident.php">

            <input type="hidden" name="incident_id" value="{$incidentId}">

            <label for="severity">Severity</label>
                <select name="severity" id="severity" required>
                    <option value="">Choose Severity-level</option>
                    $severityOptions
                </select>

                <label for="incident_type">Incident Type</label>
                <select name="incident_type" id="incident_type" required>
                    <option value="">Choose Incident-Type</option>
                    $typeOptions
                </select>

                <div id="asset_container">
                    <label for="asset_container">Choose affected assets</label>
                    $assetOptions
                </div>

                <label for="image">Upload image of incident</label>
                <input type="file" id="image" name="image" accept="image/*">

                <label for="occurrence_datetime">Set date of occurence</label>
                <input type="datetime-local" id="occurrence_datetime" name="occurrence_datetime">

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" cols="40"
                    placeholder="Write your description here">
                </textarea>

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

$content .= <<<HTML
<script src="../js/Fill-IncidentDetails.js"></script>
<script>
    const incidentData =
HTML;

$content .= json_encode(
    $incident,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);

$content .= <<<HTML
;
    fillIncidentDetails(incidentData);
</script>
HTML;

require_once "layout.php";
