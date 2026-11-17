<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";
include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/organization-queries.php";
include __DIR__ . "/../database-queries/notification-queries.php";

$registerPage = BASE_URL . "/frontend/pages/organization/register-organization.php";
$submittedPage = BASE_URL . "/frontend/pages/organization/organization-submitted.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/frontend/pages/home.php?auth=login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $registerPage);
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


$organizationTypes = [
    "Nonprofit Organization",
    "Community Group",
    "School / Educational Institution",
    "Government Organization",
    "Other"
];

$validCauses = ["environment", "education", "community", "animal"];


$organizationName = getPostText("organization_name");
$organizationType = getPostText("organization_type");
$about = getPostText("about");
$cityProvince = getPostText("city_province");
$streetAddress = getPostText("street_address");
$email = getPostText("email");
$contactNumber = getPostText("contact_number");
$website = getPostText("website");
$contactName = getPostText("contact_name");
$position = getPostText("position");

$causeAreas = [];
if (isset($_POST["cause_areas"]) && is_array($_POST["cause_areas"])) {
    $causeAreas = $_POST["cause_areas"];
}

$old = [
    "organization_name" => $organizationName,
    "organization_type" => $organizationType,
    "about" => $about,
    "cause_areas" => $causeAreas,
    "city_province" => $cityProvince,
    "street_address" => $streetAddress,
    "email" => $email,
    "contact_number" => $contactNumber,
    "website" => $website,
    "contact_name" => $contactName,
    "position" => $position
];


$errors = [];

if ($organizationName == "") {
    $errors[] = "Organization name is required.";
}

if (!in_array($organizationType, $organizationTypes)) {
    $errors[] = "Please choose an organization type.";
}

if ($about == "") {
    $errors[] = "Please tell us about your organization.";
}

if (count($causeAreas) == 0) {
    $errors[] = "Please select at least one cause area.";
}

foreach ($causeAreas as $cause) {
    if (!in_array($cause, $validCauses)) {
        $errors[] = "One of the selected cause areas is not valid.";
        break;
    }
}

if ($cityProvince == "") {
    $errors[] = "City / Province is required.";
}

if ($email == "") {
    $errors[] = "Organization email is required.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid organization email.";
}

if ($website != "" && !filter_var($website, FILTER_VALIDATE_URL)) {
    $errors[] = "Please enter a valid website link, for example https://example.org.";
}

if ($contactName == "") {
    $errors[] = "Contact person's full name is required.";
}

if ($position == "") {
    $errors[] = "Position / Role is required.";
}


$hasLogo = false;
$logoExtension = "";

if (isset($_FILES["logo"]) && $_FILES["logo"]["error"] != UPLOAD_ERR_NO_FILE) {

    if ($_FILES["logo"]["error"] != UPLOAD_ERR_OK) {
        $errors[] = "The logo could not be uploaded. Please try again.";
    } else {
        $hasLogo = true;
        $logoExtension = strtolower(pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION));

        if ($logoExtension != "jpg" && $logoExtension != "jpeg" && $logoExtension != "png") {
            $errors[] = "The logo must be a JPG or PNG image.";
        }

        if ($_FILES["logo"]["size"] > 2 * 1024 * 1024) {
            $errors[] = "The logo must be 2 MB or smaller.";
        }
    }
}


if (count($errors) > 0) {
    $_SESSION["organization_errors"] = $errors;
    $_SESSION["organization_old"] = $old;

    header("Location: " . $registerPage);
    exit;
}


$logoFileName = "";

if ($hasLogo) {
    $uploadFolder = __DIR__ . "/../../frontend/assets/uploads/logos/";

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    $logoFileName = uniqid() . "." . $logoExtension;
    move_uploaded_file($_FILES["logo"]["tmp_name"], $uploadFolder . $logoFileName);
}


$organization = [
    "user_id" => $_SESSION["user_id"],
    "logo" => $logoFileName,
    "organization_name" => $organizationName,
    "organization_type" => $organizationType,
    "about" => $about,
    "cause_areas" => implode(",", $causeAreas),
    "city_province" => $cityProvince,
    "street_address" => $streetAddress,
    "email" => $email,
    "contact_number" => $contactNumber,
    "website" => $website,
    "contact_name" => $contactName,
    "position_role" => $position
];

$isSaved = saveOrganization($connection, $organization);

if ($isSaved) {
    createNotification($connection, $_SESSION["user_id"], "organization_submitted", "Organization submitted", "Your organization " . $organizationName . " was submitted for review.");

    header("Location: " . $submittedPage);
}

$_SESSION["organization_errors"] = ["Something went wrong while saving. Please try again."];
$_SESSION["organization_old"] = $old;

header("Location: " . $registerPage);
exit;