<?php
require __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSP06 - Profile</title>
</head>
<body>
    <h1>My Profile</h1>

    <p>Logged in as:
        <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>This is a second protected page.</p>

    <p><a href="dashboard.php">Dashboard</a></p>
    <p><a href="logout.php">Log Out</a></p>
</body>
</html>
