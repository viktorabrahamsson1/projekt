<?php
require_once "redirect_by_role.php";
require_once "db.php";
require_once "alert.php";

$incidentId = (int) $_POST['incident_id'];

if (isset($_POST['add_comment'])) {
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {

        $sql = "
            INSERT INTO incident_comment (incident_id, comment)
            VALUES (?, ?)
        ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("is", $incidentId, $comment);
        $stmt->execute();
        $stmt->close();
    }

    setAlert("You have successfully added a comment", "success", "incident_details.php?id=" . $incidentId);
    exit;
}
