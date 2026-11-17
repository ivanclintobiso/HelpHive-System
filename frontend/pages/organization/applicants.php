<?php
$pageTitle = "Manage Volunteers & Hours";
$pageCss = "organization-dashboard";

$pageSubtitle = "Review registrations, record attendance, and confirm completed service hours.";
$emptyHeading = "No volunteers to manage yet";
$emptyMessage = "Register your organization and post an opportunity to start receiving volunteers.";

include __DIR__ . "/../../../backend/access-control/organization-required.php";


function makeVolunteersLink($opportunityId, $status) {
    $query = [];

    if ($opportunityId > 0) {
        $query["opportunity"] = $opportunityId;
    }

    if ($status != "all") {
        $query["status"] = $status;
    }

    if (count($query) == 0) {
        return "applicants.php";
    }

    return "applicants.php?" . http_build_query($query);
}


$statusOptions = [
    "all" => "All Volunteers",
    "registered" => "Registered",
    "pending" => "Pending",
    "verified" => "Verified"
];

$statusLabels = [
    "registered" => "Registered",
    "pending" => "Pending",
    "verified" => "Verified"
];

$attendanceLabels = [
    "present" => "Present",
    "absent" => "Absent",
    "not_recorded" => "Not recorded"
];


$opportunities = getOrganizationOpportunities($connection, $organization["id"]);
$allRecords = getOrganizationVolunteerRecords($connection, $organization["id"]);
$totals = getVolunteerTotals($allRecords);

$selectedOpportunityId = (int) getTextFromUrl("opportunity");
$selectedStatus = getTextFromUrl("status");

if (!isset($statusOptions[$selectedStatus])) {
    $selectedStatus = "all";
}

$opportunityRecords = [];

foreach ($allRecords as $record) {
    if ($selectedOpportunityId == 0 || $record["opportunity_id"] == $selectedOpportunityId) {
        $opportunityRecords[] = $record;
    }
}

$visibleRecords = [];

foreach ($opportunityRecords as $record) {
    if ($selectedStatus == "all" || getRecordStatus($record) == $selectedStatus) {
        $visibleRecords[] = $record;
    }
}

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">Manage Volunteers &amp; Hours</h1>
        <p class="page-subtitle">Review registrations, record attendance, and confirm completed service hours.</p>


        <?php if (count($opportunities) == 0) { ?>

            <div class="empty-state">
                <h3>No opportunities posted yet</h3>
                <p>Post an opportunity first. Volunteers who register will show up here.</p>
                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/publish-opportunity.php" class="empty-link">
                    Post an opportunity
                </a>
            </div>

        <?php } else if (count($allRecords) == 0) { ?>

            <div class="empty-state">
                <h3>No volunteers have registered yet</h3>
                <p>When volunteers register for your opportunities, you can manage their attendance and hours here.</p>
                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php" class="empty-link">
                    Back to My Organization
                </a>
            </div>

        <?php } else { ?>

            <div class="summary-cards">

                <div class="summary-card">
                    <span class="summary-icon"><i class="fa-solid fa-user-group"></i></span>
                    <p class="summary-number"><?php echo $totals["registrations"]; ?></p>
                    <p class="summary-label">Total Registrations</p>
                </div>

                <div class="summary-card">
                    <span class="summary-icon"><i class="fa-solid fa-hourglass-half"></i></span>
                    <p class="summary-number"><?php echo $totals["pending"]; ?></p>
                    <p class="summary-label">Pending Verification</p>
                </div>

                <div class="summary-card">
                    <span class="summary-icon"><i class="fa-regular fa-circle-check"></i></span>
                    <p class="summary-number"><?php echo formatHours($totals["hours"]); ?></p>
                    <p class="summary-label">Verified Hours</p>
                </div>

                <div class="summary-card">
                    <span class="summary-icon"><i class="fa-regular fa-calendar-days"></i></span>
                    <p class="summary-number"><?php echo $totals["corrections"]; ?></p>
                    <p class="summary-label">Correction Requests</p>
                </div>

            </div>


            <section class="panel">

                <form action="applicants.php" method="get">

                    <label class="field-label" for="opportunity-select">Choose Opportunity</label>

                    <div class="select-box">
                        <select name="opportunity" id="opportunity-select" class="select-input" onchange="this.form.submit()">
                            <option value="0">Choose opportunity</option>
                            <?php foreach ($opportunities as $opportunity) { ?>
                                <option value="<?php echo $opportunity["id"]; ?>"
                                        <?php if ($opportunity["id"] == $selectedOpportunityId) { echo "selected"; } ?>>
                                    <?php echo safe($opportunity["title"]); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <i class="fa-solid fa-chevron-up"></i>
                    </div>

                </form>

                <div class="filter-pills">
                    <?php foreach ($statusOptions as $value => $label) { ?>
                        <a href="<?php echo makeVolunteersLink($selectedOpportunityId, $value); ?>"
                           class="filter-pill <?php if ($selectedStatus == $value) { echo "active"; } ?>">
                            <?php echo $label; ?>
                            <?php if ($value == "all") { echo "(" . count($opportunityRecords) . ")"; } ?>
                        </a>
                    <?php } ?>
                </div>

                <h2 class="panel-title">Volunteer Records</h2>

                <?php if (count($visibleRecords) == 0) { ?>
                    <p class="empty-text">No volunteer records found.</p>
                <?php } ?>

                <?php foreach ($visibleRecords as $record) { ?>

                    <?php
                    $recordStatus = getRecordStatus($record);

                    $durationText = "—";
                    if ($record["hours"] > 0) {
                        $durationText = formatHours($record["hours"]);
                    }
                    ?>

                    <div class="record-row">

                        <div class="record-top">
                            <div>
                                <h3 class="record-name"><?php echo safe($record["name"]); ?></h3>
                                <p class="record-email"><?php echo safe($record["email"]); ?></p>
                            </div>

                            <span class="chip chip-<?php echo $recordStatus; ?>">
                                <?php echo $statusLabels[$recordStatus]; ?>
                            </span>
                        </div>

                        <div class="record-details">
                            <div>
                                <p class="detail-label">Attendance</p>
                                <p class="detail-value"><?php echo $attendanceLabels[$record["attendance"]]; ?></p>
                            </div>

                            <div>
                                <p class="detail-label">Expected Duration</p>
                                <p class="detail-value"><?php echo $durationText; ?></p>
                            </div>
                        </div>

                        <?php if ($recordStatus == "verified") { ?>

                            <button type="button" class="pill-button pill-yellow pill-wide" disabled>Confirmed</button>

                        <?php } else { ?>

                            <form action="<?php echo BASE_URL; ?>/backend/form-actions/attendance-action.php" method="post">
                                <input type="hidden" name="record_id" value="<?php echo $record["id"]; ?>">
                                <input type="hidden" name="opportunity" value="<?php echo $selectedOpportunityId; ?>">
                                <input type="hidden" name="status" value="<?php echo $selectedStatus; ?>">
                                <button type="submit" class="pill-button pill-gray pill-wide">Confirm &amp; Verify Hours</button>
                            </form>

                        <?php } ?>

                    </div>

                <?php } ?>

            </section>

        <?php } ?>

    </main>

</div>

<?php include __DIR__ . "/../../components/footer.php"; ?>