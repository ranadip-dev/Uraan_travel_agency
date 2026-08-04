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
        role,
        status,
        created_at
    FROM users
    ORDER BY created_at DESC"
);

$statement->execute();

$users = $statement->fetchAll();

?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <section style="padding:30px;">

    <div class="dashboard-header">
        <h2>Manage Users</h2>
        <br>
        <?php if (empty($users)) { ?>
        <p>No users found.</p>
        <?php } else { ?>
        </div>
        <div style="overflow-x:auto;"
        class="table-responsive">
            <table border="1" cellpadding="10" cellspacing="0" width="100%"
            class="dashboard-table">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>

                    <tr>

                            <td>
                                <?= e($user['id']) ?>
                            </td>

                            <td>
                                <?= e($user['full_name']) ?>
                            </td>

                            <td>
                                <?= e($user['email']) ?>
                            </td>

                            <td>
                                <?= e($user['phone'] ?? '') ?>
                            </td>

                            <td>
                                <?= e($user['role']) ?>
                            </td>

                            <td>

                            <?php if (strtolower($user['status']) === 'active') { ?>

                                    <span style="color:green;font-weight:bold;">
                                        Active
                                    </span>

                                <?php } else { ?>

                                    <span style="color:red;font-weight:bold;">
                                        Inactive
                                    </span>

                                <?php } ?>

                            </td>

                            <td>
                                <?= e($user['created_at']) ?>
                            </td>

                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>

        <br>

    <a href="dashboard.php" class="dashboard-btn">
        Back to Admin Dashboard
    </a>
    

    </section>
</body>
</html>