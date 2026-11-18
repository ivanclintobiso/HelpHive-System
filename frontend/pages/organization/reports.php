<?php
$pageTitle = "Participation Reports";
$pageCss = "organization-dashboard";

$pageSubtitle = "Review your organization's volunteer participation and verified service hours.";
$emptyHeading = "No reports yet";
$emptyMessage = "Register your organization and post an opportunity to see your volunteer participation here.";

include __DIR__ . "/../../../backend/access-control/organization-required.php";


$periodOptions = [
    "" => "Select period",
    "this_month" => "This month",
    "last_30" => "Last 30 days",
    "this_year" => "This year"
];

$opportunities = getOrganizationOpportunities($connection, $organization["id"]);
$allRecords = getOrganizationVolunteerRecords($connection, $organization["id"]);

$period = getTextFromUrl("period");
$selectedOpportunityId = (int) getTextFromUrl("opportunity");

if (!isset($periodOptions[$period])) {
    $period = "";
}

$reportOpportunities = getReportOpportunities($opportunities, $period, $selectedOpportunityId);
$reportRecords = getRecordsForOpportunities($allRecords, $reportOpportunities);
$totals = getVolunteerTotals($reportRecords);

$publishedCount = 0;

foreach ($reportOpportunities as $opportunity) {
    if ($opportunity["status"] == "published") {
        $publishedCount = $publishedCount + 1;
    }
}

$registrationPercent = 0;
$completedPercent = 0;

if ($totals["registrations"] > 0) {
    $registrationPercent = 100;
    $completedPercent = round($totals["completed"] / $totals["registrations"] * 100);
}

$exportLink = BASE_URL . "/backend/form-actions/export-report-action.php?" . http_build_query([
    "period" => $period,
    "opportunity" => $selectedOpportunityId
]);

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">Participation Reports</h1>
        <p class="page-subtitle">Review your organization's volunteer participation and verified service hours.</p>


        <?php if (count($opportunities) == 0) { ?>

            <div class="empty-state">
                <h3>No reports yet</h3>
                <p>Post an opportunity and your participation report will appear here once volunteers register.</p>
                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/publish-opportunity.php" class="empty-link">
                    Post an opportunity
                </a>
            </div>

        <?php } else { ?>

            <section class="panel">

                <div class="report-top">

                    <div>
                        <h2 class="report-name"><?php echo safe($organization["organization_name"]); ?></h2>
                        <p class="report-note">Volunteer Participation Summary</p>
                    </div>

                    <div class="report-buttons no-print">
                        <a href="<?php echo $exportLink; ?>" class="pill-button pill-outline pill-small">Export CSV</a>
                        <button type="button" class="pill-button pill-yellow pill-small" onclick="window.print()">Download PDF</button>
                    </div>

                </div>

                <form action="reports.php" method="get" class="report-filters no-print">

                    <div>
                        <label class="field-label" for="period-select">Reporting Period</label>

                        <div class="select-box">
                            <select name="period" id="period-select" class="select-input select-light" onchange="this.form.submit()">
                                <?php foreach ($periodOptions as $value => $label) { ?>
                                    <option value="<?php echo $value; ?>"
                                            <?php if ($period == $value) { echo "selected"; } ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <i class="fa-solid fa-chevron-up"></i>
                        </div>
                    </div>

                    <div>
                        <label class="field-label" for="opportunity-select">Opportunity</label>

                        <div class="select-box">
                            <select name="opportunity" id="opportunity-select" class="select-input select-light" onchange="this.form.submit()">
                                <option value="0">Select opportunity</option>
                                <?php foreach ($opportunities as $opportunity) { ?>
                                    <option value="<?php echo $opportunity["id"]; ?>"
                                            <?php if ($opportunity["id"] == $selectedOpportunityId) { echo "selected"; } ?>>
                                        <?php echo safe($opportunity["title"]); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <i class="fa-solid fa-chevron-up"></i>
                        </div>
                    </div>

                </form>

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

                <h2 class="panel-title">Volunteer Participation Summary</h2>
                <p class="report-description">Organization-wide registrations and completed participants.</p>

                <div class="bar-row">
                    <p>Volunteer registrations</p>
                    <strong><?php echo $totals["registrations"]; ?></strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill" style="width: <?php echo $registrationPercent; ?>%;"></div>
                </div>

                <div class="bar-row">
                    <p>Completed participants</p>
                    <strong><?php echo $totals["completed"]; ?></strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill" style="width: <?php echo $completedPercent; ?>%;"></div>
                </div>

            </section>


            <section class="panel">

                <h2 class="panel-title">Opportunity Registration Snapshot</h2>
                <p class="report-description">Activities listed on your organization overview.</p>

                <div class="table-row table-head">
                    <p>Opportunity / Activity date</p>
                    <p>Status</p>
                    <p class="table-right">Registrations</p>
                </div>

                <?php if (count($reportOpportunities) == 0) { ?>
                    <p class="empty-text">No opportunities found for this report.</p>
                <?php } ?>

                <?php foreach ($reportOpportunities as $opportunity) { ?>

                    <?php
                    $registrationText = countRegistrations($allRecords, $opportunity["id"]);

                    if ($opportunity["status"] != "published") {
                        $registrationText = "Not accepting registrations";
                    }
                    ?>

                    <div class="table-row">

                        <div>
                            <p class="table-title"><?php echo safe($opportunity["title"]); ?></p>
                            <p class="table-date"><?php echo formatDateText($opportunity["activity_date"]); ?></p>
                        </div>

                        <div>
                            <span class="chip chip-<?php echo $opportunity["status"]; ?>">
                                <?php echo getOpportunityStatusText($opportunity["status"]); ?>
                            </span>
                        </div>

                        <p class="table-right"><?php echo $registrationText; ?></p>

                    </div>

                <?php } ?>

                <p class="report-footnote">
                    The totals above only include the activities listed here.
                    Drafts are not accepting registrations.
                </p>

            </section>

        <?php } ?>

    </main>

</div>

<?php include __DIR__ . "/../../components/footer.php"; ?>