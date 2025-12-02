<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report</title>
    <link rel="stylesheet" href="/css/incident_form.css" />
    <link rel="stylesheet" href="/css/global.css" />
</head>

<body>
    <div class="report_container">
        <h1>Incident Report Form</h1>
        <div class="form_container">
            <form>
                <label for="sevarity">Sevarity</label>
                <select name="sevarity" id="severity" required>
                    <option value="">Choose Severity-level</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>

                <label for="incident_type">Incident Type</label>
                <select name="incident_type" id="incident_type" required>
                    <option value="">Choose Incident-Type</option>
                    <option value="phishing">Phishing</option>
                    <option value="DDoS">DDoS</option>
                    <option value="malware">Malware</option>
                    <option value="SQL_injection">SQL Injection</option>
                    <option value="brute_force">Brute Force</option>
                    <option value="privilege_escalation">Privilege Escalation</option>
                </select>

                <label for="incident_asset">Incident Asset</label>
                <select name="incident_asset" id="incident_asset" required>
                    <option value="">Choose Incident-Asset</option>
                    <option value="Webserver-01">Webserver-01</option>
                    <option value="Database-01">Database-01</option>
                    <option value="LoadBalancer-01">Malware</option>
                    <option value="MailServer-01">MailServer-01</option>
                    <option value="FileServer-01">FileServer-01</option>
                    <option value="VPN-Gateway">VPN-Gateway</option>
                </select>

                <label for="image">Upload image of incident</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5" cols="40" placeholder="Write your description here"></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>