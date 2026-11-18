<?php
$pageTitle = "Organizations";
$pageSubtitle = "All approved organizations. Suspend one if needed.";
$pageCss = "admin";

include __DIR__ . "/../../../backend/access-control/admin-required.php";

$searchText = getTextFromUrl("search");
$filterValue = getTextFromUrl("filter");
$pageNumber = (int) getTextFromUrl("page");

if ($filterValue == "") {
    $filterValue = "all";
}

if ($pageNumber < 1) {
    $pageNumber = 1;
}

$organizations = getOrganizationsForAdmin($connection);
$allOpportunities = getOpportunitiesForAdmin($connection);

$visibleOrganizations = [];

foreach ($organizations as $organization) {
    if ($organization["status"] != "approved" && $organization["status"] != "suspended") {
        continue;
    }

    $searchableText = $organization["organization_name"] . " " . $organization["contact_name"] . " " . $organization["email"];

    if ($searchText != "" && stripos($searchableText, $searchText) === false) {
        continue;
    }

    if ($filterValue != "all" && $organization["status"] != $filterValue) {
        continue;
    }

    $visibleOrganizations[] = $organization;
}

$totalRows = count($visibleOrganizations);
$totalPages = getTotalPages($totalRows, 10);

if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

$rows = getPageRows($visibleOrganizations, $pageNumber, 10);

$returnUrl = makeListLink($searchText, $filterValue, $pageNumber);

$filterOptions = [
    "all" => "All",
    "approved" => "Approved",
    "suspended" => "Suspended"
];

$searchPlaceholder = "Search by organization or contact";

include __DIR__ . "/../../components/admin-top.php";
?>

<section class="admin-panel">

    <?php include __DIR__ . "/../../components/admin-toolbar.php"; ?>

    <div id="results">

        <div class="table-wrap">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="actions-head">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($rows) == 0) { ?>
                        <tr>
                            <td colspan="5" class="table-empty">No organizations found.</td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $organization) { ?>

                        <tr>

                            <td>
                                <p class="cell-title"><?php echo safe($organization["organization_name"]); ?></p>
                                <p class="cell-note"><?php echo safe($organization["organization_type"]); ?></p>
                            </td>

                            <td>
                                <p class="cell-title"><?php echo safe($organization["contact_name"]); ?></p>
                                <p class="cell-note"><?php echo safe($organization["email"]); ?></p>
                            </td>

                            <td><?php echo safe($organization["city_province"]); ?></td>

                            <td>
                                <span class="status-chip <?php echo getStatusColor($organization["status"]); ?>">
                                    <?php echo getStatusLabel($organization["status"]); ?>
                                </span>
                            </td>

                            <td>
                                <div class="actions-cell">
                                    <button type="button"
                                            class="action-button neutral"
                                            data-target="org-popup-<?php echo $organization["id"]; ?>">View</button>
                                </div>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </div>

        <?php foreach ($rows as $organization) { ?>
            <?php include __DIR__ . "/../../components/admin-organization-popup.php"; ?>
        <?php } ?>

        <?php include __DIR__ . "/../../components/admin-pagination.php"; ?>

    </div>

</section>

<?php include __DIR__ . "/../../components/admin-bottom.php"; ?>