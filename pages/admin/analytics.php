<?php
require_once "../../includes/session.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);
require_once "../../includes/db.php";

// ------------ Fetch Data ------------

// Severity
$q_severity = "
    SELECT s.severity, COUNT(i.incident_id) AS total
    FROM incident i
    JOIN severity s ON i.severity_id = s.severity_id
    GROUP BY s.severity
    ORDER BY total DESC";
$severityData = $mysqli->query($q_severity);

// Type
$q_types = "
    SELECT t.incident_type, COUNT(i.incident_id) AS total
    FROM incident i
    JOIN incident_type t ON i.incident_type_id = t.incident_type_id
    GROUP BY t.incident_type
    ORDER BY total DESC";
$typeData = $mysqli->query($q_types);

// Status
$q_status = "
    SELECT st.status, COUNT(isx.status_id) AS total
    FROM incident_status isx
    JOIN status st ON isx.status_id = st.status_id
    GROUP BY st.status
    ORDER BY total DESC";
$statusData = $mysqli->query($q_status);

// Daily
$q_daily = "
    SELECT DATE(occurrence_datetime) AS day, COUNT(*) AS total
    FROM incident
    GROUP BY DATE(occurrence_datetime)
    ORDER BY day ASC";
$dailyData = $mysqli->query($q_daily);

// Convert MySQL results → arrays for JavaScript
function resultToArray($result) {
    $labels = [];
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row[array_keys($row)[0]];
        $values[] = $row["total"];
    }
    return [$labels, $values];
}

list($sevLabels, $sevValues) = resultToArray($severityData);
list($typeLabels, $typeValues) = resultToArray($typeData);
list($statusLabels, $statusValues) = resultToArray($statusData);
list($dayLabels, $dayValues) = resultToArray($dailyData);

ob_start();
?>

<link rel="stylesheet" href="../../css/analytics.css">

<section class="analytics-wrapper">

    <h2 class="analytics-title">Incident Analytics Dashboard</h2>

    <!-- Toggle Button -->
    <button id="toggleViewBtn" class="toggle-btn">Show Tables</button>

    <!-- CHART VIEW -->
    <div id="chartsView" class="analytics-grid">

        <div class="analytic-card">
            <h3>Incidents by Severity</h3>
            <canvas id="severityChart"></canvas>
        </div>

        <div class="analytic-card">
            <h3>Incidents by Type</h3>
            <canvas id="typeChart"></canvas>
        </div>

        <div class="analytic-card">
            <h3>Incidents by Status</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <div class="analytic-card">
            <h3>Incidents Over Time</h3>
            <canvas id="dailyChart"></canvas>
        </div>

    </div>

    <!-- TABLE VIEW -->
    <div id="tablesView" class="analytics-grid hidden">

        <div class="analytic-card">
            <h3>Incidents by Severity</h3>
            <table class="mini-table">
                <tr><th>Severity</th><th>Total</th></tr>
                <?php foreach ($sevLabels as $i => $label): ?>
                <tr><td><?= $label ?></td><td><?= $sevValues[$i] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="analytic-card">
            <h3>Incidents by Type</h3>
            <table class="mini-table">
                <tr><th>Type</th><th>Total</th></tr>
                <?php foreach ($typeLabels as $i => $label): ?>
                <tr><td><?= $label ?></td><td><?= $typeValues[$i] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="analytic-card">
            <h3>Incidents by Status</h3>
            <table class="mini-table">
                <tr><th>Status</th><th>Total</th></tr>
                <?php foreach ($statusLabels as $i => $label): ?>
                <tr><td><?= $label ?></td><td><?= $statusValues[$i] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="analytic-card">
            <h3>Incidents Over Time</h3>
            <table class="mini-table">
                <tr><th>Date</th><th>Total</th></tr>
                <?php foreach ($dayLabels as $i => $label): ?>
                <tr><td><?= $label ?></td><td><?= $dayValues[$i] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Prepare chart data
const severityLabels = <?= json_encode($sevLabels) ?>;
const severityValues = <?= json_encode($sevValues) ?>;

const typeLabels = <?= json_encode($typeLabels) ?>;
const typeValues = <?= json_encode($typeValues) ?>;

const statusLabels = <?= json_encode($statusLabels) ?>;
const statusValues = <?= json_encode($statusValues) ?>;

const dayLabels = <?= json_encode($dayLabels) ?>;
const dayValues = <?= json_encode($dayValues) ?>;

// Chart function
function makeChart(id, labels, values, color) {
    new Chart(document.getElementById(id), {
        type: "bar",
        data: {
            labels,
            datasets: [{
                label: "Incidents",
                data: values,
                backgroundColor: color,
                borderRadius: 6
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
}

makeChart("severityChart", severityLabels, severityValues, "#f7c97f");
makeChart("typeChart", typeLabels, typeValues, "#89d4f5");
makeChart("statusChart", statusLabels, statusValues, "#c9a9f5");
makeChart("dailyChart", dayLabels, dayValues, "#f59393");

// Toggle button
document.getElementById("toggleViewBtn").onclick = function () {
    const charts = document.getElementById("chartsView");
    const tables = document.getElementById("tablesView");

    charts.classList.toggle("hidden");
    tables.classList.toggle("hidden");

    this.textContent = charts.classList.contains("hidden")
        ? "Show Charts"
        : "Show Tables";
};
</script>

<?php
$content = ob_get_clean();
require "../../includes/layout.php";
?>
