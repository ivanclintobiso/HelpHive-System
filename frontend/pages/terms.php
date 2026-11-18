<?php
include_once __DIR__ . "/../../backend/settings/app-settings.php";

$cssVersion = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - HelpHive</title>

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/frontend/assets/images/logo-branding-panel.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/global.css?v=<?php echo $cssVersion; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/pages/terms.css?v=<?php echo $cssVersion; ?>">
</head>
<body>

<main>

    <section class="terms-hero">
        <h1 class="terms-hero-title">Terms of Use</h1>
        <p class="terms-hero-text">Please read these terms carefully before using HelpHive.</p>
    </section>


    <section class="terms-content">

        <p class="terms-updated">Last updated: November 18, 2026</p>

        <div class="terms-section">
            <h2 class="terms-heading">1. Acceptance of These Terms</h2>
            <p class="terms-text">
                By creating an account or using HelpHive, you agree to follow these Terms of Use. If you do not agree, please do not use the website.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">2. About HelpHive</h2>
            <p class="terms-text">
                HelpHive is a community volunteering platform. It helps volunteers find and register for activities, and it helps organizations post opportunities, manage volunteers, and confirm service hours.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">3. Your Account</h2>
            <p class="terms-text">
                You need an account to register for activities, save opportunities, or register an organization. You must give correct information when you sign up and keep it up to date.
            </p>
            <p class="terms-text">
                You are responsible for keeping your password safe and for everything that happens under your account. Please tell us right away if you think someone else is using it.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">4. For Volunteers</h2>
            <p class="terms-text">
                Volunteers agree to join only the activities they really plan to attend, to follow the instructions of the organization, and to treat other people with respect.
            </p>
            <p class="terms-text">
                Volunteer hours become official only after the organization confirms them on HelpHive.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">5. For Organizations</h2>
            <p class="terms-text">
                Organizations must give true details when they register. An organization can post opportunities only after the HelpHive admin approves its registration.
            </p>
            <p class="terms-text">
                Every opportunity is reviewed by the admin before it is published. Organizations are responsible for the safety, accuracy, and conduct of the activities they post.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">6. Community Rules</h2>
            <p class="terms-text">
                Do not post false, harmful, offensive, or misleading content. Do not harass other users, send spam, or use HelpHive for anything illegal or for selling products.
            </p>
            <p class="terms-text">
                Do not try to break, hack, or misuse the website.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">7. Admin Actions</h2>
            <p class="terms-text">
                The HelpHive admin may reject or remove opportunities, decline or suspend organizations, and ban accounts that break these Terms or the community rules. When this happens, you will receive a notification with the reason.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">8. Content You Post</h2>
            <p class="terms-text">
                You own the content you post, such as your profile details, organization information, and opportunity descriptions. By posting it, you allow HelpHive to show it on the website so the platform can work as intended.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">9. Limits of Responsibility</h2>
            <p class="terms-text">
                HelpHive only connects volunteers and organizations. We do not run the activities, and we cannot promise that every opportunity will be safe, available, or suitable for you.
            </p>
            <p class="terms-text">
                Please use your own judgment when joining an activity.
            </p>
        </div>

        <div class="terms-section">
            <h2 class="terms-heading">10. Changes to These Terms</h2>
            <p class="terms-text">
                We may update these Terms from time to time. The date at the top of this page shows when they were last changed. If you keep using HelpHive after an update, it means you accept the new Terms.
            </p>
        </div>

        <div class="terms-contact">
            <p>
                Have a question? Reach us through our
                <a href="<?php echo BASE_URL; ?>/frontend/pages/contact.php">Contact Us</a> page.
            </p>
        </div>

    </section>

</main>

</body>
</html>