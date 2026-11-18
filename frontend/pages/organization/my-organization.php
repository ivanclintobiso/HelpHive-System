<?php
$pageTitle = "My Organization";
$pageCss = "organization-dashboard";

$pageSubtitle = "Manage your organization, volunteer opportunities, and participation records.";
$emptyHeading = "No organization yet";
$emptyMessage = "You haven't registered an organization yet. Register one to start posting opportunities and managing volunteers.";

include __DIR__ . "/../../../backend/access-control/organization-required.php";


$statusLabels = [
    "approved" => "Approved",
    "declined" => "Declined",
    "suspended" => "Suspended"
];

$statusLabel = "Pending Review";
$statusClass = "pending";

if (isset($statusLabels[$organization["status"]])) {
    $statusLabel = $statusLabels[$organization["status"]];
    $statusClass = $organization["status"];
}

$message = "";

if (isset($_SESSION["organization_message"])) {
    $message = $_SESSION["organization_message"];
    unset($_SESSION["organization_message"]);
}

$popup = "";

if (isset($_SESSION["organization_popup"])) {
    $popup = $_SESSION["organization_popup"];
    unset($_SESSION["organization_popup"]);
}


$opportunities = getOrganizationOpportunities($connection, $organization["id"]);
$records = getOrganizationVolunteerRecords($connection, $organization["id"]);
$totals = getVolunteerTotals($records);

$publishedCount = 0;

foreach ($opportunities as $opportunity) {
    if ($opportunity["status"] == "published") {
        $publishedCount = $publishedCount + 1;
    }
}

$visibleOpportunities = array_slice($opportunities, 0, 5);

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">My Organization</h1>
        <p class="page-subtitle">Manage your organization, volunteer opportunities, and participation records.</p>

        <?php if ($message != "") { ?>
            <div class="notice-box"><?php echo safe($message); ?></div>
        <?php } ?>


        <section class="panel organization-card">

            <div class="organization-top">

                <div class="organization-logo">
                    <?php if ($organization["logo"] != "") { ?>
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/uploads/logos/<?php echo safe($organization["logo"]); ?>"
                             alt="<?php echo safe($organization["organization_name"]); ?>">
                    <?php } else { ?>
                        <i class="fa-regular fa-building"></i>
                    <?php } ?>
                </div>

                <div>
                    <h2 class="organization-name"><?php echo safe($organization["organization_name"]); ?></h2>

                    <p class="organization-type">
                        <?php echo safe($organization["organization_type"]); ?>
                        &middot;
                        <?php echo safe(getCauseText($organization["cause_areas"], $causeOptions)); ?>
                    </p>

                    <div class="badge-row">
                        <span class="badge badge-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                        <span class="badge badge-owner">Organization Owner</span>
                    </div>
                </div>

            </div>

            <p class="organization-about"><?php echo safe($organization["about"]); ?></p>

            <hr class="panel-line">

            <div class="organization-bottom">

                <div class="organization-contact">
                    <p>
                        <i class="fa-regular fa-map"></i>
                        <?php echo safe($organization["city_province"]); ?>
                    </p>
                    <p>
                        <i class="fa-regular fa-envelope"></i>
                        <?php echo safe($organization["email"]); ?>
                    </p>
                </div>

                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/edit-organization.php" class="pill-button pill-outline">
                    Edit Organization Details
                </a>

            </div>

        </section>


        <div class="summary-cards">

            <div class="summary-card">
                <span class="summary-icon"><i class="fa-regular fa-calendar-days"></i></span>
                <p class="summary-number"><?php echo $publishedCount; ?></p>
                <p class="summary-label">Published Opportunities</p>
            </div>

            <div class="summary-card">
                <span class="summary-icon"><i class="fa-solid fa-user-group"></i></span>
                <p class="summary-number"><?php echo $totals["registrations"]; ?></p>
                <p class="summary-label">Volunteer Registrations</p>
            </div>

            <div class="summary-card">
                <span class="summary-icon"><i class="fa-solid fa-hourglass-half"></i></span>
                <p class="summary-number"><?php echo formatHours($totals["hours"]); ?></p>
                <p class="summary-label">Verified Volunteer Hours</p>
            </div>

            <div class="summary-card">
                <span class="summary-icon"><i class="fa-regular fa-circle-check"></i></span>
                <p class="summary-number"><?php echo $totals["completed"]; ?></p>
                <p class="summary-label">Completed Participants</p>
            </div>

        </div>


        <section class="panel">

            <div class="panel-header">
                <h2 class="panel-title">Manage Opportunities</h2>
                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/reports.php" class="panel-link">View All &rarr;</a>
            </div>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/publish-opportunity.php" class="pill-button pill-yellow pill-wide">
                <i class="fa-solid fa-plus"></i>
                Post a New Opportunity
            </a>

            <?php if (count($visibleOpportunities) == 0) { ?>
                <p class="empty-text">You haven't posted any opportunities yet.</p>
            <?php } ?>

            <?php foreach ($visibleOpportunities as $opportunity) { ?>

                <?php
                $registrationCount = countRegistrations($records, $opportunity["id"]);

                $registrationText = $registrationCount . " registrations";
                if ($registrationCount == 1) {
                    $registrationText = "1 registration";
                }

                if ($opportunity["status"] != "published") {
                    $registrationText = "Not accepting registrations";
                }

                $editUrl = BASE_URL . "/frontend/pages/organization/edit-opportunity.php?id=" . $opportunity["id"];

                if ($opportunity["status"] == "draft" || $opportunity["status"] == "rejected") {
                    $editUrl = BASE_URL . "/frontend/pages/organization/publish-opportunity.php?id=" . $opportunity["id"];
                }
                ?>

                <div class="opportunity-row">

                    <div class="opportunity-row-top">
                        <h3 class="opportunity-title"><?php echo safe($opportunity["title"]); ?></h3>
                        <span class="chip chip-<?php echo $opportunity["status"]; ?>">
                            <?php echo getOpportunityStatusText($opportunity["status"]); ?>
                        </span>
                    </div>

                    <p class="opportunity-date"><?php echo formatDateText($opportunity["activity_date"]); ?></p>

                    <div class="opportunity-row-bottom">
                        <p class="opportunity-registrations">
                            <i class="fa-solid fa-user-group"></i>
                            <?php echo $registrationText; ?>
                        </p>

                        <button type="button"
                                class="manage-link manage-button"
                                data-status="<?php echo $opportunity["status"]; ?>"
                                data-edit-url="<?php echo $editUrl; ?>"
                                data-view-url="<?php echo BASE_URL; ?>/frontend/pages/opportunity.php?id=<?php echo $opportunity["id"]; ?>">
                            Manage
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                </div>

            <?php } ?>

        </section>


        <section class="panel">

            <h2 class="panel-title panel-title-icon">
                <i class="fa-solid fa-user-group"></i>
                Volunteer Management
            </h2>

            <p class="panel-text">Review registrations, record attendance, and verify volunteer hours after activities.</p>

            <div class="panel-info-row">
                <div>
                    <p class="info-title">Pending Verification</p>
                    <p class="info-note">Hours awaiting organizer confirmation</p>
                </div>

                <span class="chip chip-pending"><?php echo $totals["pending"]; ?> pending</span>
            </div>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/applicants.php" class="pill-button pill-gray pill-wide">
                Manage Volunteers &amp; Hours
            </a>

        </section>


        <section class="panel">

            <h2 class="panel-title panel-title-icon">
                <i class="fa-regular fa-file-lines"></i>
                Participation Reports
            </h2>

            <p class="panel-text">Review your organization's volunteer participation and verified service hours.</p>

            <p class="info-title">Volunteer Participation Summary</p>
            <p class="info-note info-note-spaced">Registrations, completed activities, and verified hours</p>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/reports.php" class="pill-button pill-outline pill-wide">
                View Participation Reports
            </a>

        </section>

    </main>

</div>

<div class="popup-overlay closed" id="manage-overlay">

    <div class="manage-card">

        <button type="button" class="manage-close" id="manage-close">
            <i class="fa-regular fa-circle-xmark"></i>
        </button>

        <h2 class="manage-title">Manage This Opportunity</h2>
        <p class="manage-text">Manage the details of your published opportunity and preview how it appears to volunteers.</p>

        <hr class="manage-line">

        <a href="#" class="manage-option" id="manage-edit">
            <i class="fa-regular fa-pen-to-square"></i>

            <span class="manage-option-words">
                <span class="manage-option-title" id="manage-edit-title">Edit Opportunity</span>
                <span class="manage-option-text">Update the activity description, date, time, location, or requirements.</span>
            </span>

            <i class="fa-solid fa-chevron-right manage-arrow"></i>
        </a>

        <a href="#" class="manage-option" id="manage-view">
            <i class="fa-regular fa-eye"></i>

            <span class="manage-option-words">
                <span class="manage-option-title">View Public Listing</span>
                <span class="manage-option-text">Preview how volunteers see this opportunity when published.</span>
            </span>

            <i class="fa-solid fa-chevron-right manage-arrow"></i>
        </a>

    </div>

</div>

<?php if ($popup == "updated" || $popup == "opportunity_updated") { ?>
    <div class="popup-overlay" id="organization-toast-overlay">

        <div class="toast-popup">

            <span class="popup-icon"><i class="fa-regular fa-circle-check"></i></span>

            <?php if ($popup == "updated") { ?>
                <h2 class="popup-title">Organization details updated successfully!</h2>
                <p class="popup-text">Your organization's information has been saved.</p>
            <?php } else { ?>
                <h2 class="popup-title">Opportunity updated successfully!</h2>
                <p class="popup-text">Your opportunity details have been saved.</p>
            <?php } ?>

        </div>

    </div>
<?php } ?>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/my-organization.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>