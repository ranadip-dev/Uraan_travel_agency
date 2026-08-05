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


if (isset($_POST['update_profile'])) {

    $fullName = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($fullName !== '') {

        $updateStatement = $pdo->prepare(
            "UPDATE users
             SET
                full_name = :full_name,
                phone = :phone
             WHERE id = :id"
        );

        $updateStatement->execute([
            'full_name' => $fullName,
            'phone' => $phone,
            'id' => $_SESSION['user_id']
        ]);

        $_SESSION['full_name'] = $fullName;

        header('Location: profile.php?updated=1');
        exit;
    }
}


$statement = $pdo->prepare(
    "SELECT
        id,
        full_name,
        email,
        phone,
        created_at
     FROM users
     WHERE id = :id
     LIMIT 1"
);

$statement->execute([
    'id' => $_SESSION['user_id']
]);

$user = $statement->fetch();

if (!$user) {
    redirect('../auth/logout.php');
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

    <title>My Profile</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body class="user-dashboard-page">

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h2>My Profile</h2>

            <p>
                Manage your personal information.
            </p>

        </div>

        <div class="dashboard-actions">

            <a
                href="dashboard.php"
                class="dashboard-btn"
            >
                Dashboard
            </a>

            <a
                href="../index.php"
                class="dashboard-btn"
            >
                Home
            </a>

        </div>

    </div>


    <?php if (isset($_GET['updated'])) { ?>

        <div class="success-message" style="font-weight:">
            Profile updated successfully.
        </div>

    <?php } ?>


    <div class="dashboard-section profile-section">

        <form method="POST" class="profile-form">

            <div class="form-group">

                <label for="full_name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    value="<?= e($user['full_name']) ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    value="<?= e($user['email']) ?>"
                    readonly
                >

                <small>
                    Email cannot be changed.
                </small>

            </div>


            <div class="form-group">

                <label for="phone">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= e($user['phone'] ?? '') ?>"
                >

            </div>


            <div class="profile-meta">

                <strong>Member Since:</strong>

                <?= e($user['created_at']) ?>

            </div>


            <div class="profile-actions">

                <button
                    type="submit"
                    name="update_profile"
                    class="primary-btn"
                >
                    Update Profile
                </button>

                <a
                    href="change-password.php"
                    class="secondary-btn"
                >
                    Change Password
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>