<?php
session_start();

include __DIR__ . "/../../../backend/settings/app-settings.php";

$homePage = BASE_URL . "/frontend/pages/home.php";

if (isset($_SESSION["user_id"])) {
    header("Location: " . $homePage);
    exit;
}

header("Location: " . $homePage . "?auth=signup");
exit;