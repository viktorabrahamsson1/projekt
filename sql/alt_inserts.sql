-- ===============================
-- This insert is to make sure
-- we can create an incident manually
-- ===============================



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
-- STATUS
-- ===============================

INSERT INTO status (status) VALUES 
('Pending'), 
('Investigating'), 
('Closed');


-- ===============================
-- EXAMPLE - INCIDENT CREATION
-- ===============================

-- 1. Insert into INCIDENT
INSERT INTO incident (severity_id, incident_type_id, description, occurrence_datetime)
VALUES (3, 1, 'Multiple suspicious login attempts detected', NOW());

-- 2. Get the new incident ID
SET @incident_id = LAST_INSERT_ID();

-- 3. Link the incident to an asset
INSERT INTO incident_asset (asset_id, incident_id)
VALUES (1, @incident_id);

-- 4. Insert evidence (optional)
INSERT INTO incident_evidence (incident_id, file_path, file_name)
VALUES (@incident_id, '/uploads', 'img123.png');