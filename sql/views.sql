/* ============================================================
   VIEW 1 — Incidenter kopplade till ansvariga användare
   Beskrivning:
   Visar alla incidenter och vilka användare som har en status
   associerad till incidenten (t.ex. Pending, Investigating).
   Hjälper dig verifiera FK mellan incident → incident_status → user
   ============================================================ */
CREATE VIEW v_incident_with_reporter AS
SELECT 
    i.incident_id,
    i.description,
    u.user_id,
    u.first_name,
    u.last_name,
    s.status AS incident_status
FROM incident i
JOIN incident_status ist ON i.incident_id = ist.incident_id
JOIN user u ON ist.user_id = u.user_id
JOIN status s ON ist.status_id = s.status_id;


/* ============================================================
   VIEW 2 — Incidenter med kommentarer
   Beskrivning:
   Visar incidenter tillsammans med sina kommentarer.
   Används för att validera incident_comment och FK till incident.
   ============================================================ */
CREATE VIEW v_incident_comments AS
SELECT
    i.incident_id,
    i.description,
    ic.comment,
    i.occurrence_datetime
FROM incident i
LEFT JOIN incident_comment ic 
    ON i.incident_id = ic.incident_id
ORDER BY i.incident_id;


/* ============================================================
   VIEW 3 — Incidenter med severity och incidenttyp
   Beskrivning:
   Samlar alla incidenter med deras severity-nivå (Low–Critical)
   samt incidenttyp (Phishing, Malware, etc.)
   Bra för att säkerställa korrekta kategoriseringar.
   ============================================================ */
CREATE VIEW v_incident_severity_type AS
SELECT
    i.incident_id,
    i.description,
    s.severity,
    t.incident_type,
    i.occurrence_datetime
FROM incident i
JOIN severity s ON i.severity_id = s.severity_id
JOIN incident_type t ON i.incident_type_id = t.incident_type_id;


/* ============================================================
   VIEW 4 — Besöksloggar tillsammans med användarnamn
   Beskrivning:
   Kombinerar visit_log → user_visit_log → user
   och visar besökta sidor, webbläsare och ev. användare.
   Validerar relationen i analytics-delen av databasen.
   ============================================================ */
CREATE VIEW v_visit_log_with_users AS
SELECT
    v.visit_log_id,
    u.user_id,
    u.first_name,
    u.last_name,
    p.page_url,
    b.browser,
    v.host_ip
FROM visit_log v
LEFT JOIN user_visit_log uv 
    ON v.visit_log_id = uv.visit_log_id
LEFT JOIN user u 
    ON uv.user_id = u.user_id
JOIN page_url p 
    ON v.page_url_id = p.page_url_id
JOIN browser b 
    ON v.browser_id = b.browser_id;


/* ============================================================
   VIEW 5 — Aggregat: antal incidenter per typ och severity
   Beskrivning:
   Räknar hur många incidenter som finns per incidenttyp
   och severity-nivå. Användbar för dashboards/statistik.
   ============================================================ */
CREATE VIEW v_incident_counts AS
SELECT
    t.incident_type,
    s.severity,
    COUNT(*) AS incident_count
FROM incident i
JOIN incident_type t ON i.incident_type_id = t.incident_type_id
JOIN severity s ON i.severity_id = s.severity_id
GROUP BY t.incident_type, s.severity;


/* ============================================================
   VIEW 6 — Page visit summary
   Beskrivning:
   Summerar antal gånger varje URL besökts.
   Validerar att page_url och visit_log fungerar korrekt.
   ============================================================ */
CREATE VIEW v_page_visit_summary AS
SELECT
    p.page_url,
    COUNT(*) AS visit_count
FROM visit_log v
JOIN page_url p ON v.page_url_id = p.page_url_id
GROUP BY p.page_url
ORDER BY visit_count DESC;
