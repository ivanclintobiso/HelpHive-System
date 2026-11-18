<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../settings/app-settings.php";

$adminAllowed = false;

if (isset($_SESSION["user_id"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin") {
        $adminAllowed = true;
    }
}

if (!$adminAllowed) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php");
    exit;
}

include __DIR__ . "/../settings/connection-with-test-mode.php";
include_once __DIR__ . "/../database-queries/admin-queries.php";
include_once __DIR__ . "/../database-queries/notification-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}

function getProfileImageUrl($image) {
    if ($image == "" || $image == null) {
        $image = "profile1-defaultrandomprofile.png";
    }

    return BASE_URL . "/frontend/assets/images/" . $image;
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

function getPostText($name) {
    if (!isset($_POST[$name])) {
        return "";
    }

    if (is_array($_POST[$name])) {
        return "";
    }

    return trim($_POST[$name]);
}

function formatAdminDate($dateText) {
    if ($dateText == "" || $dateText == null) {
        return "No date";
    }

    return date("M j, Y", strtotime($dateText));
}

function getPageRows($rows, $pageNumber, $perPage) {
    return array_slice($rows, ($pageNumber - 1) * $perPage, $perPage);
}

function getTotalPages($rowCount, $perPage) {
    $totalPages = (int) ceil($rowCount / $perPage);

    if ($totalPages < 1) {
        $totalPages = 1;
    }

    return $totalPages;
}

function makeListLink($searchText, $filterValue, $pageNumber) {
    $fileName = basename($_SERVER["PHP_SELF"]);
    $query = [];

    if ($searchText != "") {
        $query["search"] = $searchText;
    }

    if ($filterValue != "all") {
        $query["filter"] = $filterValue;
    }

    if ($pageNumber > 1) {
        $query["page"] = $pageNumber;
    }

    if (count($query) == 0) {
        return $fileName;
    }

    return $fileName . "?" . http_build_query($query);
}

function getStatusLabel($status) {
    $labels = [
        "active" => "Active",
        "banned" => "Banned",
        "pending" => "Pending",
        "pending_review" => "Pending",
        "approved" => "Approved",
        "declined" => "Declined",
        "suspended" => "Suspended",
        "published" => "Published",
        "rejected" => "Rejected",
        "removed" => "Removed",
        "read" => "Read",
        "unread" => "Unread"
    ];

    if (isset($labels[$status])) {
        return $labels[$status];
    }

    return ucfirst($status);
}

function getStatusColor($status) {
    $yellow = ["pending", "pending_review", "unread"];
    $red = ["banned", "declined", "suspended", "rejected", "removed"];

    if (in_array($status, $yellow)) {
        return "status-yellow";
    }

    if (in_array($status, $red)) {
        return "status-red";
    }

    return "status-green";
}

function getAdminReturnUrl() {
    $url = getPostText("return_url");
    $url = str_replace(["\r", "\n"], "", $url);

    $allowedPages = [
        "dashboard.php",
        "organization-requests.php",
        "opportunity-requests.php",
        "users.php",
        "opportunities.php",
        "organizations.php",
        "messages.php"
    ];

    $pageName = strtok($url, "?");

    if (!in_array($pageName, $allowedPages, true)) {
        return "dashboard.php";
    }

    return $url;
}

function finishAdminAction($message, $isError) {
    if ($isError) {
        $_SESSION["admin_error"] = $message;
    } else {
        $_SESSION["admin_message"] = $message;
    }

    header("Location: " . BASE_URL . "/frontend/pages/admin/" . getAdminReturnUrl());
    exit;
}

function notifyUser($connection, $userId, $type, $title, $message) {
    if ($connection == null) {
        return;
    }

    createNotification($connection, $userId, $type, substr($title, 0, 150), substr($message, 0, 255));
}