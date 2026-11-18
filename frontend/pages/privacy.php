<?php
include_once __DIR__ . "/../../backend/settings/app-settings.php";

$cssVersion = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - HelpHive</title>

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/frontend/assets/images/logo-branding-panel.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/global.css?v=<?php echo $cssVersion; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/pages/privacy.css?v=<?php echo $cssVersion; ?>">
</head>
<body>

<main>

    <section class="privacy-hero">
        <h1 class="privacy-hero-title">Privacy Policy</h1>
        <p class="privacy-hero-text">Learn what information HelpHive collects and how we use and protect it.</p>
    </section>


    <section class="privacy-content">

        <p class="privacy-updated">Last updated: November 18, 2026</p>

        <div class="privacy-section">
            <h2 class="privacy-heading">1. Information We Collect</h2>
            <p class="privacy-text">
                When you sign up, we collect your first name, last name, email address, password, and location. You may also add a profile picture and other profile details.
            </p>
            <p class="privacy-text">
                When you use HelpHive, we also keep records of your activity registrations, saved opportunities, volunteer hours, and notifications. Organizations also give us their organization details and the opportunities they post.
            </p>
            <p class="privacy-text">
                If you contact us, we keep your name, email address, subject, and message.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">2. How We Use Your Information</h2>
            <p class="privacy-text">
                We use your information to create and manage your account, to let you register for activities, to confirm volunteer hours, to send you notifications about your activity, and to answer your messages.
            </p>
            <p class="privacy-text">
                We also use it to keep the platform safe and to review organizations and opportunities.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">3. Who Can See Your Information</h2>
            <p class="privacy-text">
                Organizations can see the details of volunteers who register for their opportunities, such as name and contact information, so they can manage their activities.
            </p>
            <p class="privacy-text">
                The HelpHive admin can see account, organization, and message details so they can review requests and keep the community safe.
            </p>
            <p class="privacy-text">
                Your organization details and opportunity posts are shown publicly after they are approved.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">4. We Do Not Sell Your Data</h2>
            <p class="privacy-text">
                HelpHive does not sell your personal information. We only share it as described in this policy.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">5. How We Protect Your Information</h2>
            <p class="privacy-text">
                Your password is stored in a protected form, and access to account information is limited to the people who need it. No website can be 100% secure, so please also keep your password private.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">6. How Long We Keep Your Information</h2>
            <p class="privacy-text">
                We keep your information while your account is active and as long as it is needed to run HelpHive. You can delete your notifications at any time from your Notifications page.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">7. Your Choices</h2>
            <p class="privacy-text">
                You can update your profile details and change your password from Account Settings. You can also manage your email notification settings there.
            </p>
            <p class="privacy-text">
                If you want to see, correct, or remove your personal information, please contact us.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">8. Your Rights</h2>
            <p class="privacy-text">
                We respect your rights under the Data Privacy Act of 2012 of the Philippines, including the right to be informed, to access, and to correct your personal information.
            </p>
        </div>

        <div class="privacy-section">
            <h2 class="privacy-heading">9. Changes to This Policy</h2>
            <p class="privacy-text">
                We may update this Privacy Policy from time to time. The date at the top of this page shows when it was last changed.
            </p>
        </div>

        <div class="privacy-contact">
            <p>
                Have a question? Reach us through our
                <a href="<?php echo BASE_URL; ?>/frontend/pages/contact.php">Contact Us</a> page.
            </p>
        </div>

    </section>

</main>

</body>
</html>