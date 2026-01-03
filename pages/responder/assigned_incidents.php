<?php
require_once "../../includes/session.php";
require_once "../../includes/db.php";
require_once "../../auth/auth.php";
requireRoles(["responder"]);

$userId = $_SESSION['user_id'];

$sql = "
    SELECT 
        i.incident_id,
        s.severity,
        it.incident_type,
        COUNT(ia.asset_id) AS asset_count,
        i.occurrence_datetime,
        i.updated_at
    FROM incident i
    JOIN severity s ON s.severity_id = i.severity_id
    JOIN incident_type it ON it.incident_type_id = i.incident_type_id
    LEFT JOIN incident_asset ia ON ia.incident_id = i.incident_id
    JOIN incident_status istat ON istat.incident_id = i.incident_id
    JOIN user u ON istat.user_id = u.user_id
    WHERE (istat.status_id = 2) && (u.user_id = $userId)
    GROUP BY i.incident_id
";

$incident = $mysqli->query($sql);

ob_start();
include "../../includes/incident_table.php";
$content = ob_get_clean();


require "../../includes/layout.php";
