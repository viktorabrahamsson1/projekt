<?php
require_once "../../includes/db.php";

if (
    !isset($_POST["severity"]) ||
    !isset($_POST["incident_type"]) ||
    !isset($_POST["incident_asset"]) ||
    !isset($_POST["description"]) ||
    !isset($_FILES["image"])
) {
    die("Missing form data.");
}

$severity_id = intval($_POST["severity"]);
$incident_type_id = intval($_POST["incident_type"]);
$asset_id = intval($_POST["incident_asset"]);
$description = $mysqli->real_escape_string($_POST["description"]);
$occurrence_datetime = date("Y-m-d H:i:s");

$insertIncident = "
    INSERT INTO incident (severity_id, incident_type_id, description, occurrence_datetime)
    VALUES ($severity_id, $incident_type_id, '$description', '$occurrence_datetime')
";

$mysqli->query($insertIncident);

if ($mysqli->error) {
    die("Error inserting incident: " . $mysqli->error);
}

$incident_id = $mysqli->insert_id;

$insertAsset = "
    INSERT INTO incident_asset (asset_id, incident_id)
    VALUES ($asset_id, $incident_id)
";

$mysqli->query($insertAsset);

if ($mysqli->error) {
    die("Error linking asset: " . $mysqli->error);
}

if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {

    $uploadDir = "../../includes/images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES["image"]["name"]);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {

        $insertImage = "
            INSERT INTO incident_evidence (incident_id, file_path, file_name)
            VALUES ($incident_id, '$uploadDir', '$filename')
        ";

        $mysqli->query($insertImage);
    } else {
        echo "Warning: Failed to upload file.";
    }
}

echo "<script>
        alert('Incident created successfully!');
        window.location.href = 'incident_form.php';
      </script>";
exit;
