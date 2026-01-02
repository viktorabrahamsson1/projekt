-- ===============================
-- ROLE
-- ===============================
INSERT INTO role (role) VALUES 
('Admin'), 
('Responder'), 
('Reporter');

-- ===============================
-- USER
-- ===============================

INSERT INTO user (role_id, first_name, last_name, username, email, password_hash)
VALUES 
(1, 'Admin', 'User', 'administrator', 'admin@example.com',
'$2y$10$ErpMoCCpbYLhm78ckiGNQ.m55.ldIgqmPhO2xjsAhbjK8NOap76EO'),
(2, 'Responder', 'User', 'responder', 'responder@example.com',
'$2y$12$fOcMvz4Tg/RONpswaQtacOFcJyMPVmhRqTWk9miPZhtsEHxlrtaHu'),
(3, 'Reporter', 'User', 'reporter', 'reporter@example.com',
'$2y$12$lMzFDdLLoWRrCV0nETNqTeuICr2i9a4qOnWOXFs7gOzlN9flIhF72');

-- ===============================
-- BROWSER - PAGE_URL
-- ===============================

INSERT INTO browser (browser) VALUES 
('Chrome'),
('Firefox'),
('Edge'),
('Opera'),
('Safari'),
('Brave'),
('Unknown');

INSERT INTO page_url (page_url_id, page_url) VALUES
(1, '/index.php'),
(2, '/incident/create.php'),
(3, '/login.php'),
(4, '/main.php'),
(5, '/account/settings.php'),
(6, '/logout.php'),
(7, '/reports/view.php'),

-- ADMIN
(8, '/pages/admin/visitLogs.php'),
(9, '/pages/admin/allUsers.php'),
(10, '/pages/admin/incidents.php'),
(11, '/pages/admin/analytics.php'),
(12, '/pages/admin/add_user.php'),

-- REPORTER
(13, '/pages/reporter/incident_form.php'),
(14, '/pages/reporter/create_incident.php'),
(15, '/pages/reporter/my_incidents.php'),
(16, '/pages/reporter/add_evidence.php'),

-- RESPONDER
(17, '/pages/responder/pending_incidents.php'),
(18, '/pages/responder/assigned_incidents.php'),
(19, '/pages/responder/update_status.php'),
(20, '/pages/responder/add_comment.php');


-- ===============================
-- SEVERITY - INCIDENT TYPES - ASSETS
-- ===============================

INSERT INTO severity (severity) VALUES  
('Low'),
('Medium'), 
('High'),
('Critical');

INSERT INTO incident_type (incident_type) VALUES 
('Phishing'),
('DDoS'),
('Malware'),
('SQL Injection'),
('Brute Force'),
('Privilege Escalation');

INSERT INTO asset (asset) VALUES 
('Webserver-01'),
('Database-01'),
('LoadBalancer-01'),
('MailServer-01'),
('FileServer-01'),
('VPN-Gateway');

-- ===============================
-- INCIDENT
-- ===============================

INSERT INTO incident (severity_id, incident_type_id, reported_by, description, occurrence_datetime)
VALUES 
(2, 1, 1, 'User reported suspicious login attempts', NOW()),
(3, 2, 1, 'Major DDoS attack detected', NOW()),
(1, 3, 1, 'Malware detected on workstation', NOW());

-- ===============================
-- INCIDENT_ASSET
-- ===============================

INSERT INTO incident_asset (asset_id, incident_id)
VALUES 
(1, 1),
(2, 2),
(6, 2),
(4, 2),
(6, 3),
(5, 3),
(3, 3);

-- ===============================
-- INCIDENT_COMMENT
-- ===============================

INSERT INTO incident_comment (incident_id, comment)
VALUES 
(1, 'Initial investigation started'),
(2, 'Traffic filtering applied'),
(3, 'Malware quarantined');

-- ===============================
-- INCIDENT_EVIDENCE
-- ===============================

INSERT INTO incident_evidence (incident_id, file_path, file_name)
VALUES 
(1, '/logs', 'login_attempts.txt'),
(2, '/logs/ddos', 'traffic_dump.pcap'),
(3, '/malware', 'infected_file.exe');

-- ===============================
-- STATUS
-- ===============================

INSERT INTO status (status) VALUES 
('Pending'), 
('In Progress'), 
('Resolved');

-- ===============================
-- INCIDENT_STATUS
-- ===============================

INSERT INTO incident_status (user_id, incident_id, status_id)
VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1);

