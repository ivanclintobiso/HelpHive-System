<?php
$pageTitle = "Opportunity Requests";
$pageSubtitle = "Every new opportunity must be approved before volunteers can see it.";
$pageCss = "admin";

include __DIR__ . "/../../../backend/access-control/admin-required.php";

$searchText = getTextFromUrl("search");
$pageNumber = (int) getTextFromUrl("page");
$filterValue = "all";

if ($pageNumber < 1) {
    $pageNumber = 1;
}

$opportunities = getOpportunitiesForAdmin($connection);

$visibleOpportunities = [];

foreach ($opportunities as $opportunity) {
    if ($opportunity["status"] != "pending_review") {
        continue;
    }

    $searchableText = $opportunity["title"] . " " . $opportunity["organization_name"];

    if ($searchText != "" && stripos($searchableText, $searchText) === false) {
        continue;
    }

    $visibleOpportunities[] = $opportunity;
}

$totalRows = count($visibleOpportunities);
$totalPages = getTotalPages($totalRows, 10);

if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

$rows = getPageRows($visibleOpportunities, $pageNumber, 10);

$returnUrl = makeListLink($searchText, $filterValue, $pageNumber);

$filterOptions = [];
$searchPlaceholder = "Search by title or organization";

include __DIR__ . "/../../components/admin-top.php";
?>

<section class="admin-panel">

    <?php include __DIR__ . "/../../components/admin-toolbar.php"; ?>

    <div id="results">

        <div class="table-wrap">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Opportunity</th>
                        <th>Organization</th>
                        <th>Activity Date</th>
                        <th>Submitted</th>
                        <th class="actions-head">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($rows) == 0) { ?>
                        <tr>
                            <td colspan="5" class="table-empty">No pending opportunity requests.</td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $opportunity) { ?>

                        <tr>

                            <td>
                                <p class="cell-title"><?php echo safe($opportunity["title"]); ?></p>
                                <p class="cell-note"><?php echo safe($opportunity["short_description"]); ?></p>
                            </td>

                            <td><?php echo safe($opportunity["organization_name"]); ?></td>

                            <td><?php echo formatAdminDate($opportunity["activity_date"]); ?></td>

                            <td><?php echo formatAdminDate($opportunity["created_at"]); ?></td>

                            <td>
                                <div class="actions-cell">
                                    <button type="button"
                                            class="action-button neutral"
                                            data-target="opp-popup-<?php echo $opportunity["id"]; ?>">View</button>
                                </div>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </div>

        <?php foreach ($rows as $opportunity) { ?>
            <?php include __DIR__ . "/../../components/admin-opportunity-popup.php"; ?>
        <?php } ?>

        <?php include __DIR__ . "/../../components/admin-pagination.php"; ?>

    </div>

</section>

<?php include __DIR__ . "/../../components/admin-bottom.php"; ?>