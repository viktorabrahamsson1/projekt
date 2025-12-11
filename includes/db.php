<?php
//If you want to try yourself just enter your specific MySql and xampp info
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "incident_db";

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
