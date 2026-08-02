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

if (isset($_POST['update_status'])) {

    $enquiryId = (int) ($_POST['enquiry_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';

    $allowedStatuses = ['New', 'Read', 'Closed'];

    if ($enquiryId > 0 && in_array($newStatus, $allowedStatuses, true)) {

        $updateStatement = $pdo->prepare(
            "UPDATE enquiries
             SET status = :status
             WHERE id = :id"
        );

        $updateStatement->execute([
            'status' => $newStatus,
            'id' => $enquiryId
        ]);

        header(
            'Location: view-enquiry.php?id=' .
            $enquiryId .
            '&updated=1'
        );
        exit;
    }
}

$enquiryId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$enquiryId || $enquiryId <= 0) {
    exit('Invalid enquiry.');
}

$statement = $pdo->prepare(
    "SELECT
        id,
        full_name,
        email,
        phone,
        subject,
        message,
        status,
        created_at
     FROM enquiries
     WHERE id = :id
     LIMIT 1"
);

$statement->execute([
    'id' => $enquiryId
]);

$enquiry = $statement->fetch();

if (!$enquiry) {
    exit('Enquiry not found.');
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

    <title>View Enquiry</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >
</head>

<body>

<section>

    <h2>Enquiry Details</h2>

    <?php

if (isset($_GET['updated'])) {

?>

<p style="color: green; font-weight: bold;">
    Status updated successfully.
</p>

<?php

}

?>

    <p>
        <strong>Name:</strong>
        <?= e($enquiry['full_name']) ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?= e($enquiry['email']) ?>
    </p>

    <p>
        <strong>Phone:</strong>
        <?= e($enquiry['phone'] ?? '') ?>
    </p>

    <p>
        <strong>Subject:</strong>
        <?= e($enquiry['subject']) ?>
    </p>

    <p>
        <strong>Message:</strong>
    </p>

    <p>
        <?= nl2br(e($enquiry['message'])) ?>
    </p>

    <p>
    <strong>Status:</strong>
    <?= e($enquiry['status']) ?>
</p>

<form method="POST">

    <input
        type="hidden"
        name="enquiry_id"
        value="<?= (int) $enquiry['id'] ?>"
    >

    <label for="status">
        <strong>Change Status:</strong>
    </label>

    <select name="status" id="status">

        <option
            value="New"
            <?= $enquiry['status'] === 'New' ? 'selected' : '' ?>
        >
            New
        </option>

        <option
            value="Read"
            <?= $enquiry['status'] === 'Read' ? 'selected' : '' ?>
        >
            Read
        </option>

        <option
            value="Closed"
            <?= $enquiry['status'] === 'Closed' ? 'selected' : '' ?>
        >
            Closed
        </option>

    </select>

    <button type="submit" name="update_status">
        Update Status
    </button>

</form>

    <p>
        <strong>Received:</strong>
        <?= e($enquiry['created_at']) ?>
    </p>

    <br>

    <a href="enquiries.php">
        ← Back to Enquiries
    </a>

</section>

</body>

</html>