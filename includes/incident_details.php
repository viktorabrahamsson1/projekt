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
    stat.status,
    GROUP_CONCAT(
        ic.comment
        ORDER BY ic.timestamp
        SEPARATOR ' | '
    ) AS comments
FROM incident i
JOIN severity s ON i.severity_id = s.severity_id
JOIN incident_type it ON i.incident_type_id = it.incident_type_id

LEFT JOIN incident_comment ic 
    ON i.incident_id = ic.incident_id

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

$comments = "";
$incident_comment_query = "
    SELECT incident_comment_id, comment, timestamp
    FROM incident_comment
    WHERE incident_id = $incidentId
    ORDER BY timestamp ASC
";
$incident_comment = $mysqli->query($incident_comment_query);

while ($row = $incident_comment->fetch_object()) {
    $comments .= "
        <input  class=comment id=\"{$row->incident_comment_id}\" type=text value=\"{$row->comment} - {$row->timestamp}\" {$disabled}>
        <p></p>";
}

$statusOptions = "";
$status_query = "SELECT status_id, status FROM status";
$status = $mysqli->query($status_query);

while ($row = $status->fetch_object()) {
    $statusOptions .= "<option value=\"{$row->status_id}\">{$row->status}</option>";
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

                <p></p>

                <div class="comments">
                    <label for="Comments">Comments</label>
                    <p></p>
                    $comments
                    <div class="comment_button" onclick="openCommentModal($incidentId)">Add comment</div>
                </div>

                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="">Choose status-level</option>
                    $statusOptions
                </select>
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

$content .= <<<HTML
</div>
</div>
<div id="commentModal" hidden>
    <div class="modal_backdrop">
        <div class="modal_content">
            <h3>Add comment</h3>

            <textarea id="commentText" placeholder="Write your comment..."></textarea>

            <div class="modal_actions">
                <div class="modal_btn" onclick="submitComment()">Submit</div>
                <div class="modal_btn cancel" onclick="closeCommentModal()">Cancel</div>
            </div>
        </div>
    </div>
</div>

<form id="commentForm" action="add_comment.php" method="POST">
    <input type="hidden" name="incident_id" id="incident_id">
    <input type="hidden" name="comment" id="comment">
    <input type="hidden" name="add_comment" value="1">
</form>

<script src="../js/add_comment.js"></script>
HTML;
require_once "layout.php";
