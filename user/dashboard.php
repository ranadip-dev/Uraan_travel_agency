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


/* Cancel Book */

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


/* fetch user bookking*/ 

$bookingStatement = $pdo->prepare(
    "SELECT
        bookings.id,
        packages.title,
        bookings.travel_date,
        bookings.persons,
        bookings.booking_status,
        bookings.created_at
    FROM bookings
    INNER JOIN packages
        ON bookings.package_id = packages.id
    WHERE bookings.user_id = :user_id
    ORDER BY bookings.created_at DESC"
);

$bookingStatement->execute([
    'user_id' => $_SESSION['user_id']
]);

$bookings = $bookingStatement->fetchAll();

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

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h2>User Dashboard</h2>

            <p>
                Welcome,
                <strong><?= e($_SESSION['full_name']) ?></strong>
            </p>

            <p class="dashboard-email">
                <?= e($_SESSION['email']) ?>
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
                href="../index.php"
                class="dashboard-btn"
            >
                Home
            </a>

            <a
                href="../auth/logout.php"
                class="dashboard-btn logout-btn"
            >
                Logout
            </a>

        </div>

    </div>


    <?php
    if (
        isset($_GET['booking']) &&
        $_GET['booking'] === 'success'
    ) {
    ?>

        <div class="success-message">
            Your booking has been submitted successfully.
        </div>

    <?php
    }
    ?>


    <div class="dashboard-section">

        <h3>My Bookings</h3>

        <?php if (empty($bookings)) { ?>

            <div class="empty-message">
                No bookings found.
            </div>

        <?php } else { ?>

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>
                            <th>Package</th>
                            <th>Travel Date</th>
                            <th>Persons</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    foreach ($bookings as $booking) {

                        $status = strtolower(
                            $booking['booking_status']
                        );
                    ?>

                        <tr>

                            <td>
                                <?= e($booking['title']) ?>
                            </td>

                            <td>
                                <?= e($booking['travel_date']) ?>
                            </td>

                            <td>
                                <?= e($booking['persons']) ?>
                            </td>

                            <td>

                                <?php if ($status === 'pending') { ?>

                                    <span class="status status-pending">
                                        Pending
                                    </span>

                                <?php } elseif ($status === 'confirmed') { ?>

                                    <span class="status status-confirmed">
                                        Confirmed
                                    </span>

                                <?php } elseif ($status === 'cancelled') { ?>

                                    <span class="status status-cancelled">
                                        Cancelled
                                    </span>

                                <?php } else { ?>

                                    <?= e($booking['booking_status']) ?>

                                <?php } ?>

                            </td>

                            <td>

                                <?php if ($status === 'pending') { ?>

                                    <a
                                        href="?cancel=<?= (int) $booking['id'] ?>"
                                        class="cancel-booking"
                                        onclick="return confirm('Are you sure you want to cancel this booking?');"
                                    >
                                        Cancel
                                    </a>

                                <?php } else { ?>

                                    <span class="no-action">—</span>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        <?php } ?>

    </div>

</div>

</body>

</html>