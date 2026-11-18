<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../home.php?auth=login");
    exit;
}

$pageTitle = "Notifications";
$pageCss = "notifications";

include __DIR__ . "/../../../backend/settings/connection-with-test-mode.php";
include __DIR__ . "/../../../backend/database-queries/notification-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}

function getTimeAgoText($dateText) {
    $seconds = time() - strtotime($dateText);

    if ($seconds < 60) {
        return "Just now";
    }

    if ($seconds < 3600) {
        $minutes = floor($seconds / 60);

        if ($minutes == 1) {
            return "1 minute ago";
        }

        return $minutes . " minutes ago";
    }

    if ($seconds < 86400) {
        $hours = floor($seconds / 3600);

        if ($hours == 1) {
            return "1 hour ago";
        }

        return $hours . " hours ago";
    }

    $days = floor($seconds / 86400);

    if ($days == 1) {
        return "Yesterday";
    }

    if ($days < 7) {
        return $days . " days ago";
    }

    return date("F j, Y", strtotime($dateText));
}


$typeIcons = [
    "approved" => "fa-regular fa-calendar-check",
    "hours_verified" => "fa-solid fa-hourglass-half",
    "new_registration" => "fa-solid fa-user-group",
    "upcoming" => "fa-regular fa-calendar",
    "organization_submitted" => "fa-regular fa-building",
    "opportunity_published" => "fa-regular fa-calendar-plus",
    "opportunity_submitted" => "fa-regular fa-clock",
    "opportunity_approved" => "fa-regular fa-circle-check",
    "opportunity_rejected" => "fa-regular fa-circle-xmark",
    "opportunity_removed" => "fa-regular fa-trash-can",
    "organization_approved" => "fa-regular fa-circle-check",
    "organization_declined" => "fa-regular fa-circle-xmark",
    "organization_suspended" => "fa-solid fa-ban"
];


$filter = "all";

if (isset($_GET["filter"]) && $_GET["filter"] == "unread") {
    $filter = "unread";
}

$allNotifications = getMyNotifications($connection, $_SESSION["user_id"]);

$unreadCount = 0;

foreach ($allNotifications as $notification) {
    if ($notification["is_read"] == 0) {
        $unreadCount = $unreadCount + 1;
    }
}

$visibleNotifications = [];

foreach ($allNotifications as $notification) {
    if ($filter == "all" || $notification["is_read"] == 0) {
        $visibleNotifications[] = $notification;
    }
}

$allTabClass = "tab";
$unreadTabClass = "tab";

if ($filter == "all") {
    $allTabClass = "tab tab-active";
} else {
    $unreadTabClass = "tab tab-active";
}

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <div class="notifications-header">

            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="page-subtitle">Stay updated on your volunteering activities and organization.</p>
            </div>

            <?php if ($unreadCount > 0) { ?>
                <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/mark-notifications-read-action.php">
                    <button type="submit" class="mark-read-button">Mark all as read</button>
                </form>
            <?php } ?>

        </div>


        <section class="notifications-card">

            <div class="tabs">
                <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/notifications.php"
                   class="<?php echo $allTabClass; ?>">All</a>

                <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/notifications.php?filter=unread"
                   class="<?php echo $unreadTabClass; ?>">Unread (<?php echo $unreadCount; ?>)</a>
            </div>

            <?php if (count($allNotifications) == 0) { ?>

                <div class="empty-state">
                    <h3>No notifications yet</h3>
                    <p>Updates about your registrations, volunteer hours, and organization will show up here.</p>
                </div>

            <?php } else if (count($visibleNotifications) == 0) { ?>

                <div class="empty-state">
                    <h3>You're all caught up</h3>
                    <p>You don't have any unread notifications.</p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/notifications.php" class="empty-link">
                        View all notifications
                    </a>
                </div>

            <?php } ?>

            <?php foreach ($visibleNotifications as $notification) { ?>

                <?php
                $icon = "fa-regular fa-bell";

                if (isset($typeIcons[$notification["type"]])) {
                    $icon = $typeIcons[$notification["type"]];
                }

                $itemClass = "notification-item";

                if ($notification["is_read"] == 0) {
                    $itemClass = "notification-item unread";
                }
                ?>

                <div class="<?php echo $itemClass; ?>">

                    <span class="notification-icon"><i class="<?php echo $icon; ?>"></i></span>

                    <div class="notification-text">
                        <h3><?php echo safe($notification["title"]); ?></h3>
                        <p><?php echo safe($notification["message"]); ?></p>
                        <span><?php echo getTimeAgoText($notification["created_at"]); ?></span>
                    </div>

                    <div class="notification-actions">

                        <?php if ($notification["is_read"] == 0) { ?>
                            <span class="notification-dot"></span>

                            <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/mark-notification-read-action.php">
                                <input type="hidden" name="notification_id" value="<?php echo $notification["id"]; ?>">
                                <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                                <button type="submit" class="notification-read-button">Mark as read</button>
                            </form>
                        <?php } ?>

                        <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/delete-notification-action.php">
                            <input type="hidden" name="notification_id" value="<?php echo $notification["id"]; ?>">
                            <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                            <button type="button" class="notification-delete-button" title="Delete notification">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>

                    </div>

                </div>

            <?php } ?>

        </section>

    </main>

</div>

<div class="delete-overlay" id="delete-overlay">

    <div class="delete-card">
        <h2 class="delete-title">Delete notification?</h2>
        <p class="delete-text">This notification will be removed permanently. This cannot be undone.</p>

        <div class="delete-buttons">
            <button type="button" class="delete-cancel" id="delete-cancel">Cancel</button>
            <button type="button" class="delete-confirm" id="delete-confirm">Delete</button>
        </div>
    </div>

</div>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/notifications.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>