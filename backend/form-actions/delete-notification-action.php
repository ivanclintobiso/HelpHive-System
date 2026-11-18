<?php
session_start();

include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/notification-queries.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php?auth=login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["notification_id"])) {
    $notificationId = (int) $_POST["notification_id"];

    deleteNotification($connection, $_SESSION["user_id"], $notificationId);
}

$redirectUrl = BASE_URL . "/frontend/pages/volunteer/notifications.php";

if (isset($_POST["filter"]) && $_POST["filter"] == "unread") {
    $redirectUrl = $redirectUrl . "?filter=unread";
}

header("Location: " . $redirectUrl);
exit;