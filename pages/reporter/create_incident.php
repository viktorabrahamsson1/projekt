<?php
require_once "../../includes/session.php";
require_once "../../includes/db.php";
require_once "../../auth/auth.php";
require_once "../../includes/alert.php";
requireRoles(["reporter"]);


if (
    !isset($_POST["severity"]) ||
    !isset($_POST["incident_type"]) ||
    !isset($_POST["assets"]) ||
    !isset($_POST["description"])
) {
    setAlert("All fields besides photo evidence needs to be filled", "error", "incident_form.php");
}

$reported_by = $_SESSION["user_id"];

$severity_id = intval($_POST["severity"]);
$incident_type_id = intval($_POST["incident_type"]);
$selectedAssets = array_map('intval', $_POST["assets"]);
$description = $mysqli->real_escape_string($_POST["description"]);
$occurrence_datetime = date(
    'Y-m-d H:i:s',
    strtotime($_POST['occurrence_datetime'])
);

$insertIncident = "
    INSERT INTO incident (severity_id, incident_type_id, reported_by, description, occurrence_datetime)
    VALUES ($severity_id, $incident_type_id, $reported_by, '$description', '$occurrence_datetime')
";

$mysqli->query($insertIncident);

if ($mysqli->error) {
    die("Error inserting incident: " . $mysqli->error);
}

$incident_id = $mysqli->insert_id;

foreach ($selectedAssets as $asset) {

    $asset = intval($asset);

    $check = $mysqli->query("
        SELECT 1 FROM incident_asset 
        WHERE asset_id = $asset AND incident_id = $incident_id
    ");

    if ($check->num_rows > 0) {
        continue;
    }

    $insertAsset = "
        INSERT INTO incident_asset (asset_id, incident_id)
        VALUES ($asset, $incident_id)
    ";

    $mysqli->query($insertAsset);

    if ($mysqli->error) {
        die("Error linking asset: " . $mysqli->error);
    }
}

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {

    $uploadDir = "../../includes/images/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES["image"]["name"]);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {

        $insertImage = "
            INSERT INTO incident_evidence (incident_id, file_path, file_name)
            VALUES ($incident_id, 'images/$filename', '$filename')
        ";

        $mysqli->query($insertImage);
    } else {
        setAlert("Image could not be uploaded", "error", "incident_form.php");
    }
}

$insert_status = "
INSERT INTO incident_status (user_id, incident_id, status_id)
VALUES ($reported_by, $incident_id, 1)
";

$mysqli->query($insert_status);

if ($mysqli->error) {
    die("Error inserting incident: " . $mysqli->error);
}

setAlert("Incident was successfully created by $reported_by", "success", "incident_form.php");
exit;
