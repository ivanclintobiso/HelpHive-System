<?php
include __DIR__ . "/../../backend/settings/app-settings.php";

if (!isset($pageTitle)) {
    $pageTitle = "HelpHive";
}

if (!isset($pageCss)) {
    $pageCss = "";
}

$currentPage = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | HelpHive</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/global.css">

    <?php if ($pageCss != "") { ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/frontend/assets/css/pages/<?php echo $pageCss; ?>.css">
    <?php } ?>
</head>
<body>

<header class="site-header">

    <div class="header-left">
        <a href="<?php echo BASE_URL; ?>/frontend/pages/home.php" class="header-logo">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-logo.png" alt="HelpHive logo">
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

            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/register-organization.php"
               class="<?php if ($currentPage == "register-organization.php") { echo "active"; } ?>">
                Register Organization
            </a>
        </nav>
    </div>

    <div class="header-right">
        <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/login.php">Log In</a>
        <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/signup.php">Sign Up</a>
    </div>

</header>