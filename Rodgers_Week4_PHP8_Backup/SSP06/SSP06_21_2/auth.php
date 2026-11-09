<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Redirect visitors who are not logged in.
if (!isset($_SESSION['user'])) {
    $loginPath = str_contains(
        $_SERVER['SCRIPT_NAME'] ?? '',
        '/SSP06_23_1/'
    ) ? '../SSP06_21_2/login.php' : 'login.php';

    header('Location: ' . $loginPath);
    exit;
}
