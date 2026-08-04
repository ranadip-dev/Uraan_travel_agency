<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../auth/login.php');
}

if (($_SESSION['role'] ?? '') !== 'user') {
    redirect('../auth/login.php');
}

$errors = [];
$success = '';

if (isset($_POST['change_password'])) {

    
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($currentPassword === '') {
        $errors[] = 'Current password is required.';
    }

    if (strlen($newPassword) < 6) {
        $errors[] = 'New password must be at least 6 characters.';
    }

    if ($newPassword !== $confirmPassword) {
        $errors[] = 'New password and confirm password do not match.';
    }

    if (empty($errors)) {

        $statement = $pdo->prepare(
            "SELECT password
             FROM users
             WHERE id = :id
             LIMIT 1"
        );

        $statement->execute([
            'id' => $_SESSION['user_id']
        ]);

        $user = $statement->fetch();

        if (
            !$user ||
            !password_verify(
                $currentPassword,
                $user['password']
            )
        ) {

            $errors[] = 'Current password is incorrect.';

        } else {

            $newPasswordHash = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

            $updateStatement = $pdo->prepare(
                "UPDATE users
                 SET password = :password
                 WHERE id = :id"
            );

            $updateStatement->execute([
                'password' => $newPasswordHash,
                'id' => $_SESSION['user_id']
            ]);

            $success = 'Password changed successfully.';
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

    <title>Change Password</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h2>Change Password</h2>

            <p>
                Update your account password securely.
            </p>

        </div>

        <div class="dashboard-actions">

            <a
                href="profile.php"
                class="dashboard-btn"
            >
                My Profile
            </a>

            <a
                href="dashboard.php"
                class="dashboard-btn"
            >
                Dashboard
            </a>

        </div>

    </div>


    <?php if (!empty($errors)) { ?>

        <div class="error-message">

            <?php foreach ($errors as $error) { ?>

                <p>
                    <?= e($error) ?>
                </p>

            <?php } ?>

        </div>

    <?php } ?>


    <?php if ($success !== '') { ?>

        <div class="success-message">
            <?= e($success) ?>
        </div>

    <?php } ?>


    <div class="dashboard-section profile-section">

        <form method="POST" class="profile-form">

            <div class="form-group">

                <label for="current_password">
                    Current Password
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="current_password"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

            </div>


            <div class="form-group">

                <label for="new_password">
                    New Password
                </label>

                <div class="password-field">

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-target="new_password"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

                <small>
                    Minimum 6 characters.
                </small>

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
                name="change_password"
                class="primary-btn"
            >
                Change Password
            </button>

        </form>

    </div>

</div>

<script src="../assets/js/app.js"></script>

</body>

</html>