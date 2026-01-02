<?php
require_once "../../includes/session.php";
require_once "../../includes/db.php";
require_once "../../auth/auth.php";
requireRoles(["reporter"]);

$reported_by = $_SESSION["user_id"];

$sql = "
    SELECT 
        i.incident_id,
        u.user_id,
        s.severity,
        it.incident_type,
        COUNT(ia.asset_id) AS asset_count,
        i.occurrence_datetime,
        i.updated_at
    FROM incident i
    JOIN severity s ON s.severity_id = i.severity_id
    JOIN incident_type it ON it.incident_type_id = i.incident_type_id
    JOIN user u ON u.user_id = i.reported_by 
    LEFT JOIN incident_asset ia ON ia.incident_id = i.incident_id
    WHERE reported_by = $reported_by
    GROUP BY i.incident_id
";

$incident = $mysqli->query($sql);

ob_start();
include "../../includes/incident_table.php";
$content = ob_get_clean();

require "../../includes/layout.php";
