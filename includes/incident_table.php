<div class="incident_container">
    <div class="headers">
        <h1>Incidents</h1>
        <button id="order_by">Order By</button>
    </div>

    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Severity</th>
                    <th>Type</th>
                    <th>Affected Assets</th>
                    <th>Occurrence</th>
                    <th>Updated at</th>
                    <th>Details/Edit</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $incident->fetch_object()): ?>
                    <tr>
                        <td><?= $row->incident_id ?></td>
                        <td><?= htmlspecialchars($row->severity) ?></td>
                        <td><?= htmlspecialchars($row->incident_type) ?></td>
                        <td><?= $row->asset_count ?></td>
                        <td><?= $row->occurrence_datetime ?></td>
                        <td><?= $row->updated_at ?></td>
                        <td><button onclick="window.location.href='../../includes/incident_details.php'">Details/Edit</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>