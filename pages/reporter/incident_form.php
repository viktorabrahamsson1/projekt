<?php
require_once "../../includes/session.php";
require_once "../../includes/db.php";
require_once "../../auth/auth.php";
requireRoles(["reporter"]);

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
        <h1 id="incident_form_header">Incident Report Form</h1>
        <div class="form_container">
            <form action="create_incident.php" method="POST" enctype="multipart/form-data">
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
                <input type="file" id="image" name="image" accept="image/*" required>

                <label for="occurrence_datetime">Set date of occurence</label>
                <input type="datetime-local" id="occurrence_datetime" name="occurrence_datetime">

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" cols="40"
                    placeholder="Write your description here"></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
HTML;
require_once "../../includes/layout.php";
