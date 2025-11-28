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
(1, 'John', 'Doe', 'jdoe', 'john@example.com', 'hash1'),
(2, 'Bob', 'Bengtsson', 'bob', 'bob@example.com', 'hash2'),
(3, 'Alice', 'Adminsson', 'alice', 'alice@example.com', 'hash3'),
(2, 'Eve', 'Larsson', 'elarsson', 'eve@example.com', 'hash4'),
(3, 'Charlie', 'Nyström', 'cny', 'charlie@example.com', 'hash5'),
(2, 'David', 'Ek', 'dek', 'david@example.com', 'hash6'),
(3, 'Mia', 'Holm', 'mholm', 'mia@example.com', 'hash7');

-- ===============================
-- BROWSER - PAGE_URL
-- ===============================

INSERT INTO browser (browser) VALUES 
('Chrome'),
('Firefox'),
('Safari'),
('Edge'),
('Opera'),
('Opera GX'),
('Brave'),
('Vivaldi'),
('Tor Browser'),
('Chromium'),
('Waterfox'),
('Pale Moon');

INSERT INTO page_url (page_url) VALUES 
('https://example.com/login'),
('https://example.com'),
('https://example.com/dashboard'),
('https://example.com/account'),
('https://example.com/logout'),
('https://intranet.local'),
('https://intranet.local/admin');

-- ===============================
-- VISIT LOG
-- ===============================

INSERT INTO visit_log (page_url_id, browser_id, host_ip)
VALUES 
(1, 1, '127.0.0.1'),
(2, 3, '192.168.1.10'),
(3, 2, '10.0.0.5'),
(4, 5, '172.16.0.22'),
(5, 7, '192.168.1.55'),
(6, 1, '10.10.10.10'),
(7, 4, '192.168.0.199');

-- ===============================
-- ASSOCIATE USER
-- ===============================

INSERT INTO user_visit_log VALUES 
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 1),
(6, 5),
(7, 7);

-- ===============================
-- SEVERITY - INCIDENT TYPES - ASSETS
-- ===============================

INSERT INTO severity (severity) VALUES 
('Pending'), 
('Medium'), 
('High'),
('Critical'),
('Info');

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
(5, 5, 'High number of failed login attempts', NOW()),
(3, 6, 'Privilege escalation attempt logged', NOW());

-- ===============================
-- INCIDENT_ASSET
-- ===============================

INSERT INTO incident_asset (asset_id, incident_id)
VALUES 
(1, 1),
(2, 2),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(1, 6),
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
('Investigating'), 
('Closed');

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
