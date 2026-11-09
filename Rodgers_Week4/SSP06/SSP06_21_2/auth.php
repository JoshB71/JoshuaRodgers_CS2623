<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    $scriptName = isset($_SERVER['SCRIPT_NAME'])
        ? $_SERVER['SCRIPT_NAME'] : '';

    $loginPath = (strpos($scriptName, '/SSP06_23_1/') !== false)
        ? '../SSP06_21_2/login.php'
        : 'login.php';

    header('Location: ' . $loginPath);
    exit;
}