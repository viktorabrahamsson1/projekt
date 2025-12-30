<?php
require_once "db.php";
require_once "redirect_by_role.php";

$incidentId = (int) $_POST['incident_id'];

$mysqli->begin_transaction();

try {
    $stmt = $mysqli->prepare(
        "DELETE FROM incident_comment WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident_evidence WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident_asset WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident_status WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $stmt = $mysqli->prepare(
        "DELETE FROM incident WHERE incident_id = ?"
    );
    $stmt->bind_param("i", $incidentId);
    $stmt->execute();

    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    throw $e;
}

redirectBackByRole("Incident successfuly deleted", "success");
