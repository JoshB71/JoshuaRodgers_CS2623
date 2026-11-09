<?php
declare(strict_types=1);

require __DIR__ . '/../SSP06_21_2/auth.php';

// Empty the shopping cart
unset($_SESSION['cart']);

// Return to the shopping cart page
header('Location: index.php');
exit;
