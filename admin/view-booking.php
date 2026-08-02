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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Booking Details</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

<section style="padding:30px;">

    <h2>Booking Details</h2>

    <?php

    if (isset($_GET['updated'])) {

    ?>

        <p style="color:green;font-weight:bold;">
            Booking status updated successfully.
        </p>

    <?php

    }

    ?>

    <hr>

    <h3>Customer</h3>

    <p>
        <strong>Name:</strong>
        <?= e($booking['full_name']) ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?= e($booking['email']) ?>
    </p>

    <p>
        <strong>Phone:</strong>
        <?= e($booking['phone'] ?? '') ?>
    </p>


    <h3>Package</h3>

    <p>
        <strong>Package:</strong>
        <?= e($booking['package_title']) ?>
    </p>

    <p>
        <strong>Location:</strong>
        <?= e($booking['location']) ?>
    </p>

    <p>
        <strong>Duration:</strong>
        <?= e($booking['duration_days']) ?> Days
    </p>

    <p>
        <strong>Price:</strong>
        ₹<?= number_format((float) $booking['price'], 2) ?>
    </p>


    <h3>Booking</h3>

    <p>
        <strong>Travel Date:</strong>
        <?= e($booking['travel_date']) ?>
    </p>

    <p>
        <strong>Persons:</strong>
        <?= e($booking['persons']) ?>
    </p>

    <p>
        <strong>Current Status:</strong>
        <?= e($booking['booking_status']) ?>
    </p>

    <p>
        <strong>Booked On:</strong>
        <?= e($booking['created_at']) ?>
    </p>

    <hr>

    <h3>Update Booking Status</h3>

    <form method="POST">

        <select
            name="booking_status"
            required
        >

            <option
                value="Pending"
                <?= $booking['booking_status'] === 'Pending'
                    ? 'selected'
                    : '' ?>
            >
                Pending
            </option>

            <option
                value="Confirmed"
                <?= $booking['booking_status'] === 'Confirmed'
                    ? 'selected'
                    : '' ?>
            >
                Confirmed
            </option>

            <option
                value="Cancelled"
                <?= $booking['booking_status'] === 'Cancelled'
                    ? 'selected'
                    : '' ?>
            >
                Cancelled
            </option>

        </select>

        <button
            type="submit"
            name="update_status"
        >
            Update Status
        </button>

    </form>

    <br>

    <a href="bookings.php">
         Back to Bookings
    </a>

</section>

</body>

</html>