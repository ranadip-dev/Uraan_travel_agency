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

<body>

    <h2>Create Your Account</h2>

    <?php
    if (!empty($errors)) {
        echo "<ul style='color:red;'>";

        foreach ($errors as $error) {
            echo "<li>" . e($error) . "</li>";
        }

        echo "</ul>";
    }
    ?>

    <form action="" method="POST">

        <label>Full Name</label><br>
        <input
            type="text"
            name="full_name"
            value="<?= e($full_name) ?>"
            required>

        <br><br>

        <label>Email</label><br>
        <input
            type="email"
            name="email"
            value="<?= e($email) ?>"
            required>

        <br><br>

        <label>Phone Number</label><br>
        <input
            type="text"
            name="phone"
            value="<?= e($phone) ?>"
            required>

        <br><br>

        <label>Password</label><br>
        <input
            type="password"
            name="password"
            required>

        <br><br>

        <label>Confirm Password</label><br>
        <input
            type="password"
            name="confirm_password"
            required>

        <br><br>

        <button type="submit">
            Register
        </button>

    </form>

</body>

</html>