<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$email = '';
$errors = [];

if (isset($_SESSION['user_id'])) {
    redirect('../user/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $statement = $pdo->prepare(
            "SELECT id, full_name, email, password, role, status
             FROM users
             WHERE email = :email
             LIMIT 1"
        );

        $statement->execute([
            'email' => $email
        ]);

        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } elseif ($user['status'] !== 'active') {
            $errors[] = 'Your account is currently inactive.';
        } else {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                redirect('../admin/dashboard.php');
            }

            redirect('../user/dashboard.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>User Login</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="auth-page login-page">

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-header">
            <h2>Login to Your Account</h2>
            <p>Welcome back to Uraan Travel Agency</p>
        </div>

        <?php if (!empty($errors)) { ?>

            <div class="error-message">
                <?php foreach ($errors as $error) { ?>
                    <p><?= e($error) ?></p>
                <?php } ?>
            </div>

        <?php } ?>

        <form method="POST" class="auth-form">

            <div class="form-group">

                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">Password</label>

                <div class="password-field">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="password"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

            </div>

            <button
                type="submit"
                name="login"
                class="auth-btn"
            >
                Login
            </button>

        </form>

        <div class="auth-footer">
            Don't have an account?
            <a href="register.php">Register here</a>
        </div>

    </div>

</div>

<script src="../assets/js/app.js"></script>

</body>

</html>