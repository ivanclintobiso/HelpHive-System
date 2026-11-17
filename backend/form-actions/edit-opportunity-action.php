<?php
include __DIR__ . "/../access-control/organization-required.php";

$managePage = BASE_URL . "/frontend/pages/organization/my-organization.php";
$editPage = BASE_URL . "/frontend/pages/organization/edit-opportunity.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $managePage);
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

function getPostList($name) {
    if (!isset($_POST[$name])) {
        return [];
    }

    if (!is_array($_POST[$name])) {
        return [];
    }

    return $_POST[$name];
}

function isValidDate($text) {
    if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $text)) {
        return false;
    }

    $parts = explode("-", $text);

    return checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0]);
}

function isValidTime($text) {
    if (!preg_match("/^[0-9]{2}:[0-9]{2}$/", $text)) {
        return false;
    }

    $parts = explode(":", $text);

    if ((int) $parts[0] > 23 || (int) $parts[1] > 59) {
        return false;
    }

    return true;
}


$opportunityId = (int) getPostText("opportunity_id");
$existingOpportunity = getOrganizationOpportunity($connection, $organization["id"], $opportunityId);

if ($existingOpportunity == null || $existingOpportunity["status"] != "published") {
    header("Location: " . $managePage);
    exit;
}

$errorPage = $editPage . "?id=" . $opportunityId;


$locationTypes = ["On-site", "Remote"];
$validGoodFor = ["students", "beginners", "groups", "individuals"];
$validDurations = ["one-day", "few-days", "ongoing"];
$allowedImageTypes = ["jpg", "jpeg", "png"];

$title = getPostText("title");
$causes = getPostList("cause");
$shortDescription = getPostText("short_description");
$about = getPostText("about");
$whatYoullDo = getPostText("what_youll_do");
$activityDate = getPostText("activity_date");
$locationType = getPostText("location_type");
$startTime = getPostText("start_time");
$endTime = getPostText("end_time");
$location = getPostText("location");
$volunteersNeeded = (int) getPostText("volunteers_needed");
$goodFor = getPostList("good_for");
$duration = getPostText("duration");
$skills = getPostText("skills");
$instructions = getPostText("instructions");


$errors = [];

if ($title == "") {
    $errors[] = "Please enter the opportunity title.";
}

if (strlen($title) > 150) {
    $errors[] = "The title must be 150 characters or less.";
}

if (count($causes) == 0) {
    $errors[] = "Please choose at least one cause area.";
}

foreach ($causes as $cause) {
    if (!isset($causeOptions[$cause])) {
        $errors[] = "One of the selected cause areas is not valid.";
    }
}

if ($shortDescription == "") {
    $errors[] = "Please enter a short description.";
}

if (strlen($shortDescription) > 255) {
    $errors[] = "The short description must be 255 characters or less.";
}

if ($about == "") {
    $errors[] = "Please tell volunteers about this opportunity.";
}

if ($whatYoullDo == "") {
    $errors[] = "Please list what volunteers will do.";
}

if (!isValidDate($activityDate)) {
    $errors[] = "Please choose the activity date.";
} elseif ($activityDate < date("Y-m-d") && $activityDate != $existingOpportunity["activity_date"]) {
    $errors[] = "The activity date cannot be in the past.";
}

if ($locationType == "") {
    $errors[] = "Please choose a location type.";
} elseif (!in_array($locationType, $locationTypes)) {
    $errors[] = "Please choose a valid location type.";
}

if (!isValidTime($startTime)) {
    $errors[] = "Please enter the start time.";
}

if (!isValidTime($endTime)) {
    $errors[] = "Please enter the end time.";
}

if (isValidTime($startTime) && isValidTime($endTime) && $endTime <= $startTime) {
    $errors[] = "The end time must be later than the start time.";
}

if ($location == "") {
    $errors[] = "Please enter the activity location.";
}

if ($volunteersNeeded < 1) {
    $errors[] = "Please enter how many volunteers you need.";
}

foreach ($goodFor as $value) {
    if (!in_array($value, $validGoodFor)) {
        $errors[] = "One of the selected Good For options is not valid.";
    }
}

if ($duration != "" && !in_array($duration, $validDurations)) {
    $errors[] = "Please choose a valid duration.";
}


$imageIsReady = false;
$imageExtension = "";

if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
    if ($_FILES["image"]["error"] != UPLOAD_ERR_OK) {
        $errors[] = "The image could not be uploaded. Please try again.";
    } else {
        $imageExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $allowedImageTypes)) {
            $errors[] = "The image must be a JPG or PNG file.";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $errors[] = "The image must be 2 MB or smaller.";
        } else {
            $imageIsReady = true;
        }
    }
}


if (count($errors) > 0) {
    $_SESSION["edit_opportunity_errors"] = $errors;
    $_SESSION["edit_opportunity_old"] = $_POST;

    header("Location: " . $errorPage);
    exit;
}


$imageFileName = $existingOpportunity["image"];

if ($imageIsReady) {
    $uploadFolder = __DIR__ . "/../../frontend/assets/uploads/opportunities/";

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    $imageFileName = uniqid() . "." . $imageExtension;
    move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFolder . $imageFileName);
}

$startTime = $startTime . ":00";
$endTime = $endTime . ":00";

$updatedOpportunity = [
    "image" => $imageFileName,
    "title" => $title,
    "cause" => implode(",", $causes),
    "short_description" => $shortDescription,
    "about" => $about,
    "what_youll_do" => $whatYoullDo,
    "activity_date" => $activityDate,
    "location_type" => $locationType,
    "start_time" => $startTime,
    "end_time" => $endTime,
    "location" => $location,
    "volunteers_needed" => $volunteersNeeded,
    "good_for" => implode(",", $goodFor),
    "duration" => $duration,
    "skills" => $skills,
    "instructions" => $instructions,
    "status" => "published"
];

$isUpdated = updateOrganizationOpportunity($connection, $organization["id"], $opportunityId, $updatedOpportunity);

if ($isUpdated) {
    unset($_SESSION["edit_opportunity_errors"]);
    unset($_SESSION["edit_opportunity_old"]);

    $_SESSION["organization_popup"] = "opportunity_updated";

    header("Location: " . $managePage);
    exit;
}

$_SESSION["edit_opportunity_errors"] = ["Something went wrong while saving. Please try again."];
$_SESSION["edit_opportunity_old"] = $_POST;

header("Location: " . $errorPage);
exit;