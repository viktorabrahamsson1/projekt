<?php

function setAlert(string $message, string $type): void
{
  $_SESSION["alert"] = [
    'message' => $message,
    'type' => $type
  ];
}


?>