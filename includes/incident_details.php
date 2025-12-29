<?php
$content = <<<HTML
          <div class="report_container">
    <h1 id="incident_form_header">Incident "ID"</h1>
    <div class="form_container">
        <form action="create_incident.php" method="POST" enctype="multipart/form-data">
            <label for="severity">Severity</label>
            <select name="severity" id="severity" required>
                <option value="">Choose Severity-level</option>
            </select>

            <label for="incident_type">Incident Type</label>
            <select name="incident_type" id="incident_type" required>
                <option value="">Choose Incident-Type</option>
            </select>

            <div id="asset_container">
                <label for="asset_container">Choose affected assets</label>
            </div>

            <label for="image">Upload image of incident</label>
            <input type="file" id="image" name="image" accept="image/*">

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
?>



<?php require_once "layout.php"; ?>