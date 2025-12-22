<?php

function setAlert(string $message, string $type, string $location): void
{
  $_SESSION["alert"] = [
    'message' => $message,
    'type' => $type
  ];

  header("Location: " . $location);
  exit;
}


?>