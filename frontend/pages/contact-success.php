<?php
session_start();

if (!isset($_SESSION["contact_sent"])) {
    header("Location: contact.php");
    exit;
}

unset($_SESSION["contact_sent"]);

$pageTitle = "Message Sent";
$pageCss = "contact";

include __DIR__ . "/../components/header.php";
?>

<main class="success-section">

    <div class="success-card">

        <div class="success-icon">
            <i class="fa-regular fa-circle-check"></i>
        </div>

        <h1 class="success-title">Message Sent Successfully!</h1>

        <p class="success-text">
            Thank you for reaching out to HelpHive. We've received your message
            and appreciate you taking the time to contact us.
        </p>

        <hr class="contact-line">

        <h2 class="next-title">What Happens Next?</h2>

        <div class="next-box">
            <i class="fa-solid fa-envelope-circle-check"></i>
            <div>
                <h3>Message Received</h3>
                <p>Your inquiry has been submitted to the HelpHive team.</p>
            </div>
        </div>

        <div class="next-box">
            <i class="fa-regular fa-comment"></i>
            <div>
                <h3>Wait for Our Response</h3>
                <p>We'll get back to you using the email address you provided.</p>
            </div>
        </div>

        <a href="<?php echo BASE_URL; ?>/frontend/pages/home.php" class="success-button-green">Back to Home</a>

        <a href="<?php echo BASE_URL; ?>/frontend/pages/contact.php" class="success-button-outline">Back to Contact Us</a>

    </div>

</main>

<?php include __DIR__ . "/../components/footer-simple.php"; ?>