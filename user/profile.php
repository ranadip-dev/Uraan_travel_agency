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

if (isset($_POST['update_profile'])) {

    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);

    $updateStatement = $pdo->prepare(
    "UPDATE users
     SET
        full_name = :full_name,
        phone = :phone
     WHERE id = :id"
);

    $updateStatement->execute([
    'full_name' => $fullName,
    'phone'     => $phone,
    'id'        => $_SESSION['user_id']
]);
header('Location: profile.php?updated=1');
exit;
}

$statement = $pdo->prepare("
    SELECT id, full_name, email, phone, created_at
    FROM users
    WHERE id = :id
    LIMIT 1
");

$statement->execute([
    'id' => $_SESSION['user_id']
]);

$user = $statement->fetch();

if (!$user) {
    redirect('../auth/logout.php');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
</head>

<body>

<h2>My Profile</h2>

<?php

if (isset($_GET['updated'])) {

?>

<p style="color: green; font-weight: bold;">
    ✅ Profile updated successfully.
</p>

<?php

}

?>

<hr>

<form method="POST">

    <label>Full Name</label><br>
    <input
        type="text"
        name="full_name"
        value="<?= e($user['full_name']) ?>"
        required
    >

    <br><br>

    <label>Email</label><br>
    <input
        type="email"
        value="<?= e($user['email']) ?>"
        readonly
    >

    <br><br>

    <label>Phone</label><br>
    <input
        type="text"
        name="phone"
        value="<?= e($user['phone']) ?>"
    >

    <br><br>
    <p><strong>Member Since:</strong> <?= e($user['created_at']) ?></p>

    <br><br>

    <button type="submit" name="update_profile">
        Update Profile
    </button>

</form>

<br>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>