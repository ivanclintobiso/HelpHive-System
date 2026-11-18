<?php
$pageTitle = "Dashboard";
$pageSubtitle = "Overview of HelpHive and the requests waiting for you.";
$pageCss = "admin";

include __DIR__ . "/../../../backend/access-control/admin-required.php";

$adminCounts = getAdminCounts($connection);

$users = getAllUsers($connection);
$organizations = getOrganizationsForAdmin($connection);
$opportunities = getOpportunitiesForAdmin($connection);

$activeOpportunityCount = 0;

foreach ($opportunities as $opportunity) {
    if ($opportunity["status"] == "published") {
        $activeOpportunityCount = $activeOpportunityCount + 1;
    }
}

$pendingOrganizations = [];

foreach ($organizations as $organization) {
    if ($organization["status"] == "pending") {
        $pendingOrganizations[] = $organization;
    }
}

$pendingOpportunities = [];

foreach ($opportunities as $opportunity) {
    if ($opportunity["status"] == "pending_review") {
        $pendingOpportunities[] = $opportunity;
    }
}

$latestOrganizations = array_slice($pendingOrganizations, 0, 5);
$latestOpportunities = array_slice($pendingOpportunities, 0, 5);

include __DIR__ . "/../../components/admin-top.php";
?>

<div class="stat-grid">

    <div class="stat-card">
        <span class="stat-icon"><i class="fa-solid fa-user-group"></i></span>
        <p class="stat-number"><?php echo count($users); ?></p>
        <p class="stat-label">Total Users</p>
    </div>

    <div class="stat-card">
        <span class="stat-icon"><i class="fa-regular fa-calendar-days"></i></span>
        <p class="stat-number"><?php echo $activeOpportunityCount; ?></p>
        <p class="stat-label">Active Opportunities</p>
    </div>

    <div class="stat-card">
        <span class="stat-icon"><i class="fa-regular fa-building"></i></span>
        <p class="stat-number"><?php echo $adminCounts["pending_organizations"]; ?></p>
        <p class="stat-label">Pending Organizations</p>
    </div>

    <div class="stat-card">
        <span class="stat-icon"><i class="fa-regular fa-envelope"></i></span>
        <p class="stat-number"><?php echo $adminCounts["unread_messages"]; ?></p>
        <p class="stat-label">Unread Messages</p>
    </div>

</div>


<section class="admin-panel">

    <h2 class="panel-heading">Needs your attention</h2>

    <?php if (count($latestOrganizations) == 0 && count($latestOpportunities) == 0) { ?>
        <div class="empty-state">
            <h3>You're all caught up</h3>
            <p>There are no organizations or opportunities waiting for review.</p>
        </div>
    <?php } ?>

    <?php foreach ($latestOrganizations as $organization) { ?>

        <div class="attention-row">

            <div>
                <p class="attention-title"><?php echo safe($organization["organization_name"]); ?></p>
                <p class="attention-note">
                    New organization &middot; <?php echo safe($organization["city_province"]); ?>
                    &middot; <?php echo formatAdminDate($organization["created_at"]); ?>
                </p>
            </div>

            <div class="actions-cell">
                <a href="organization-requests.php" class="action-button neutral">Review</a>
            </div>

        </div>

    <?php } ?>

    <?php foreach ($latestOpportunities as $opportunity) { ?>

        <div class="attention-row">

            <div>
                <p class="attention-title"><?php echo safe($opportunity["title"]); ?></p>
                <p class="attention-note">
                    New opportunity from <?php echo safe($opportunity["organization_name"]); ?>
                    &middot; <?php echo formatAdminDate($opportunity["created_at"]); ?>
                </p>
            </div>

            <div class="actions-cell">
                <a href="opportunity-requests.php" class="action-button neutral">Review</a>
            </div>

        </div>

    <?php } ?>

</section>

<?php include __DIR__ . "/../../components/admin-bottom.php"; ?>