<?php
if (!isset($pageSubtitle)) {
    $pageSubtitle = "";
}

if (!isset($emptyHeading)) {
    $emptyHeading = "No organization yet";
}

if (!isset($emptyMessage)) {
    $emptyMessage = "Register your organization to get started.";
}

include __DIR__ . "/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($pageSubtitle); ?></p>

        <div class="empty-state">
            <h3><?php echo htmlspecialchars($emptyHeading); ?></h3>
            <p><?php echo htmlspecialchars($emptyMessage); ?></p>
            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/register-organization.php" class="empty-link">
                Register your organization
            </a>
        </div>

    </main>

</div>

<?php include __DIR__ . "/footer.php"; ?>