-- ===============================
-- BASIC TABLES
-- ===============================

CREATE TABLE role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE browser (
    browser_id INT AUTO_INCREMENT PRIMARY KEY,
    browser VARCHAR(100) NOT NULL
);

CREATE TABLE page_url (
    page_url_id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(255) NOT NULL
);

-- ===============================
-- VISIT LOG
-- ===============================

CREATE TABLE visit_log (
    visit_log_id INT AUTO_INCREMENT PRIMARY KEY,
    page_url_id INT NOT NULL,
    browser_id INT NOT NULL,
    host_ip VARCHAR(45),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (page_url_id) REFERENCES page_url(page_url_id),
    FOREIGN KEY (browser_id) REFERENCES browser(browser_id)
);

CREATE TABLE user_visit_log (
    visit_log_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (visit_log_id, user_id),

    FOREIGN KEY (visit_log_id)
        REFERENCES visit_log(visit_log_id)
        ON DELETE CASCADE,

    FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        ON DELETE CASCADE
);


-- ===============================
-- INCIDENTS
-- ===============================

CREATE TABLE severity (
    severity_id INT AUTO_INCREMENT PRIMARY KEY,
    severity VARCHAR(30) NOT NULL
);

CREATE TABLE incident_type (
    incident_type_id INT AUTO_INCREMENT PRIMARY KEY,
    incident_type VARCHAR(100)
);

CREATE TABLE asset (
    asset_id INT AUTO_INCREMENT PRIMARY KEY,
    asset VARCHAR(50) NOT NULL
);

CREATE TABLE incident (
    incident_id INT AUTO_INCREMENT PRIMARY KEY,
    severity_id INT NOT NULL,
    incident_type_id INT NOT NULL,
    reported_by INT NOT NULL,
    description TEXT,
    occurrence_datetime DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (severity_id) REFERENCES severity(severity_id),
    FOREIGN KEY (incident_type_id) REFERENCES incident_type(incident_type_id),
    FOREIGN KEY (reported_by) REFERENCES user(user_id)
);

CREATE TABLE incident_asset (
    asset_id INT NOT NULL,
    incident_id INT NOT NULL,
    PRIMARY KEY (asset_id, incident_id),
    FOREIGN KEY (asset_id) REFERENCES asset(asset_id),
    FOREIGN KEY (incident_id) REFERENCES incident(incident_id)
);

CREATE TABLE incident_comment (
    incident_comment_id INT AUTO_INCREMENT PRIMARY KEY,
    incident_id INT NOT NULL,
    comment TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (incident_id) REFERENCES incident(incident_id)
);

CREATE TABLE incident_evidence (
    incident_evidence_id INT AUTO_INCREMENT PRIMARY KEY,
    incident_id INT NOT NULL,
    file_path VARCHAR(255),
    file_name VARCHAR(255),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (incident_id) REFERENCES incident(incident_id)
);

CREATE TABLE status (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(30) NOT NULL
);

CREATE TABLE incident_status (
    incident_status_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    incident_id INT NOT NULL,
    status_id INT NOT NULL,
    incident_status_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (incident_id)
        REFERENCES incident(incident_id),

    FOREIGN KEY (status_id)
        REFERENCES status(status_id)
);
