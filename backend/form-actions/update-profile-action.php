<?php
session_start();

include __DIR__ . "/../settings/connection-with-test-mode.php";
include __DIR__ . "/../database-queries/user-queries.php";

$loginPage = BASE_URL . "/frontend/pages/home.php?auth=login";
$profilePage = BASE_URL . "/frontend/pages/volunteer/my-profile.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . $loginPage);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $profilePage);
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


$fullName = getPostText("full_name");
$email = getPostText("email");
$phone = getPostText("phone");
$location = getPostText("location");
$aboutMe = getPostText("about_me");
$causes = getPostText("causes");
$skills = getPostText("skills");

$old = [
    "full_name" => $fullName,
    "email" => $email,
    "phone" => $phone,
    "location" => $location,
    "about_me" => $aboutMe,
    "causes" => $causes,
    "skills" => $skills
];

$errors = [];

if ($fullName == "") {
    $errors[] = "Please enter your full name.";
}

if ($email == "") {
    $errors[] = "Please enter your email.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($phone != "") {
    if (!preg_match("/^[0-9+() -]+$/", $phone) || strlen($phone) > 30) {
        $errors[] = "Phone number can only have numbers, spaces, +, - and ( ).";
    }
}

if (strlen($aboutMe) > 255 || strlen($causes) > 255 || strlen($skills) > 255) {
    $errors[] = "About me, causes and skills must be 255 characters or less.";
}

if (count($errors) == 0 && $connection != null) {
    $existingUser = getUserByEmail($connection, $email);

    if ($existingUser != null && $existingUser["id"] != $_SESSION["user_id"]) {
        $errors[] = "That email is already used by another account.";
    }
}

if (count($errors) > 0) {
    $_SESSION["profile_errors"] = $errors;
    $_SESSION["profile_old"] = $old;

    header("Location: " . $profilePage);
    exit;
}


$nameParts = explode(" ", $fullName, 2);

$firstName = $nameParts[0];
$lastName = "";

if (isset($nameParts[1])) {
    $lastName = trim($nameParts[1]);
}

$profile = [
    "first_name" => $firstName,
    "last_name" => $lastName,
    "email" => $email,
    "phone" => $phone,
    "location" => $location,
    "about_me" => $aboutMe,
    "causes" => $causes,
    "skills" => $skills
];

$isSaved = updateUserProfile($connection, $_SESSION["user_id"], $profile);

if (!$isSaved) {
    $_SESSION["profile_errors"] = ["Something went wrong while saving. Please try again."];
    $_SESSION["profile_old"] = $old;

    header("Location: " . $profilePage);
    exit;
}

$_SESSION["user_name"] = $firstName;
$_SESSION["profile_success"] = "Your profile was updated.";

header("Location: " . $profilePage);
exit;