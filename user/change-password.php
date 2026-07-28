<?php

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

    $currentPassword = $_POST['current_password'] ?? '';
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

        if (!$user || !password_verify($currentPassword, $user['password'])) {

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
<html>
    <head>
        <title>Change Password</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
        <h2>Change Password</h2>

        <?php

if (!empty($errors)) {

    foreach ($errors as $error) {

?>

<p style="color: red;">
    <?= e($error) ?>
</p>

<?php

    }
}

if ($success !== '') {

?>

<p style="color: green; font-weight: bold;">
    ✅ <?= e($success) ?>
</p>

<?php

}

?>
        <hr>
        <form method="POST">
            <label>Current Password</label><br>
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
            <br><br>
            <label>New Password</label><br>
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
            </div>
            <br><br>
            <label>Confirm Password</label><br>
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
            <br><br>
            <button type="submit" name="change_password">Change Password</button>
        </form>
        <br>
        <a href="profile.php">Back to Profile</a>
        <script src="../assets/js/app.js"></script>
    </body>
</html>