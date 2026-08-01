<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['user_id'])) {
    redirect('../auth/login.php');
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    redirect('../user/dashboard.php');
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

    <title>Admin Dashboard</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >
</head>

<body>

    <h2>Admin Dashboard</h2>

    <p>
        Welcome,
        <strong><?= e($_SESSION['full_name']) ?></strong>
    </p>

    <p>
        <a href="enquiries.php">
            Manage Enquiries
        </a>
    </p>

    <p>
        <a href="../index.php">
            View Website
        </a>
    </p>

    <p>
        <a href="../auth/logout.php">
            Logout
        </a>
    </p>

</body>

</html>