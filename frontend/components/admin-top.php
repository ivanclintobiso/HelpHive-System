<?php
if (!isset($adminCounts)) {
    $adminCounts = getAdminCounts($connection);
}

if (!isset($pageSubtitle)) {
    $pageSubtitle = "";
}

$adminMessage = "";
$adminError = "";

if (isset($_SESSION["admin_message"])) {
    $adminMessage = $_SESSION["admin_message"];
    unset($_SESSION["admin_message"]);
}

if (isset($_SESSION["admin_error"])) {
    $adminError = $_SESSION["admin_error"];
    unset($_SESSION["admin_error"]);
}

include __DIR__ . "/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/admin-sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title"><?php echo safe($pageTitle); ?></h1>
        <p class="page-subtitle"><?php echo safe($pageSubtitle); ?></p>

        <?php if ($adminMessage != "") { ?>
            <p class="admin-notice admin-notice-success"><?php echo safe($adminMessage); ?></p>
        <?php } ?>

        <?php if ($adminError != "") { ?>
            <p class="admin-notice admin-notice-error"><?php echo safe($adminError); ?></p>
        <?php } ?>