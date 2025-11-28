-- ===============================
-- VIEW: USER VISIT ACTIVITY
-- ===============================
CREATE VIEW view_user_visit_activity AS
SELECT 
    u.user_id,
    u.username,
    u.email,
    v.visit_log_id,
    v.timestamp,
    v.host_ip,
    b.browser,
    p.page_url
FROM user_visit_log uv
INNER JOIN user u ON uv.user_id = u.user_id
INNER JOIN visit_log v ON uv.visit_log_id = v.visit_log_id
INNER JOIN browser b ON v.browser_id = b.browser_id
INNER JOIN page_url p ON v.page_url_id = p.page_url_id;


-- ===============================
-- VIEW: INCIDENT WITH SEVERITY & TYPE
-- ===============================
CREATE VIEW view_incident_overview AS
SELECT 
    i.incident_id,
    s.severity,
    it.incident_type,
    i.description,
    i.occurrence_datetime,
    i.updated_at
FROM incident i
INNER JOIN severity s ON i.severity_id = s.severity_id
INNER JOIN incident_type it ON i.incident_type_id = it.incident_type_id;


-- ===============================
-- VIEW: INCIDENT ASSETS (M:N)
-- ===============================
CREATE VIEW view_incident_assets AS
SELECT
    ia.incident_id,
    a.asset
FROM incident_asset ia
INNER JOIN asset a ON ia.asset_id = a.asset_id;


-- ===============================
-- VIEW: INCIDENT COMMENTS
-- ===============================
CREATE VIEW view_incident_comments AS
SELECT 
    ic.incident_comment_id,
    ic.incident_id,
    ic.comment,
    ic.timestamp
FROM incident_comment ic;


-- ===============================
-- VIEW: INCIDENT EVIDENCE
-- ===============================
CREATE VIEW view_incident_evidence AS
SELECT
    ie.incident_evidence_id,
    ie.incident_id,
    ie.file_path,
    ie.file_name,
    ie.timestamp
FROM incident_evidence ie;


-- ===============================
-- VIEW: INCIDENT STATUS HISTORY
-- ===============================
CREATE VIEW view_incident_status_history AS
SELECT 
    ist.incident_status_id,
    ist.incident_id,
    s.status,
    ist.incident_status_timestamp,
    u.username AS updated_by
FROM incident_status ist
INNER JOIN status s ON ist.status_id = s.status_id
INNER JOIN user u ON ist.user_id = u.user_id;


-- ===============================
-- VIEW: INCIDENT LATEST STATUS
-- ===============================
CREATE VIEW view_incident_latest_status AS
SELECT 
    ish.incident_id,
    ish.status,
    ish.incident_status_timestamp,
    ish.updated_by
FROM view_incident_status_history ish
INNER JOIN (
    SELECT incident_id, MAX(incident_status_timestamp) AS max_ts
    FROM incident_status
    GROUP BY incident_id
) AS latest
ON ish.incident_id = latest.incident_id 
AND ish.incident_status_timestamp = latest.max_ts;


-- ===============================
-- SUPER VIEW: INCIDENT FULL DETAIL
-- ===============================
CREATE VIEW view_incident_full AS
SELECT
    i.incident_id,
    s.severity,
    it.incident_type,
    i.description,
    i.occurrence_datetime,
    ls.status AS latest_status,
    ls.incident_status_timestamp AS status_updated_at,
    GROUP_CONCAT(DISTINCT a.asset ORDER BY a.asset SEPARATOR ', ') AS affected_assets
FROM incident i
INNER JOIN severity s ON i.severity_id = s.severity_id
INNER JOIN incident_type it ON i.incident_type_id = it.incident_type_id
LEFT JOIN view_incident_latest_status ls ON i.incident_id = ls.incident_id
LEFT JOIN incident_asset ia ON i.incident_id = ia.incident_id
LEFT JOIN asset a ON ia.asset_id = a.asset_id
GROUP BY i.incident_id, s.severity, it.incident_type, i.description, i.occurrence_datetime, ls.status, ls.incident_status_timestamp;
