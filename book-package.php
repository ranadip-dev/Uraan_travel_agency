<?php

declare(strict_types=1);

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isset($_SESSION['user_id'])) {
    redirect('auth/login.php');
}

$packageId = (int) ($_GET['package_id'] ?? 0);

if ($packageId <= 0) {
    exit('Invalid package selected.');
}

$statement = $pdo->prepare(
    "SELECT id, title, price
     FROM packages
     WHERE id = :id
     LIMIT 1"
);

$statement->execute([
    'id' => $packageId
]);

$package = $statement->fetch();

if (!$package) {
    exit('Package not found.');
}

$errors = [];
$travelDate = '';
$persons = 1;
$specialRequest = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $travelDate = cleanInput($_POST['travel_date'] ?? '');
    $persons = (int) ($_POST['persons'] ?? 1);
    $specialRequest = cleanInput($_POST['special_request'] ?? '');

    // Validate travel date
    if ($travelDate === '') {
        $errors[] = 'Please select a travel date.';
    }

    // Validate number of persons
    if ($persons < 1) {
        $errors[] = 'Number of persons must be at least 1.';
    }

    if (empty($errors)) {

        $insertStatement = $pdo->prepare(
            "INSERT INTO bookings
            (user_id, package_id, travel_date, persons, special_request)
            VALUES
            (:user_id, :package_id, :travel_date, :persons, :special_request)"
        );

        $insertStatement->execute([
            'user_id'         => $_SESSION['user_id'],
            'package_id'      => $package['id'],
            'travel_date'     => $travelDate,
            'persons'         => $persons,
            'special_request' => $specialRequest
        ]);

        header(
            'Location: user/dashboard.php?booking=success'
        );
        exit;
    }
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

    <title>Book Package</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="booking-page">

<div class="booking-container">

    <div class="booking-card">

        <div class="booking-heading">

            <p class="booking-label">
                Complete Your Reservation
            </p>

            <h2>Book Your Package</h2>

            <h3><?= e($package['title']) ?></h3>

            <p class="booking-price">
                ₹<?= number_format((float) $package['price'], 2) ?>
                <span>per package</span>
            </p>

        </div>


        <?php
        if (!empty($errors)) {
        ?>

            <div class="error-message">

                <?php
                foreach ($errors as $error) {
                ?>

                    <p><?= e($error) ?></p>

                <?php
                }
                ?>

            </div>

        <?php
        }
        ?>


        <form method="POST" class="booking-form">

            <div class="form-group">

                <label for="travel_date">
                    Travel Date
                </label>

                <input
                    type="date"
                    id="travel_date"
                    name="travel_date"
                    value="<?= e($travelDate) ?>"
                    min="<?= date('Y-m-d') ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label for="persons">
                    Number of Persons
                </label>

                <input
                    type="number"
                    id="persons"
                    name="persons"
                    value="<?= e((string) $persons) ?>"
                    min="1"
                    required
                >

            </div>


            <div class="form-group">

                <label for="special_request">
                    Special Request
                </label>

                <textarea
                    id="special_request"
                    name="special_request"
                    rows="5"
                    placeholder="Let us know if you have any special requirements..."
                ><?= e($specialRequest) ?></textarea>

            </div>


            <button
                type="submit"
                class="btn"
            >
                Confirm Booking
            </button>

        </form>


        <div class="booking-links">

            <a href="package-details.php?id=<?= (int) $package['id'] ?>">
                Back to Package
            </a>

            <a href="user/dashboard.php">
                My Dashboard
            </a>

        </div>

    </div>

</div>

</body>

</html>