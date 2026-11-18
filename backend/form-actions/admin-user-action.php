<?php
include __DIR__ . "/../access-control/admin-required.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . BASE_URL . "/frontend/pages/admin/dashboard.php");
    exit;
}

$userId = (int) getPostText("user_id");
$action = getPostText("action");
$reason = getPostText("reason");

$users = getAllUsers($connection);
$user = findRowById($users, $userId);

if ($user == null) {
    finishAdminAction("That user was not found.", true);
}

$fullName = $user["first_name"] . " " . $user["last_name"];

if ($user["role"] == "admin") {
    finishAdminAction("You cannot ban or change another admin.", true);
}

if ($userId == $_SESSION["user_id"]) {
    finishAdminAction("You cannot ban yourself.", true);
}

if ($action == "ban") {
    if ($reason == "") {
        finishAdminAction("Please write a reason for banning.", true);
    }

    updateUserStatus($connection, $userId, "banned", $reason);

    finishAdminAction($fullName . " was banned.", false);
}

if ($action == "unban") {
    updateUserStatus($connection, $userId, "active", "");

    finishAdminAction($fullName . " can log in again.", false);
}

finishAdminAction("Unknown action.", true);