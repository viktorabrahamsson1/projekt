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
(1, 'Admin', 'User', 'admin', 'admin@example.com',
'$2y$10$sfoFlGLobbCkepNZXxmIjuarrwKfR9dbE3gHg.d4sm5ydWVnD9.Cm'),
(2, 'Responder', 'User', 'responder', 'responder@example.com',
'$2y$10$UFztR7Mlzg.2s92RytxZq.4Ckigcren18ARJpke63yYSVfwpmLKcK'),
(3, 'Reporter', 'User', 'reporter', 'reporter@example.com',
'$2y$10$DPtd1crzvtbwaRZDcWUte..4P4jHdmU1PyF84WW0WP.tXXWxo9lNO');

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
(4, '/dashboard.php'),
(5, '/account/settings.php'),
(6, '/logout.php'),
(7, '/reports/view.php'),
(8, '/pages/admin/visitLogs.php'),
(9, '/pages/admin/allUsers.php'),
(10, '/pages/admin/incidents.php'),
(11, '/pages/admin/analytics.php'),
(12, '/pages/admin/add_user.php');

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

INSERT INTO incident (severity_id, incident_type_id, description, occurrence_datetime)
VALUES 
(2, 1, 'User reported suspicious login attempts', NOW()),
(3, 2, 'Major DDoS attack detected', NOW()),
(1, 3, 'Malware detected on workstation', NOW()),
(4, 4, 'SQL injection attempt detected', NOW()),
(2, 5, 'High number of failed login attempts', NOW()),
(3, 6, 'Privilege escalation attempt logged', NOW());

-- ===============================
-- INCIDENT_ASSET
-- ===============================

INSERT INTO incident_asset (asset_id, incident_id)
VALUES 
(1, 1),
(2, 2),
(6, 2),
(4, 3),
(2, 5),
(6, 3),
(5, 6),
(3, 6);

-- ===============================
-- INCIDENT_COMMENT
-- ===============================

INSERT INTO incident_comment (incident_id, comment)
VALUES 
(1, 'Initial investigation started'),
(2, 'Traffic filtering applied'),
(3, 'Malware quarantined'),
(4, 'Firewall updated'),
(5, 'Password reset enforced'),
(6, 'Admin notified of escalation attempt');

-- ===============================
-- INCIDENT_EVIDENCE
-- ===============================

INSERT INTO incident_evidence (incident_id, file_path, file_name)
VALUES 
(1, '/logs', 'login_attempts.txt'),
(2, '/logs/ddos', 'traffic_dump.pcap'),
(3, '/malware', 'infected_file.exe'),
(4, '/sql', 'payload.txt'),
(5, '/auth', 'failed_logins.csv'),
(6, '/priv', 'escalation_trace.log');

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
(2, 1, 2),
(1, 1, 1),
(3, 2, 1),
(2, 2, 2),
(1, 3, 1),
(3, 3, 2),
(2, 4, 2),
(1, 5, 1),
(3, 6, 2);
