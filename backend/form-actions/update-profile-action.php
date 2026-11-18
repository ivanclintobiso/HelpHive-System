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

$currentProfile = getUserProfile($connection, $_SESSION["user_id"]);

$profileImage = "profile1-defaultrandomprofile.png";

if (!empty($currentProfile["profile_image"])) {
    $profileImage = $currentProfile["profile_image"];
}

$photoExtension = "";
$hasNewPhoto = false;

if (isset($_FILES["profile_photo"]) && $_FILES["profile_photo"]["error"] != UPLOAD_ERR_NO_FILE) {
    $photo = $_FILES["profile_photo"];

    if ($photo["error"] != UPLOAD_ERR_OK) {
        $errors[] = "The photo could not be uploaded. Please try a smaller image.";
    } else if ($photo["size"] > 2 * 1024 * 1024) {
        $errors[] = "The photo must be 2MB or smaller.";
    } else {
        $imageInfo = getimagesize($photo["tmp_name"]);

        $allowedTypes = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/gif" => "gif",
            "image/webp" => "webp"
        ];

        if ($imageInfo == false) {
            $errors[] = "Please choose an image file only.";
        } else if (!isset($allowedTypes[$imageInfo["mime"]])) {
            $errors[] = "Only JPG, PNG, GIF or WEBP images are allowed.";
        } else {
            $photoExtension = $allowedTypes[$imageInfo["mime"]];
            $hasNewPhoto = true;
        }
    }
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

if ($hasNewPhoto) {
    $uploadFolder = __DIR__ . "/../../frontend/assets/images/profile-uploads/";

    if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    $newFileName = "user" . $_SESSION["user_id"] . "-" . time() . "." . $photoExtension;

    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $uploadFolder . $newFileName)) {
        $profileImage = "profile-uploads/" . $newFileName;
    }
}

$profile = [
    "first_name" => $firstName,
    "last_name" => $lastName,
    "email" => $email,
    "phone" => $phone,
    "location" => $location,
    "about_me" => $aboutMe,
    "causes" => $causes,
    "skills" => $skills,
    "profile_image" => $profileImage
];

$isSaved = updateUserProfile($connection, $_SESSION["user_id"], $profile);

if (!$isSaved) {
    $_SESSION["profile_errors"] = ["Something went wrong while saving. Please try again."];
    $_SESSION["profile_old"] = $old;

    header("Location: " . $profilePage);
    exit;
}

$_SESSION["user_name"] = $firstName;
$_SESSION["profile_image"] = $profileImage;
$_SESSION["profile_success"] = "Your profile was updated.";

header("Location: " . $profilePage);
exit;