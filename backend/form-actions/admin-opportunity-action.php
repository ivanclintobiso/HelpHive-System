<?php
include __DIR__ . "/../access-control/admin-required.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . BASE_URL . "/frontend/pages/admin/dashboard.php");
    exit;
}

$opportunityId = (int) getPostText("opportunity_id");
$action = getPostText("action");
$reason = getPostText("reason");

$opportunities = getOpportunitiesForAdmin($connection);
$opportunity = findRowById($opportunities, $opportunityId);

if ($opportunity == null) {
    finishAdminAction("That opportunity was not found.", true);
}

$title = $opportunity["title"];
$ownerId = $opportunity["owner_id"];

if ($action == "approve") {
    updateOpportunityStatus($connection, $opportunityId, "published", "");

    notifyUser($connection, $ownerId, "opportunity_approved", "Opportunity approved!", "Your opportunity " . $title . " was approved and is now live.");

    finishAdminAction($title . " was approved.", false);
}

if ($action == "reject") {
    if ($reason == "") {
        finishAdminAction("Please write a reason for rejecting.", true);
    }

    updateOpportunityStatus($connection, $opportunityId, "rejected", $reason);

    notifyUser($connection, $ownerId, "opportunity_rejected", "Opportunity needs changes", "Your opportunity " . $title . " was rejected. Reason: " . $reason);

    finishAdminAction($title . " was rejected.", false);
}

if ($action == "remove") {
    if ($reason == "") {
        finishAdminAction("Please write a reason for removing.", true);
    }

    updateOpportunityStatus($connection, $opportunityId, "removed", $reason);

    notifyUser($connection, $ownerId, "opportunity_removed", "Opportunity removed", "Your opportunity " . $title . " was removed by the admin. Reason: " . $reason);

    finishAdminAction($title . " was removed.", false);
}

if ($action == "restore") {
    updateOpportunityStatus($connection, $opportunityId, "published", "");

    notifyUser($connection, $ownerId, "opportunity_approved", "Opportunity restored", "Your opportunity " . $title . " is live again.");

    finishAdminAction($title . " was restored.", false);
}

finishAdminAction("Unknown action.", true);