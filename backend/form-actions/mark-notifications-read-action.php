<?php
session_start();

include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/notification-queries.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php?auth=login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    markAllNotificationsRead($connection, $_SESSION["user_id"]);
}

header("Location: " . BASE_URL . "/frontend/pages/volunteer/notifications.php");
exit;