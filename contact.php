<?php include 'includes/header.php'; ?>
<?php

require_once 'config/database.php';
require_once 'includes/functions.php';

$errors = [];
$success = '';

$name = '';
$email = '';
$phone = '';
$subject = '';
$message = '';

if (isset($_POST['send_enquiry'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($subject === '') {
        $errors[] = 'Subject is required.';
    }

    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    if (empty($errors)) {

        $statement = $pdo->prepare(
            "INSERT INTO enquiries
            (
                full_name,
                email,
                phone,
                subject,
                message
            )
            VALUES
            (
                :full_name,
                :email,
                :phone,
                :subject,
                :message
            )"
        );

        $statement->execute([
            'full_name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ]);

        $success = 'Your messages has been submitted successfully.';

        $name = '';
        $email = '';
        $phone = '';
        $subject = '';
        $message = '';
    }
}
?>

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

<?php

foreach ($errors as $error) {

?>

<p style="color:red;">
    <?= e($error) ?>
</p>

<?php

}

if ($success !== '') {

?>

<p style="color:green;font-weight:bold;">
    <?= e($success) ?>
</p>

<?php

}

?>

<form class="contact-form" method="POST" action="#">

    <h3>Send a Message</h3>
    <input
    type="text"
    name="name"
    value="<?= e($name) ?>"
    placeholder="Your Name"
    required>
    <input
    type="email"
    name="email"
    value="<?= e($email) ?>"
    placeholder="Your Email"
    required>
    <input
    type="text"
    name="phone"
    value="<?= e($phone) ?>"
    placeholder="Your phone"
    required>
    <input
    type="text"
    name="subject"
    value="<?= e($subject) ?>"
    placeholder="Subject"
    required>
    <textarea
    name="message"
    rows="6"
    placeholder="Your Message"
    required><?= e($message) ?></textarea>

    <button
    type="submit"
    name="send_enquiry"
    class="btn">Send Massage</button>

</form>
```

</section>

<?php include 'includes/footer.php'; ?>
