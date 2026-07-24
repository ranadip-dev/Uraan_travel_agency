<?php include 'includes/header.php'; ?>

<div class="heading" style="background:url(assets/images/contact-bg.jpg) no-repeat;">
    <h1>Contact Us</h1>
</div>

<section class="contact">

```
<div class="contact-info">

    <h3>Get in Touch</h3>

    <div class="info-box">
        <i class="fas fa-phone"></i>
        <div>
            <h4>Phone</h4>
            <p>+91 9876543210</p>
        </div>
    </div>

    <div class="info-box">
        <i class="fas fa-envelope"></i>
        <div>
            <h4>Email</h4>
            <p>travel@example.com</p>
        </div>
    </div>

    <div class="info-box">
        <i class="fas fa-map-marker-alt"></i>
        <div>
            <h4>Office</h4>
            <p>Siliguri, West Bengal, India</p>
        </div>
    </div>

</div>

<form class="contact-form" method="POST" action="#">

    <h3>Send a Message</h3>

    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <input type="text" name="subject" placeholder="Subject" required>
    <textarea name="message" rows="6" placeholder="Your Message" required></textarea>

    <button type="submit" class="btn">Send Message</button>

</form>
```

</section>

<?php include 'includes/footer.php'; ?>
