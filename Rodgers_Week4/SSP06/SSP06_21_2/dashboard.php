<?php
require __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSP06 - Dashboard</title>
</head>
<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>!</h1>
    </header>

    <p>You are successfully logged in.</p>
    <p>Logged in at:
        <?= date('g:i A', $_SESSION['login_at']) ?>
    </p>

    <nav>
        <ul>
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="../SSP06_23_1/index.php">Shopping Cart</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </nav>
</body>
</html>
