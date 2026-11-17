<?php
session_start();

include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/user-queries.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php?auth=login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isOn = 0;

    if (isset($_POST["email_notifications"])) {
        $isOn = 1;
    }

    updateEmailNotifications($connection, $_SESSION["user_id"], $isOn);
}

header("Location: " . BASE_URL . "/frontend/pages/volunteer/account-settings.php");
exit;