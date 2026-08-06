<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$full_name = '';
$email = '';
$phone = '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $full_name = cleanInput($_POST['full_name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($full_name === '') {
        $errors[] = 'Full name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Phone number must contain exactly 10 digits.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must contain at least 8 characters.';
    }

    if ($confirm_password === '') {
        $errors[] = 'Please confirm your password.';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
    $checkStatement = $pdo->prepare(
        "SELECT id FROM users WHERE email = :email LIMIT 1"
    );

    $checkStatement->execute([
        'email' => $email
    ]);

    if ($checkStatement->fetch()) {
        $errors[] = 'This email is already registered.';
    } else {
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $insertStatement = $pdo->prepare(
            "INSERT INTO users
            (full_name, email, phone, password)
            VALUES
            (:full_name, :email, :phone, :password)"
        );

        $insertStatement->execute([
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword
        ]);

        header('Location: login.php?registered=1');
        exit;
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>


<body class="auth-page register-page">

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-header">
            <h2>Create Your Account</h2>
            <p>Join Uraan Travel Agency and start your journey</p>
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

                <label for="full_name">Full Name</label>

                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    required
                >

            </div>

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

                <label for="phone">Phone Number</label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
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

            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="confirm_password"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

            </div>

            <button
                type="submit"
                name="register"
                class="auth-btn"
            >
                Register
            </button>

        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="login.php">Login here</a>
        </div>

    </div>

</div>

<script src="../assets/js/app.js"></script>

</body>


</body>

</html>