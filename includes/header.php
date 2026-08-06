<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agency</title>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="header">
    <a href="/uraan_travel_agency/index.php" class="logo">
    <img
        src="/uraan_travel_agency/assets/images/logo.png"
        alt="Uraan Travel Agency"
    >
</a>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="packages.php">Packages</a>
        <a href="contact.php">Contact</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="user/dashboard.php">Dashboard</a>
            <a href="auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="auth/login.php">Login</a>
            <a href="auth/register.php">Register</a>
        <?php endif; ?>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</header>