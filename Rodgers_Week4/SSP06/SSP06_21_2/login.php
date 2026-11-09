<?php
session_start();

// Demo users with securely hashed passwords.
$users = [
    'admin' => password_hash('secret123', PASSWORD_DEFAULT),
    'jsmith' => password_hash('php4life!', PASSWORD_DEFAULT)
];

$username = (string)(isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)(isset($_POST['username']) ? $_POST['username'] : ''));
    $password = (string)(isset($_POST['password']) ? $_POST['password'] : '');

    // Unlock after five minutes.
    if (isset($_SESSION['locked_until']) &&
        time() >= $_SESSION['locked_until']) {
        $_SESSION['attempts'] = 0;
        unset($_SESSION['locked_until']);
    }

    if (isset($_SESSION['locked_until']) &&
        time() < $_SESSION['locked_until']) {
        $error = 'Too many attempts. Try again in five minutes.';
    } elseif (
        isset($users[$username]) &&
        password_verify($password, $users[$username])
    ) {
        session_regenerate_id(true);

        $_SESSION['user'] = $username;
        $_SESSION['login_at'] = time();
        $_SESSION['attempts'] = 0;
        unset($_SESSION['locked_until']);

        if (isset($_POST['remember'])) {
            setcookie('remember_username', $username, time() + 2592000, '/', '', false, true);
        } else {
            setcookie('remember_username', '', time() - 3600, '/', '', false, true);
        }

        header('Location: dashboard.php');
        exit;
    } else {
        $_SESSION['attempts'] = (isset($_SESSION['attempts']) ? $_SESSION['attempts'] : 0) + 1;

        if ($_SESSION['attempts'] >= 3) {
            $_SESSION['locked_until'] = time() + 300;
            $error = 'Account locked after 3 failed attempts.';
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSP06 - Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post">
        <p>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"
                autocomplete="username"
                value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>">
        </p>

        <p>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"
                autocomplete="current-password">
        </p>

        <p>
            <label>
                <input type="checkbox" name="remember" value="1">
                Remember me for 30 days
            </label>
        </p>

        <button type="submit">Log In</button>
    </form>
</body>
</html>
