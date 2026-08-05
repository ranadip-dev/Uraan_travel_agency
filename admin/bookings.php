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


/*
|--------------------------------------------------------------------------
| Fetch All Bookings
|--------------------------------------------------------------------------
*/

$statement = $pdo->prepare(
    "SELECT
        bookings.id,
        bookings.travel_date,
        bookings.persons,
        bookings.booking_status,
        bookings.created_at,
        users.full_name,
        users.email,
        packages.title AS package_title

     FROM bookings

     INNER JOIN users
        ON bookings.user_id = users.id

     INNER JOIN packages
        ON bookings.package_id = packages.id

     ORDER BY bookings.created_at DESC"
);

$statement->execute();

$bookings = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Bookings</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body class="admin-dashboard-page">

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h2>Manage Bookings</h2>

            <p>
                View and manage customer bookings.
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
                View Website
            </a>

        </div>

    </div>


    <div class="dashboard-section">

        <?php if (empty($bookings)) { ?>

            <div class="empty-message">
                No bookings found.
            </div>

        <?php } else { ?>

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Package</th>
                            <th>Travel Date</th>
                            <th>Persons</th>
                            <th>Status</th>
                            <th>Booked On</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($bookings as $booking) {

                        $status = strtolower(
                            $booking['booking_status']
                        );

                    ?>

                        <tr>

                            <td>
                                <?= e($booking['id']) ?>
                            </td>

                            <td>
                                <?= e($booking['full_name']) ?>
                            </td>

                            <td>
                                <?= e($booking['email']) ?>
                            </td>

                            <td>
                                <?= e($booking['package_title']) ?>
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
                                <?= e($booking['created_at']) ?>
                            </td>

                            <td>

                                <a
                                    href="view-booking.php?id=<?= (int) $booking['id'] ?>"
                                    class="view-link"
                                >
                                    View
                                </a>

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