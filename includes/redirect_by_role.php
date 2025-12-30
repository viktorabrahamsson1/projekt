<?php
include_once "session.php";

function redirectBackByRole($message, $type)
{
    $role = $_SESSION['role'] ?? null;

    switch ($role) {
        case 'admin':
            $_SESSION["alert"] = [
                'message' => $message,
                'type' => $type
            ];
            header('Location: /pages/admin/incidents.php');
            break;

        case 'reporter':
            $_SESSION["alert"] = [
                'message' => $message,
                'type' => $type
            ];
            header('Location: /pages/reporter/my_incidents.php');
            break;

        case 'responder':
            $_SESSION["alert"] = [
                'message' => $message,
                'type' => $type
            ];
            header('Location: /pages/responder/pending_incidents.php');
            break;

        default:
            header('Location: /index.php');
    }

    exit;
}
