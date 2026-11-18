<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../settings/app-settings.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php?auth=login");
    exit;
}

include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/organization-queries.php";
include __DIR__ . "/../database-queries/organization-opportunity-queries.php";
include __DIR__ . "/../database-queries/volunteer-hour-queries.php";

$organization = getMyOrganization($connection, $_SESSION["user_id"]);

if ($organization == null) {
    include __DIR__ . "/../../frontend/components/no-organization.php";
    exit;
}


function safe($text) {
    return htmlspecialchars($text);
}

function getTextFromUrl($name) {
    if (!isset($_GET[$name])) {
        return "";
    }

    if (is_array($_GET[$name])) {
        return "";
    }

    return trim($_GET[$name]);
}

function getOpportunityStatusText($status) {
    $labels = [
        "draft" => "Draft",
        "pending_review" => "Pending Review",
        "published" => "Published",
        "rejected" => "Rejected",
        "removed" => "Removed"
    ];

    if (isset($labels[$status])) {
        return $labels[$status];
    }

    return ucfirst($status);
}

function formatHours($hours) {
    $number = round($hours, 1);

    if ($number == 1) {
        return "1 hr";
    }

    return $number . " hrs";
}

function formatDateText($date) {
    if ($date == "") {
        return "No date yet";
    }

    return date("F j, Y", strtotime($date));
}

function getCauseText($causeString, $causeOptions) {
    $names = [];

    foreach (explode(",", $causeString) as $cause) {
        if (isset($causeOptions[$cause])) {
            $names[] = $causeOptions[$cause];
        }
    }

    return implode(", ", $names);
}


$causeOptions = [
    "environment" => "Environment",
    "education" => "Education",
    "community" => "Community Support",
    "animal" => "Animal Welfare"
];