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

// Total Users
$userCountStatement = $pdo->query(
    "SELECT COUNT(*) FROM users WHERE role = 'user'"
);

$totalUsers = (int) $userCountStatement->fetchColumn();


// Total Packages
$packageCountStatement = $pdo->query(
    "SELECT COUNT(*) FROM packages"
);

$totalPackages = (int) $packageCountStatement->fetchColumn();


// Total Bookings
$bookingCountStatement = $pdo->query(
    "SELECT COUNT(*) FROM bookings"
);

$totalBookings = (int) $bookingCountStatement->fetchColumn();


// Pending Bookings
$pendingBookingStatement = $pdo->prepare(
    "SELECT COUNT(*)
     FROM bookings
     WHERE booking_status = :status"
);

$pendingBookingStatement->execute([
    'status' => 'Pending'
]);

$pendingBookings = (int) $pendingBookingStatement->fetchColumn();


// New Enquiries
$enquiryCountStatement = $pdo->prepare(
    "SELECT COUNT(*)
     FROM enquiries
     WHERE status = :status"
);

$enquiryCountStatement->execute([
    'status' => 'New'
]);

$newEnquiries = (int) $enquiryCountStatement->fetchColumn();

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

    <section class="admin-stats">

    <div class="stat-box">
        <h3><?= $totalUsers ?></h3>
        <p>Users</p>
    </div>

    <div class="stat-box">
        <h3><?= $totalPackages ?></h3>
        <p>Packages</p>
    </div>

    <div class="stat-box">
        <h3><?= $totalBookings ?></h3>
        <p>Total Bookings</p>
    </div>

    <div class="stat-box">
        <h3><?= $pendingBookings ?></h3>
        <p>Pending Bookings</p>
    </div>

    <div class="stat-box">
        <h3><?= $newEnquiries ?></h3>
        <p>New Enquiries</p>
    </div>

    <div class="dashboard-actions">

        <a href="bookings.php" class="dashboard-opt">Manage Bookings</a>

        <a href="packages.php" class="dashboard-opt">Manage Packages</a>

        <a href="users.php" class="dashboard-opt">Manage Users</a>

        <a href="enquiries.php" class="dashboard-opt">Manage Enquiries</a>


        <a href="../index.php" class="dashboard-btn">
            View Website
        </a>

        <a href="../auth/logout.php" class="dashboard-btn logout-btn">
            Logout
        </a>

    </div>


</section>

</body>

</html>