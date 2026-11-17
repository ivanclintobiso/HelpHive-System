<?php
include_once __DIR__ . "/app-settings.php";

$connection = null;

$isTestUser = false;

if (isset($_SESSION["user_id"])) {
    if (TEST_ACCOUNT_ENABLED && $_SESSION["user_id"] == TEST_ACCOUNT_ID) {
        $isTestUser = true;
    }
}

if (!$isTestUser) {
    include __DIR__ . "/database-connection.php";
}