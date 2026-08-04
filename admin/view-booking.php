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

$bookingId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$bookingId || $bookingId <= 0) {
    exit('Invalid booking.');
}



if (isset($_POST['update_status'])) {

    $newStatus = $_POST['booking_status'] ?? '';

    $allowedStatuses = [
        'Pending',
        'Confirmed',
        'Cancelled'
    ];

    if (in_array($newStatus, $allowedStatuses, true)) {

        $updateStatement = $pdo->prepare(
            "UPDATE bookings
             SET booking_status = :booking_status
             WHERE id = :id"
        );

        $updateStatement->execute([
            'booking_status' => $newStatus,
            'id' => $bookingId
        ]);

        header(
            'Location: view-booking.php?id=' .
            $bookingId .
            '&updated=1'
        );

        exit;
    }
}



$statement = $pdo->prepare(
    "SELECT
        bookings.id,
        bookings.travel_date,
        bookings.persons,
        bookings.booking_status,
        bookings.created_at,

        users.full_name,
        users.email,
        users.phone,

        packages.title AS package_title,
        packages.location,
        packages.price,
        packages.duration_days

    FROM bookings

    INNER JOIN users
        ON bookings.user_id = users.id

    INNER JOIN packages
        ON bookings.package_id = packages.id

    WHERE bookings.id = :id

    LIMIT 1"
);

$statement->execute([
    'id' => $bookingId
]);

$booking = $statement->fetch();

if (!$booking) {
    exit('Booking not found.');
}
$status = strtolower($booking['booking_status']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Booking Details</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">

        <div>

            <h2>Booking Details</h2>

            <p>
                Booking #<?= e($booking['id']) ?>
            </p>

        </div>

        <div class="dashboard-actions">

            <a
                href="bookings.php"
                class="dashboard-btn"
            >
                Back to Bookings
            </a>

            <a
                href="dashboard.php"
                class="dashboard-btn"
            >
                Dashboard
            </a>

        </div>

    </div>


    <?php if (isset($_GET['updated'])) { ?>

        <div class="success-message">
            Booking status updated successfully.
        </div>

    <?php } ?>


    <div class="booking-details-grid">

        <div class="dashboard-section">

            <h3>Customer Information</h3>

            <div class="detail-row">
                <span>Name</span>
                <strong><?= e($booking['full_name']) ?></strong>
            </div>

            <div class="detail-row">
                <span>Email</span>
                <strong><?= e($booking['email']) ?></strong>
            </div>

            <div class="detail-row">
                <span>Phone</span>
                <strong><?= e($booking['phone'] ?? '') ?></strong>
            </div>

        </div>


        <div class="dashboard-section">

            <h3>Package Information</h3>

            <div class="detail-row">
                <span>Package</span>
                <strong><?= e($booking['package_title']) ?></strong>
            </div>

            <div class="detail-row">
                <span>Location</span>
                <strong><?= e($booking['location']) ?></strong>
            </div>

            <div class="detail-row">
                <span>Duration</span>
                <strong>
                    <?= e($booking['duration_days']) ?> Days
                </strong>
            </div>

            <div class="detail-row">
                <span>Price</span>
                <strong>
                    ₹<?= number_format((float) $booking['price'], 2) ?>
                </strong>
            </div>

        </div>

    </div>


    <div class="dashboard-section booking-summary">

        <h3>Booking Information</h3>

        <div class="detail-row">
            <span>Travel Date</span>
            <strong><?= e($booking['travel_date']) ?></strong>
        </div>

        <div class="detail-row">
            <span>Persons</span>
            <strong><?= e($booking['persons']) ?></strong>
        </div>

        <div class="detail-row">

            <span>Status</span>

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

                <strong>
                    <?= e($booking['booking_status']) ?>
                </strong>

            <?php } ?>

        </div>

        <div class="detail-row">
            <span>Booked On</span>
            <strong><?= e($booking['created_at']) ?></strong>
        </div>

    </div>


    <div class="dashboard-section status-update-section">

        <h3>Update Booking Status</h3>

        <form
            method="POST"
            class="status-form">

            <select
                name="booking_status"
                required>

                <option
                    value="Pending"
                    <?= $booking['booking_status'] === 'Pending'
                        ? 'selected'
                        : '' ?>>
                    Pending
                </option>

                <option
                    value="Confirmed"
                    <?= $booking['booking_status'] === 'Confirmed'
                        ? 'selected'
                        : '' ?>>
                    Confirmed
                </option>

                <option
                    value="Cancelled"
                    <?= $booking['booking_status'] === 'Cancelled'
                        ? 'selected'
                        : '' ?>>
                    Cancelled
                </option>

            </select>

            <button
                type="submit"
                name="update_status"
                class="primary-btn">
                Update Status
            </button>

        </form>

    </div>

</div>


</body>

</html>