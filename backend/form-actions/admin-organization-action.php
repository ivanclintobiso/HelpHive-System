<?php
include __DIR__ . "/../access-control/admin-required.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . BASE_URL . "/frontend/pages/admin/dashboard.php");
    exit;
}

$organizationId = (int) getPostText("organization_id");
$action = getPostText("action");
$reason = getPostText("reason");

$organizations = getOrganizationsForAdmin($connection);
$organization = findRowById($organizations, $organizationId);

if ($organization == null) {
    finishAdminAction("That organization was not found.", true);
}

$name = $organization["organization_name"];
$ownerId = $organization["user_id"];

if ($action == "approve") {
    updateOrganizationStatus($connection, $organizationId, "approved", "");

    notifyUser($connection, $ownerId, "organization_approved", "Organization approved!", "Your organization " . $name . " was approved. You can now post opportunities.");

    finishAdminAction($name . " was approved.", false);
}

if ($action == "reject") {
    if ($reason == "") {
        finishAdminAction("Please write a reason for rejecting.", true);
    }

    updateOrganizationStatus($connection, $organizationId, "declined", $reason);

    notifyUser($connection, $ownerId, "organization_declined", "Organization declined", "Your organization " . $name . " was declined. Reason: " . $reason);

    finishAdminAction($name . " was rejected.", false);
}

if ($action == "suspend") {
    if ($reason == "") {
        finishAdminAction("Please write a reason for suspending.", true);
    }

    updateOrganizationStatus($connection, $organizationId, "suspended", $reason);

    notifyUser($connection, $ownerId, "organization_suspended", "Organization suspended", "Your organization " . $name . " was suspended by the admin. Reason: " . $reason);

    finishAdminAction($name . " was suspended.", false);
}

if ($action == "reactivate") {
    updateOrganizationStatus($connection, $organizationId, "approved", "");

    notifyUser($connection, $ownerId, "organization_approved", "Organization reactivated", "Your organization " . $name . " is active again.");

    finishAdminAction($name . " was reactivated.", false);
}

finishAdminAction("Unknown action.", true);