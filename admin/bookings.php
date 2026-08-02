<?php
declare(strict_types=1);

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (!isset($_SESSION['user_id'])){
    redirect('../auth/login.php');
}

if (($_SESSION['role']?? '') !== 'admin') {
    redirect('../user/dashboard.php');
}

$statement = $pdo->prepare(
    "SELECT bookings.id, bookings.travel_date, bookings.persons, bookings.booking_status, bookings.created_at, users.full_name, users.email, packages.title AS package_title
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
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Manage Bookings</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>

    <body>
        <section style="padding: 30px;">
            <h2>Manage Bookings</h2>
            <br>
            <?php
            if (empty($bookings)) { ?>
                <p>No bookings found.</p>
            <?php 
            } else { ?>
            <div style="overflow-x:auto">
                <table border="1" cellpadding="10" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Package</th>
                            <th>Travel Date</th>
                            <th>persons</th>
                            <th>Status</th>
                            <th>Booked On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($bookings as $booking) { ?>
                            <tr>
                                <td><?= e($booking['id']) ?></td>
                                <td><?= e($booking['full_name']) ?></td>
                                <td><?= e($booking['email']) ?></td>
                                <td><?= e($booking['package_title']) ?></td>
                                <td><?= e($booking['travel_date']) ?></td>
                                <td><?= e($booking['persons']) ?></td>
                                <td><?php
                                $status = strtolower(
                                    $booking['booking_status']
                                );
                                if ($status === 'pending') {
                                    ?>
                                    <span style="color:yellow; font-weight:bold;">pending</span>
                                <?php
                                } elseif ($status === 'confirmed') {
                                    ?>
                                    <span style:red; font-weight:bold;>cancelled</span>
                                    <?php
                                } ?>
                                </td>
                                <td>
                                    <?= e($booking['created_at']) ?>
                                </td>
                                <td>
                                    <a href="view-booking.php?id=<?= (int) $booking['id'] ?>">View</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
           <?php }
            ?>
            <br>
            <a href="dashboard.php">Back to Admin Dashboard</a>
        </section>
    </body>
</html>