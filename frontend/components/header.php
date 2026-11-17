<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../../backend/settings/app-settings.php";

$cssVersion = time();

if (!isset($pageCss)) {
    $pageCss = "";
}

$currentPage = basename($_SERVER["PHP_SELF"]);

$isLoggedIn = false;
if (isset($_SESSION["user_id"])) {
    $isLoggedIn = true;
}

$profileImageFile = __DIR__ . "/../assets/images/temp-profile.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpHive</title>

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/frontend/assets/images/logo-branding-panel.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/global.css?v=<?php echo $cssVersion; ?>">

    <?php if (!$isLoggedIn) { ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/pages/auth.css?v=<?php echo $cssVersion; ?>">
    <?php } ?>

    <?php if ($isLoggedIn) { ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/profile-menu.css?v=<?php echo $cssVersion; ?>">
    <?php } ?>

    <?php if ($pageCss != "") { ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/pages/<?php echo $pageCss; ?>.css?v=<?php echo $cssVersion; ?>">
    <?php } ?>
</head>
<body>

<header class="site-header">

    <div class="header-left">
        <a href="<?php echo BASE_URL; ?>/frontend/pages/home.php" class="header-logo">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/logo-home-page.png" alt="HelpHive logo">
        </a>

        <nav class="header-nav">
            <a href="<?php echo BASE_URL; ?>/frontend/pages/home.php"
               class="<?php if ($currentPage == "home.php") { echo "active"; } ?>">
                Home
            </a>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php"
               class="<?php if ($currentPage == "search.php") { echo "active"; } ?>">
                Search Opportunities
            </a>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/organization-intro.php"
               class="<?php if ($currentPage == "organization-intro.php" || $currentPage == "register-organization.php") { echo "active"; } ?>">
                Register Organization
            </a>
        </nav>
    </div>

    <div class="header-right">

        <?php if ($isLoggedIn) { ?>

            <div class="profile-menu" id="profile-menu">

                <button type="button" class="profile-button" id="profile-button">

                    <?php if (file_exists($profileImageFile)) { ?>
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-profile.png"
                             alt="Profile picture"
                             class="profile-photo">
                    <?php } else { ?>
                        <span class="profile-photo profile-photo-empty"></span>
                    <?php } ?>

                    <span>Profile</span>
                    <span class="profile-arrow"></span>
                </button>

                <div class="profile-dropdown">

                    <p class="menu-label">My Activities</p>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/saved-opportunities.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/saveoppurtunity-allprofilepage.png" alt="" class="menu-icon">
                        <span>Saved Opportunities</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/my-applications.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myapplication-allprofilepage.png" alt="" class="menu-icon">
                        <span>My Applications</span>
                    </a>

                    <p class="menu-label">Organization</p>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myorganization-allprofilepage.png" alt="" class="menu-icon">
                        <span>My Organization</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/publish-opportunity.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/postanoppurtunity-allprofilepage.png" alt="" class="menu-icon">
                        <span>Post an Opportunity</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/applicants.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/managevolunteershours-allprofilepage.png" alt="" class="menu-icon">
                        <span>Manage Volunteers &amp; Hours</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/reports.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/particapationreports-allprofilepage.png" alt="" class="menu-icon">
                        <span>Participation Reports</span>
                    </a>

                    <p class="menu-label">Account</p>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/my-profile.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myprofile.png" alt="" class="menu-icon">
                        <span>My Profile</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/notifications.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/notifications-allprofilepage.png" alt="" class="menu-icon">
                        <span>Notifications</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/account-settings.php" class="menu-item">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/accountsetting-allprofilepage.png" alt="" class="menu-icon">
                        <span>Account Settings</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/backend/form-actions/logout-action.php" class="menu-item menu-logout logout-link">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/logout--allprofilepage.png" alt="" class="menu-icon">
                        <span>Log Out</span>
                    </a>

                </div>

            </div>

        <?php } else { ?>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/login.php" class="header-auth-link open-login">Log In</a>
            <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/signup.php" class="header-auth-link open-signup">Sign Up</a>

        <?php } ?>

    </div>

</header>

<?php
if (!$isLoggedIn) {
    include __DIR__ . "/auth-modal.php";
}

if ($isLoggedIn) {
    include __DIR__ . "/logout-modal.php";
?>
    <script src="<?php echo BASE_URL; ?>/frontend/assets/js/profile-menu.js"></script>
<?php
}
?>