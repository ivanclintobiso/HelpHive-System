<?php
include_once __DIR__ . "/../../backend/settings/connection-with-test-mode.php";
include_once __DIR__ . "/../../backend/database-queries/admin-queries.php";

$adminPagesUrl = BASE_URL . "/frontend/pages/admin/";

$bellOrganizations = [];

foreach (getOrganizationsForAdmin($connection) as $bellOrganization) {
    if ($bellOrganization["status"] == "pending" && count($bellOrganizations) < 3) {
        $bellOrganizations[] = $bellOrganization;
    }
}

$bellOpportunities = [];

foreach (getOpportunitiesForAdmin($connection) as $bellOpportunity) {
    if ($bellOpportunity["status"] == "pending_review" && count($bellOpportunities) < 3) {
        $bellOpportunities[] = $bellOpportunity;
    }
}

$bellMessages = [];

foreach (getMessagesForAdmin($connection) as $bellMessage) {
    if ($bellMessage["is_read"] == 0 && count($bellMessages) < 3) {
        $bellMessages[] = $bellMessage;
    }
}
?>

<div class="bell-menu" id="bell-menu">

    <button type="button" class="header-bell" id="bell-button" title="Needs your attention">
        <i class="fa-regular fa-bell"></i>

        <?php if ($adminBellCount > 0) { ?>
            <span class="bell-badge"><?php echo $adminBellCount; ?></span>
        <?php } ?>
    </button>

    <div class="bell-dropdown">

        <p class="bell-heading">Needs your attention</p>

        <?php if ($adminBellCount == 0) { ?>
            <p class="bell-empty">You're all caught up. Nothing is waiting for you.</p>
        <?php } ?>


        <?php if ($adminCounts["pending_organizations"] > 0) { ?>

            <div class="bell-group">

                <a href="<?php echo $adminPagesUrl; ?>organization-requests.php" class="bell-group-title">
                    <span>Organization Requests</span>
                    <span class="bell-count"><?php echo $adminCounts["pending_organizations"]; ?></span>
                </a>

                <?php foreach ($bellOrganizations as $bellOrganization) { ?>
                    <a href="<?php echo $adminPagesUrl; ?>organization-requests.php" class="bell-item">
                        <span class="bell-item-title"><?php echo htmlspecialchars($bellOrganization["organization_name"]); ?></span>
                        <span class="bell-item-note">Wants to be approved &middot; <?php echo htmlspecialchars($bellOrganization["city_province"]); ?></span>
                    </a>
                <?php } ?>

            </div>

        <?php } ?>


        <?php if ($adminCounts["pending_opportunities"] > 0) { ?>

            <div class="bell-group">

                <a href="<?php echo $adminPagesUrl; ?>opportunity-requests.php" class="bell-group-title">
                    <span>Opportunity Requests</span>
                    <span class="bell-count"><?php echo $adminCounts["pending_opportunities"]; ?></span>
                </a>

                <?php foreach ($bellOpportunities as $bellOpportunity) { ?>
                    <a href="<?php echo $adminPagesUrl; ?>opportunity-requests.php" class="bell-item">
                        <span class="bell-item-title"><?php echo htmlspecialchars($bellOpportunity["title"]); ?></span>
                        <span class="bell-item-note">From <?php echo htmlspecialchars($bellOpportunity["organization_name"]); ?></span>
                    </a>
                <?php } ?>

            </div>

        <?php } ?>


        <?php if ($adminCounts["unread_messages"] > 0) { ?>

            <div class="bell-group">

                <a href="<?php echo $adminPagesUrl; ?>messages.php" class="bell-group-title">
                    <span>Unread Messages</span>
                    <span class="bell-count"><?php echo $adminCounts["unread_messages"]; ?></span>
                </a>

                <?php foreach ($bellMessages as $bellMessage) { ?>
                    <a href="<?php echo $adminPagesUrl; ?>messages.php" class="bell-item">
                        <span class="bell-item-title"><?php echo htmlspecialchars($bellMessage["subject"]); ?></span>
                        <span class="bell-item-note">From <?php echo htmlspecialchars($bellMessage["full_name"]); ?></span>
                    </a>
                <?php } ?>

            </div>

        <?php } ?>

    </div>

</div>
