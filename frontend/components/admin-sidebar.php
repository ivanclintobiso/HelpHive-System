<?php
$currentPage = basename($_SERVER["PHP_SELF"]);

function getAdminSidebarClass($pageName, $currentPage) {
    if ($pageName == $currentPage) {
        return "sidebar-link active";
    }

    return "sidebar-link";
}

$adminPages = BASE_URL . "/frontend/pages/admin/";

$sidebarCounts = [
    "pending_organizations" => 0,
    "pending_opportunities" => 0,
    "unread_messages" => 0
];

if (isset($adminCounts)) {
    $sidebarCounts = $adminCounts;
}
?>

<aside class="sidebar">

    <p class="sidebar-label">Overview</p>

    <a href="<?php echo $adminPages; ?>dashboard.php"
       class="<?php echo getAdminSidebarClass("dashboard.php", $currentPage); ?>">
        <i class="fa-solid fa-gauge sidebar-fa"></i>
        <span>Dashboard</span>
    </a>

    <hr class="sidebar-divider">

    <p class="sidebar-label">Requests</p>

    <a href="<?php echo $adminPages; ?>organization-requests.php"
       class="<?php echo getAdminSidebarClass("organization-requests.php", $currentPage); ?>">
        <i class="fa-solid fa-clipboard-check sidebar-fa"></i>
        <span>Organization Requests</span>
        <?php if ($sidebarCounts["pending_organizations"] > 0) { ?>
            <span class="sidebar-badge"><?php echo $sidebarCounts["pending_organizations"]; ?></span>
        <?php } ?>
    </a>

    <a href="<?php echo $adminPages; ?>opportunity-requests.php"
       class="<?php echo getAdminSidebarClass("opportunity-requests.php", $currentPage); ?>">
        <i class="fa-solid fa-hourglass-half sidebar-fa"></i>
        <span>Opportunity Requests</span>
        <?php if ($sidebarCounts["pending_opportunities"] > 0) { ?>
            <span class="sidebar-badge"><?php echo $sidebarCounts["pending_opportunities"]; ?></span>
        <?php } ?>
    </a>

    <hr class="sidebar-divider">

    <p class="sidebar-label">Manage</p>

    <a href="<?php echo $adminPages; ?>users.php"
       class="<?php echo getAdminSidebarClass("users.php", $currentPage); ?>">
        <i class="fa-solid fa-user-group sidebar-fa"></i>
        <span>Users</span>
    </a>

    <a href="<?php echo $adminPages; ?>opportunities.php"
       class="<?php echo getAdminSidebarClass("opportunities.php", $currentPage); ?>">
        <i class="fa-regular fa-calendar-days sidebar-fa"></i>
        <span>Opportunities</span>
    </a>

    <a href="<?php echo $adminPages; ?>organizations.php"
       class="<?php echo getAdminSidebarClass("organizations.php", $currentPage); ?>">
        <i class="fa-regular fa-building sidebar-fa"></i>
        <span>Organizations</span>
    </a>

    <a href="<?php echo $adminPages; ?>messages.php"
       class="<?php echo getAdminSidebarClass("messages.php", $currentPage); ?>">
        <i class="fa-regular fa-envelope sidebar-fa"></i>
        <span>Messages</span>
        <?php if ($sidebarCounts["unread_messages"] > 0) { ?>
            <span class="sidebar-badge"><?php echo $sidebarCounts["unread_messages"]; ?></span>
        <?php } ?>
    </a>

    <hr class="sidebar-divider">

    <a href="<?php echo BASE_URL; ?>/backend/form-actions/logout-action.php" class="sidebar-link logout-link">
        <i class="fa-solid fa-right-from-bracket sidebar-fa"></i>
        <span>Log Out</span>
    </a>

</aside>