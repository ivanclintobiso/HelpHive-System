<?php
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "login_required"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error"]);
    exit;
}


$opportunityId = 0;

if (isset($_POST["opportunity_id"]) && !is_array($_POST["opportunity_id"])) {
    $opportunityId = (int) $_POST["opportunity_id"];
}

$mode = "toggle";

if (isset($_POST["mode"]) && $_POST["mode"] == "remove") {
    $mode = "remove";
}


include __DIR__ . "/../database-queries/opportunity-queries.php";

$opportunityExists = false;

foreach (getAllOpportunities() as $opportunity) {
    if ($opportunity["id"] == $opportunityId) {
        $opportunityExists = true;
    }
}

if (!$opportunityExists) {
    echo json_encode(["status" => "error"]);
    exit;
}


include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/saved-opportunity-queries.php";

$userId = $_SESSION["user_id"];

if ($mode == "remove") {
    removeSavedOpportunity($connection, $userId, $opportunityId);
    echo json_encode(["status" => "removed"]);
    exit;
}

if (isOpportunitySaved($connection, $userId, $opportunityId)) {
    removeSavedOpportunity($connection, $userId, $opportunityId);
    echo json_encode(["status" => "removed"]);
    exit;
}

saveOpportunity($connection, $userId, $opportunityId);
echo json_encode(["status" => "saved"]);