<?php
include_once "session.php";

function redirectBackByRole(): void
{
    $role = $_SESSION['role'] ?? null;

    switch ($role) {
        case 'admin':
            header('Location: /pages/admin/incidents.php');
            break;

        case 'reporter':
            header('Location: /pages/reporter/my_incidents.php');
            break;

        case 'responder':
            header('Location: /pages/responder/pending_incidents.php');
            break;

        default:
            // Fallback if role is missing or invalid
            header('Location: /index.php');
    }

    exit;
}
