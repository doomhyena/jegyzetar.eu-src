
<?php
    require_once __DIR__ . '/functions.php';

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    auth_logout();
    header('Location: ../../index.php');
    exit;
?>
