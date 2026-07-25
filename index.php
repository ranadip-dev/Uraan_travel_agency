<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uraan Travel Agency</title>
</head>
<body>
    <?php 
    include 'includes/header.php'; ?>

<section class="home">
    <div class="swiper home-slider">
        <div class="swiper-wrapper">

            <div class="swiper-slide slide"
                 style="background:url(assets/images/slide1.jpg) no-repeat">
                <div class="content">
                    <span>Explore, Discover, Travel</span>
                    <h3>Travel Around The World</h3>
                    <a href="packages.php" class="btn">Discover More</a>
                </div>
            </div>

            <div class="swiper-slide slide"
                 style="background:url(assets/images/slide2.jpg) no-repeat">
                <div class="content">
                    <span>Make Your Tour Worthwhile</span>
                    <h3>Discover New Places</h3>
                    <a href="packages.php" class="btn">Discover More</a>
                </div>
            </div>

            <div class="swiper-slide slide"
                 style="background:url(assets/images/slide3.jpg) no-repeat">
                <div class="content">
                    <span>Take Only Memories, Leave Only Footprints</span>
                    <h3>Let's Find Some Beautiful Places To Get Lost !</h3>
                    <a href="packages.php" class="btn">Discover More</a>
                </div>
            </div>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<section class="services">
    <h1 class="heading-title">Our Services</h1>

    <div class="box-container">
        <div class="box">
            <i class="fas fa-hotel"></i>
            <h3>Hotel Booking</h3>
        </div>

        <div class="box">
            <i class="fas fa-plane"></i>
            <h3>Flight Tickets</h3>
        </div>

        <div class="box">
            <i class="fas fa-globe-asia"></i>
            <h3>World Tours</h3>
        </div>
    </div>
</section>

<section class="home-about">
    <div class="image">
        <img src="assets/images/about-img.jpg" alt="About">
    </div>

    <div class="content">
        <h3>About Us</h3>
        <p>
            We provide memorable travel experiences with affordable packages,
            secure bookings, and personalized customer support.
        </p>
        <a href="about.php" class="btn">Read More</a>
    </div>
</section>

<section class="home-packages">
    <h1 class="heading-title">Popular Packages</h1>

    <div class="box-container">

        <div class="box">
            <div class="image">
                <img src="assets/images/img-1.jpg" alt="">
            </div>
            <div class="content">
                <h3>Darjeeling Tour</h3>
                <p>3 Days / 2 Nights</p>
                <a href="packages.php" class="btn">Book Now</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="assets/images/img-2.jpg" alt="">
            </div>
            <div class="content">
                <h3>Gangtok Adventure</h3>
                <p>5 Days / 4 Nights</p>
                <a href="packages.php" class="btn">Book Now</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="assets/images/img-3.jpg" alt="">
            </div>
            <div class="content">
                <h3>Goa Beach Trip</h3>
                <p>4 Days / 3 Nights</p>
                <a href="packages.php" class="btn">Book Now</a>
            </div>
        </div>

    </div>
</section>

<section class="home-offer">
    <div class="content">
        <h3>Special Summer Offer</h3>
        <p>Get up to 25% discount on selected tour packages this season.</p>
        <a href="packages.php" class="btn">Explore Offers</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>