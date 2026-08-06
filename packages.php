<?php
include 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
$stmt = $pdo->query("SELECT * FROM packages WHERE status = 'active' ORDER BY created_at DESC");
$packages = $stmt->fetchAll();
?>

<div class="heading" style="background:url(assets/images/bg-img.png) no-repeat;">
    <h1>Tour Packages</h1>
</div>

<section class="packages">

<h1 class="heading-title">Choose Your Next Destination</h1>

<div class="box-container">

    <?php foreach($packages as $package): ?>

        <div class="box">

            <div class="image">
                <img src="assets/images/packages/<?php echo htmlspecialchars($package['image']); ?>" 
                     alt="<?php echo htmlspecialchars($package['title']); ?>">
            </div>

            <div class="content">

                <div class="package-meta">
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($package['location']); ?></span>
                    <span><i class="fas fa-clock"></i> <?= (int) $package['duration_days'] ?> Days</span>
                </div>

                <h3><?php echo htmlspecialchars($package['title']); ?></h3>

                <p>
                    <?php echo htmlspecialchars(substr($package['description'], 0, 120)); ?>...
                </p>

                <div class="price-row">
                    <div class="price">
                        ₹<?php echo number_format($package['price'], 0); ?>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-outline">
                        View Details
                    </a>

                    <a href="book-package.php?package_id=<?= (int) $package['id'] ?>" class="btn">
                        Book Now
                    </a>
                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>
```

</section>

<?php include 'includes/footer.php'; ?>
