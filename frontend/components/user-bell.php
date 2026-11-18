<?php
include_once __DIR__ . "/../../backend/settings/connection-with-test-mode.php";
include_once __DIR__ . "/../../backend/database-queries/notification-queries.php";

$bellAllNotifications = getMyNotifications($connection, $_SESSION["user_id"]);

$bellUnreadCount = 0;
$bellLatestNotifications = [];

foreach ($bellAllNotifications as $bellNotification) {
    if ($bellNotification["is_read"] == 0) {
        $bellUnreadCount = $bellUnreadCount + 1;
    }

    if (count($bellLatestNotifications) < 4) {
        $bellLatestNotifications[] = $bellNotification;
    }
}

$notificationsPageUrl = BASE_URL . "/frontend/pages/volunteer/notifications.php";
?>

<div class="bell-menu" id="bell-menu">

    <button type="button" class="header-bell" id="bell-button" title="Notifications">
        <i class="fa-regular fa-bell"></i>

        <?php if ($bellUnreadCount > 0) { ?>
            <span class="bell-badge"><?php echo $bellUnreadCount; ?></span>
        <?php } ?>
    </button>

    <div class="bell-dropdown">

        <p class="bell-heading">Notifications</p>

        <?php if (count($bellLatestNotifications) == 0) { ?>
            <p class="bell-empty">You have no notifications yet.</p>
        <?php } ?>

        <?php foreach ($bellLatestNotifications as $bellNotification) { ?>
            <a href="<?php echo $notificationsPageUrl; ?>" class="bell-item">
                <span class="bell-item-title"><?php echo htmlspecialchars($bellNotification["title"]); ?></span>
                <span class="bell-item-note"><?php echo htmlspecialchars($bellNotification["message"]); ?></span>
            </a>
        <?php } ?>

        <a href="<?php echo $notificationsPageUrl; ?>" class="bell-see-all">See all notifications</a>

    </div>

</div>
