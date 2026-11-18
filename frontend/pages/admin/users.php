<?php
$pageTitle = "Users";
$pageSubtitle = "See everyone who joined HelpHive. You can ban a user who breaks the rules.";
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

$users = getAllUsers($connection);

$visibleUsers = [];

foreach ($users as $user) {
    $searchableText = $user["first_name"] . " " . $user["last_name"] . " " . $user["email"];

    if ($searchText != "" && stripos($searchableText, $searchText) === false) {
        continue;
    }

    if ($filterValue != "all" && $user["status"] != $filterValue) {
        continue;
    }

    $visibleUsers[] = $user;
}

$totalRows = count($visibleUsers);
$totalPages = getTotalPages($totalRows, 10);

if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

$rows = getPageRows($visibleUsers, $pageNumber, 10);

$returnUrl = makeListLink($searchText, $filterValue, $pageNumber);

$filterOptions = [
    "all" => "All",
    "active" => "Active",
    "banned" => "Banned"
];

$searchPlaceholder = "Search by name or email";

include __DIR__ . "/../../components/admin-top.php";
?>

<section class="admin-panel">

    <?php include __DIR__ . "/../../components/admin-toolbar.php"; ?>

    <div id="results">

        <div class="table-wrap">
            <table class="admin-table">

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="actions-head">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($rows) == 0) { ?>
                        <tr>
                            <td colspan="5" class="table-empty">No users found.</td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $user) { ?>

                        <?php
                        $fullName = $user["first_name"] . " " . $user["last_name"];

                        $canBan = true;

                        if ($user["role"] == "admin") {
                            $canBan = false;
                        }

                        if ($user["id"] == $_SESSION["user_id"]) {
                            $canBan = false;
                        }
                        ?>

                        <tr>

                            <td>
                                <div class="person-cell">
                                    <img src="<?php echo getProfileImageUrl($user["profile_image"]); ?>"
                                         alt="Profile picture"
                                         class="person-avatar">

                                    <div>
                                        <p class="cell-title"><?php echo safe($fullName); ?></p>
                                        <p class="cell-note"><?php echo safe($user["email"]); ?></p>
                                    </div>
                                </div>
                            </td>

                            <td><?php echo ucfirst($user["role"]); ?></td>

                            <td>
                                <span class="status-chip <?php echo getStatusColor($user["status"]); ?>">
                                    <?php echo getStatusLabel($user["status"]); ?>
                                </span>
                            </td>

                            <td><?php echo formatAdminDate($user["created_at"]); ?></td>

                            <td>
                                <div class="actions-cell">

                                    <?php if (!$canBan) { ?>

                                        <span class="cell-note">No actions</span>

                                    <?php } else if ($user["status"] == "banned") { ?>

                                        <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/admin-user-action.php" class="action-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                                            <input type="hidden" name="action" value="unban">
                                            <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                                            <button type="button"
                                                    class="action-button approve confirm-button"
                                                    data-title="Unban user?"
                                                    data-message="<?php echo safe("Unban " . $user["first_name"] . " " . $user["last_name"] . "? They will be able to use HelpHive again."); ?>"
                                                    data-yes="Unban"
                                                    data-style="approve"
                                                    data-reason="no">Unban</button>
                                        </form>

                                    <?php } else { ?>

                                        <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/admin-user-action.php" class="action-form">
                                            <input type="hidden" name="user_id" value="<?php echo $user["id"]; ?>">
                                            <input type="hidden" name="action" value="ban">
                                            <input type="hidden" name="reason" value="" class="reason-field">
                                            <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                                            <button type="button"
                                                    class="action-button danger confirm-button"
                                                    data-title="Ban user?"
                                                    data-message="<?php echo safe("Ban " . $fullName . "? Please write the reason below."); ?>"
                                                    data-label="Reason (the user will see this when they log in)"
                                                    data-yes="Ban User"
                                                    data-style="danger"
                                                    data-reason="yes">Ban</button>
                                        </form>

                                    <?php } ?>

                                </div>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </div>

        <?php include __DIR__ . "/../../components/admin-pagination.php"; ?>

    </div>

</section>

<?php include __DIR__ . "/../../components/admin-bottom.php"; ?>