<?php
$pageTitle = "Messages";
$pageSubtitle = "Messages sent from the Contact Us page.";
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

$messages = getMessagesForAdmin($connection);

$visibleMessages = [];

foreach ($messages as $message) {
    $searchableText = $message["full_name"] . " " . $message["email"] . " " . $message["subject"];

    if ($searchText != "" && stripos($searchableText, $searchText) === false) {
        continue;
    }

    if ($filterValue == "unread" && $message["is_read"] != 0) {
        continue;
    }

    if ($filterValue == "read" && $message["is_read"] != 1) {
        continue;
    }

    $visibleMessages[] = $message;
}

$totalRows = count($visibleMessages);
$totalPages = getTotalPages($totalRows, 10);

if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

$rows = getPageRows($visibleMessages, $pageNumber, 10);

$returnUrl = makeListLink($searchText, $filterValue, $pageNumber);

$filterOptions = [
    "all" => "All",
    "unread" => "Unread",
    "read" => "Read"
];

$searchPlaceholder = "Search by name, email or subject";

include __DIR__ . "/../../components/admin-top.php";
?>

<section class="admin-panel">

    <?php include __DIR__ . "/../../components/admin-toolbar.php"; ?>

    <div id="results">

        <div class="table-wrap">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>From</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th>Status</th>
                        <th class="actions-head">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($rows) == 0) { ?>
                        <tr>
                            <td colspan="5" class="table-empty">No messages found.</td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $message) { ?>

                        <?php
                        $statusKey = "read";

                        if ($message["is_read"] == 0) {
                            $statusKey = "unread";
                        }

                        $messagePreview = $message["message"];

                        if (strlen($messagePreview) > 70) {
                            $messagePreview = substr($messagePreview, 0, 70) . "...";
                        }
                        ?>

                        <tr>

                            <td>
                                <div class="person-cell">
                                    <img src="<?php echo getProfileImageUrl($message["profile_image"]); ?>"
                                         alt="Profile picture"
                                         class="person-avatar">

                                    <div>
                                        <p class="cell-title"><?php echo safe($message["full_name"]); ?></p>
                                        <p class="cell-note"><?php echo safe($message["email"]); ?></p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <p class="cell-title"><?php echo safe($message["subject"]); ?></p>

                                <p class="cell-note"><?php echo safe($messagePreview); ?></p>
                            </td>

                            <td><?php echo formatAdminDate($message["created_at"]); ?></td>

                            <td>
                                <span class="status-chip <?php echo getStatusColor($statusKey); ?>">
                                    <?php echo getStatusLabel($statusKey); ?>
                                </span>
                            </td>

                            <td>
                                <div class="actions-cell">
                                    <button type="button"
                                            class="action-button neutral"
                                            data-target="msg-popup-<?php echo $message["id"]; ?>">View Message</button>
                                </div>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </div>

        <?php foreach ($rows as $message) { ?>
            <?php include __DIR__ . "/../../components/admin-message-popup.php"; ?>
        <?php } ?>

        <?php include __DIR__ . "/../../components/admin-pagination.php"; ?>

    </div>

</section>

<?php include __DIR__ . "/../../components/admin-bottom.php"; ?>