<?php
declare(strict_types=1);
include 'includes/header.php';
include 'config/database.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header('Location: packages.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ? AND status = 'active'");
$stmt->execute([$id]);
$package = $stmt->fetch();

if(!$package){
    header('Location: packages.php');
    exit;
}
?>

<div class="heading" style="background:url(assets/images/packages-bg.jpg) no-repeat;">
    <h1><?php echo htmlspecialchars($package['title']); ?></h1>
</div>

<section class="package-details">

```
<div class="image">
    <img src="assets/images/packages/<?php echo htmlspecialchars($package['image']); ?>" 
         alt="<?php echo htmlspecialchars($package['title']); ?>">
</div>

<div class="content">

    <div class="details-meta">
        <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($package['location']); ?></span>
        <span><i class="fas fa-clock"></i> <?= (int) $package['duration_days'] ?> Days</span>
    </div>

    <h2><?php echo htmlspecialchars($package['title']); ?></h2>

    <div class="detail-price">
        ₹<?php echo number_format($package['price'], 0); ?>
    </div>

    <p><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>

    <div class="detail-actions">
        <a href="booking/create.php?package_id=<?php echo $package['id']; ?>" class="btn">
            Book This Package
        </a>

        <a href="packages.php" class="btn btn-outline">
            Back to Packages
        </a>
    </div>

</div>
```

</section>

<?php include 'includes/footer.php'; ?>

