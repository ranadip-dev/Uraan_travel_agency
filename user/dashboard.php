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

$bookingStatement = $pdo->prepare(
    "SELECT bookings.id, packages.title, bookings.travel_date, bookings.persons, bookings.booking_status, bookings.created_at
    FROM bookings
    INNER JOIN packages
    ON bookings.package_id = packages.id
    Where bookings.user_id = :user_id
    ORDER BY bookings.created_at DESC"
);

$bookingStatement->execute([
    'user_id' => $_SESSION['user_id']
]);

$bookings = $bookingStatement->fetchAll();

echo '<pre>';
print_r($bookings);
echo '</pre>';

if (isset($_GET['cancel'])) {

    $bookingId = (int) $_GET['cancel'];

    $cancelStatement = $pdo->prepare(
        "UPDATE bookings
         SET booking_status = 'Cancelled'
         WHERE id = :id
         AND user_id = :user_id
         AND booking_status = 'Pending'"
    );

    $cancelStatement->execute([
        'id' => $bookingId,
        'user_id' => $_SESSION['user_id']
    ]);

    header('Location: dashboard.php');
    exit;
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

    <title>User Dashboard</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <h2>User Dashboard</h2>

    <p>
        Welcome,
        <strong><?= e($_SESSION['full_name']) ?></strong>
    </p>

    <?php if (isset($_GET['booking']) && $_GET['booking'] === 'success'); ?>
    <p style="color: green;">
        Your booking has been submitted successfully.
    </p>

    <p>
        Email:
        <?= e($_SESSION['email']) ?>
    </p>

    <hr>
    <h3>My bookings</h3>
    <?php
    if (empty($bookings)){
    ?>
    <p>No booking found.</p>
    <?php
    } else {
    ?>
    <table border="1" cellpadding="10" cellspaceing="0">
        <tr>
            <th>Package</th>
            <th>Travel Date</th>
            <th>Persons</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($bookings as $booking) {
        ?>

        <tr>
            <td><?= e($booking['title']) ?></td>
            <td><?= e($booking['travel_date']) ?></td>
            <td><?= e((string)$booking['persons']) ?></td>
    
            <td>
                <?php
                $status = strtolower($booking['booking_status']);
                if ($status == 'pending'){
                ?>
                <span style="color:yellow; font-weight:bold;">
                    🟡 Pending
                </span>
                <?php
                } elseif($status == 'confirmed'){
                    ?>
                    <span style="coloe:green; font-waight:bold;">
                        🟢 Confirmed
                    </span>
                    <?php
                } elseif($status == 'cancelled'){
                    ?>
                    <span style="color:red; font-waight:bold;">
                        🔴 Cancelled
                    </span>
                    <?php
                } else {
                    ?>
                    <?= e($booking['booking_status']) ?>
                    <?php
                    }
                    ?>
            </td>

            <td>
                <?php
                $status = strtolower($booking['booking_status']);
                if ($status === 'pending') {
                    ?>
                    <a href="?cancel=<?= $booking['id'] ?>"
                    onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel Booking</a>
                    <?php
                    } else {
                        ?>
                        -
                        <?php
                        }
                        ?>
                        </td>
            
        </tr>
        <?php
        }
        ?>
    </table>
    <?php
    }
    ?>

    <p>
        <a href="../index.php" >Go to Home</a>
    </p>

    <p>
        <a href="../auth/logout.php">Logout</a>
    </p>

</body>

</html>