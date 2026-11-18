<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";
include __DIR__ . "/../settings/database-connection.php";
include __DIR__ . "/../database-queries/user-queries.php";

$homePage = BASE_URL . "/frontend/pages/home.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $homePage);
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

function getPostPassword() {
    if (!isset($_POST["password"])) {
        return "";
    }

    if (is_array($_POST["password"])) {
        return "";
    }

    return $_POST["password"];
}

function getReturnUrl($homePage) {
    $url = getPostText("return_url");

    if ($url == "") {
        return $homePage;
    }

    if (substr($url, 0, 1) != "/") {
        return $homePage;
    }

    if (substr($url, 0, 2) == "//") {
        return $homePage;
    }

    if (strpos($url, "\\") !== false) {
        return $homePage;
    }

    return $url;
}


$returnUrl = getReturnUrl($homePage);

$firstName = getPostText("first_name");
$lastName = getPostText("last_name");
$email = getPostText("email");
$password = getPostPassword();
$location = getPostText("location");

$errors = [];

if ($firstName == "") {
    $errors[] = "Please enter your first name.";
}

if ($lastName == "") {
    $errors[] = "Please enter your last name.";
}

if ($email == "") {
    $errors[] = "Please enter your email.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

if ($password == "") {
    $errors[] = "Please enter your password.";
} else if (strlen($password) < 8) {
    $errors[] = "Your password must be at least 8 characters.";
}

if (count($errors) == 0) {
    $existingUser = getUserByEmail($connection, $email);

    if ($existingUser != null) {
        $errors[] = "This email is already registered. Please log in instead.";
    }
}

if (count($errors) > 0) {
    $_SESSION["auth_errors"] = $errors;
    $_SESSION["auth_old"] = [
        "first_name" => $firstName,
        "last_name" => $lastName,
        "email" => $email,
        "location" => $location
    ];
    $_SESSION["auth_mode"] = "signup";

    header("Location: " . $returnUrl);
    exit;
}

$user = [
    "first_name" => $firstName,
    "last_name" => $lastName,
    "email" => $email,
    "password" => password_hash($password, PASSWORD_DEFAULT),
    "location" => $location,
    "profile_image" => getRandomProfileImage()
];

$userId = saveUser($connection, $user);

session_regenerate_id(true);

$_SESSION["user_id"] = $userId;
$_SESSION["user_name"] = $firstName;
$_SESSION["profile_image"] = $user["profile_image"];

header("Location: " . $returnUrl);
exit;