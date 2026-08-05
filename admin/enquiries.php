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
    ORDER BY created_at DESC"
);

$statement->execute();

$enquiries = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Enquiries | Admin</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body class="admin-dashboard-page">

<section style="padding: 30px;">

<div class="dashboard-header">
    <h2>Manage Enquiries</h2>

    <br>

    <?php if (empty($enquiries)) { ?>

        <p>No enquiries found.</p>

    <?php } else { ?>
    </div>

        <div style="overflow-x: auto;"
        class="table-responsive">

            <table
                border="1"
                cellpadding="10"
                cellspacing="0"
                width="100%"
                class="dashboard-table"
            >

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($enquiries as $enquiry) { ?>

                        <tr>

                            <td>
                                <?= e($enquiry['id']) ?>
                            </td>

                            <td>
                                <?= e($enquiry['full_name']) ?>
                            </td>

                            <td>
                                <?= e($enquiry['email']) ?>
                            </td>

                            <td>
                                <?= e($enquiry['phone'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($enquiry['subject']) ?>
                            </td>

                            <td>

                                <?php

                                $status = strtolower($enquiry['status']);

                                if ($status === 'new') {

                                ?>

                                    <span style="color: orange; font-weight: bold;">
                                        New
                                    </span>

                                <?php

                                } elseif ($status === 'read') {

                                ?>

                                    <span style="color: green; font-weight: bold;">
                                        Read
                                    </span>

                                <?php

                                } else {

                                ?>

                                    <span style="color: gray; font-weight: bold;">
                                        Closed
                                    </span>

                                <?php } ?>

                            </td>

                            <td>
                                <?= e($enquiry['created_at']) ?>
                            </td>

                            <td>

                                <a href="view-enquiry.php?id=<?= (int) $enquiry['id'] ?>"
                                class="view-link">
                                View</a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    <?php } ?>

    <br><br>

    <a href="dashboard.php"
    class="dashboard-btn">
        Back to Admin Dashboard
    </a>

</section>

</body>

</html>