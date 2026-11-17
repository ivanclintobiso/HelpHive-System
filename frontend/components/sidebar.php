<?php
$currentPage = basename($_SERVER["PHP_SELF"]);

function getSidebarClass($pageName, $currentPage) {
    if ($pageName == $currentPage) {
        return "sidebar-link active";
    }

    return "sidebar-link";
}
?>

<aside class="sidebar">

    <p class="sidebar-label">My Activities</p>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/saved-opportunities.php"
       class="<?php echo getSidebarClass("saved-opportunities.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/saveoppurtunity-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Saved Opportunities</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/my-applications.php"
       class="<?php echo getSidebarClass("my-applications.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myapplication-allprofilepage.png" alt="" class="sidebar-icon">
        <span>My Applications</span>
    </a>

    <hr class="sidebar-divider">

    <p class="sidebar-label">Organization</p>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php"
       class="<?php echo getSidebarClass("my-organization.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myorganization-allprofilepage.png" alt="" class="sidebar-icon">
        <span>My Organization</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/publish-opportunity.php"
       class="<?php echo getSidebarClass("publish-opportunity.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/postanoppurtunity-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Post an Opportunity</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/applicants.php"
       class="<?php echo getSidebarClass("applicants.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/managevolunteershours-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Manage Volunteers &amp; Hours</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/reports.php"
       class="<?php echo getSidebarClass("reports.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/particapationreports-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Participation Reports</span>
    </a>

    <hr class="sidebar-divider">

    <p class="sidebar-label">Account</p>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/my-profile.php"
       class="<?php echo getSidebarClass("my-profile.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/myprofile.png" alt="" class="sidebar-icon">
        <span>My Profile</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/notifications.php"
       class="<?php echo getSidebarClass("notifications.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/notifications-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Notifications</span>
    </a>

    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/account-settings.php"
       class="<?php echo getSidebarClass("account-settings.php", $currentPage); ?>">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/accountsetting-allprofilepage.png" alt="" class="sidebar-icon">
        <span>Account Settings</span>
    </a>

    <hr class="sidebar-divider">

    <a href="<?php echo BASE_URL; ?>/backend/form-actions/logout-action.php" class="sidebar-link logout-link">
        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/logout--allprofilepage.png" alt="" class="sidebar-icon">
        <span>Log Out</span>
    </a>

</aside>