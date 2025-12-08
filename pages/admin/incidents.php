<?php
require_once "../../includes/session.php";
require_once "../../includes/db.php";
require_once "../../auth/auth.php";
requireRoles(["admin"]);

$incident_query = "SELECT * FROM incident";
$incident = $mysqli->query($incident_query);

$incident_row = "";

while ($row = $incident->fetch_object()) {

  $severity_result = $mysqli->query("SELECT severity FROM severity WHERE severity_id = {$row->severity_id}");
  $severity = $severity_result->fetch_object()->severity;

  $incident_type_result = $mysqli->query("SELECT incident_type FROM incident_type WHERE incident_type_id = {$row->incident_type_id}");
  $incident_type = $incident_type_result->fetch_object()->incident_type;

  $incident_asset_result = $mysqli->query("SELECT asset_id FROM incident_asset WHERE incident_id = {$row->incident_id}");
  $incident_asset_id = $incident_asset_result->fetch_object()->asset_id;

  $asset_result = $mysqli->query("SELECT asset FROM asset WHERE asset_id = {$incident_asset_id}");
  $asset = $asset_result->fetch_object()->asset;

  $incident_row .= "
        <tr>
          <td>{$row->incident_id}</td>
          <td>{$severity}</td>
          <td>{$incident_type}</td>
          <td>{$asset}</td>
          <td>{$row->occurrence_datetime}</td>
          <td>{$row->updated_at}</td>
          <td>
            <button>Details/Edit</button>
          </td>
        </tr>";
}

$content = <<<HTML
<div class="incident_container">
  <div class="headers">
    <h1 style="margin-left: 45%;">Incidents</h1>
    <button id="order_by">Order By</button>
  </div>
  <div class="table_container">
    <table>
      <tbody>
        <tr>
          <th>
            <h3>ID</h3>
          </th>
          <th>
            <h3>Severity</h3>
          </th>
          <th>
            <h3>Type</h3>
          </th>
          <th>
            <h3>Asset</h3>
          </th>
          <th>
            <h3>Occurrence</h3>
          </th>
          <th>
            <h3>Updated at</h3>
          </th>
          <th>
            <h3>Details/Edit</h3>
          </th>
        </tr>
        $incident_row
      </tbody>
    </table>
  </div>
</div>
HTML;

require "../../includes/layout.php";
