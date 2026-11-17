<?php
include __DIR__ . "/../access-control/organization-required.php";
include __DIR__ . "/../database-queries/notification-queries.php";

$volunteersPage = BASE_URL . "/frontend/pages/organization/applicants.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $volunteersPage);
    exit;
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


$recordId = (int) getPostText("record_id");
$returnOpportunity = (int) getPostText("opportunity");
$returnStatus = getPostText("status");

$validStatuses = ["registered", "pending", "verified"];

$returnQuery = [];

if ($returnOpportunity > 0) {
    $returnQuery["opportunity"] = $returnOpportunity;
}

if (in_array($returnStatus, $validStatuses)) {
    $returnQuery["status"] = $returnStatus;
}

$returnPage = $volunteersPage;

if (count($returnQuery) > 0) {
    $returnPage = $volunteersPage . "?" . http_build_query($returnQuery);
}


$records = getOrganizationVolunteerRecords($connection, $organization["id"]);

$selectedRecord = null;

foreach ($records as $record) {
    if ($record["id"] == $recordId) {
        $selectedRecord = $record;
    }
}

if ($selectedRecord == null) {
    header("Location: " . $returnPage);
    exit;
}

$opportunity = getOrganizationOpportunity($connection, $organization["id"], $selectedRecord["opportunity_id"]);

if ($opportunity == null) {
    header("Location: " . $returnPage);
    exit;
}

$hours = getExpectedHours($opportunity);

confirmVolunteerHours($connection, $organization["id"], $recordId, $hours);

$volunteerId = getApplicationUserId($connection, $recordId);

if ($volunteerId > 0) {
    createNotification($connection, $volunteerId, "hours_verified", "Volunteer hours verified", "Your " . formatHours($hours) . " for " . $opportunity["title"] . " have been verified.");
}

header("Location: " . $returnPage);
exit;