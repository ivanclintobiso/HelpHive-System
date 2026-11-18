<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";

$homePage = BASE_URL . "/frontend/pages/home.php";
$dashboardPage = BASE_URL . "/frontend/pages/admin/dashboard.php";

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

$email = getPostText("email");
$password = getPostPassword();

$errors = [];

if ($email == "") {
    $errors[] = "Please enter your email.";
}

if ($password == "") {
    $errors[] = "Please enter your password.";
}

$user = null;
$isTestAccount = false;
$isTestAdmin = false;
$isTestBanned = false;

if (count($errors) == 0) {

    if (TEST_ACCOUNT_ENABLED && strtolower($email) == TEST_ACCOUNT_EMAIL) {

        if ($password == TEST_ACCOUNT_PASSWORD) {
            $isTestAccount = true;
        } else {
            $errors[] = "Incorrect password. Please try again.";
        }

    } else if (TEST_ACCOUNT_ENABLED && strtolower($email) == TEST_BANNED_EMAIL) {

        if ($password == TEST_BANNED_PASSWORD) {
            $isTestBanned = true;
        } else {
            $errors[] = "Incorrect password. Please try again.";
        }

    } else if (TEST_ACCOUNT_ENABLED && strtolower($email) == TEST_ADMIN_EMAIL) {

        if ($password == TEST_ADMIN_PASSWORD) {
            $isTestAdmin = true;
        } else {
            $errors[] = "Incorrect password. Please try again.";
        }

    } else {

        include __DIR__ . "/../settings/database-connection.php";
        include_once __DIR__ . "/../database-queries/user-queries.php";

        $user = getUserByEmail($connection, $email);

        if ($user == null) {
            $errors[] = "No account was found with that email.";
        } else if (!password_verify($password, $user["password"])) {
            $errors[] = "Incorrect password. Please try again.";
        }

    }
}

if (count($errors) > 0) {
    $_SESSION["auth_errors"] = $errors;
    $_SESSION["auth_old"] = ["email" => $email];
    $_SESSION["auth_mode"] = "login";

    header("Location: " . $returnUrl);
    exit;
}

session_regenerate_id(true);

if ($isTestAdmin) {
    $_SESSION["user_id"] = TEST_ADMIN_ID;
    $_SESSION["user_name"] = "Admin";
    $_SESSION["role"] = "admin";
    $_SESSION["profile_image"] = "profile1-defaultrandomprofile.png";
    unset($_SESSION["test_admin_data"]);

    header("Location: " . $dashboardPage);
    exit;
}

if ($isTestBanned) {
    $_SESSION["user_id"] = TEST_BANNED_ID;
    $_SESSION["user_name"] = "Banned";
    $_SESSION["role"] = "volunteer";
    $_SESSION["profile_image"] = "profile2-defaultrandomprofile.png";
    $_SESSION["is_banned"] = true;
    $_SESSION["ban_reason"] = TEST_BANNED_REASON;

    header("Location: " . $returnUrl);
    exit;
}

if ($isTestAccount) {
    include_once __DIR__ . "/../database-queries/user-queries.php";

    $_SESSION["user_id"] = TEST_ACCOUNT_ID;
    $_SESSION["user_name"] = "Test";
    $_SESSION["role"] = "volunteer";
    $_SESSION["is_test_account"] = true;
    $_SESSION["test_has_applications"] = TEST_ACCOUNT_HAS_APPLICATIONS;
    $_SESSION["profile_image"] = getRandomProfileImage();
    unset($_SESSION["test_user_profile"]);

    header("Location: " . $returnUrl);
    exit;
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["first_name"];
$_SESSION["role"] = $user["role"];
$_SESSION["profile_image"] = $user["profile_image"];

if ($user["status"] == "banned") {
    $_SESSION["is_banned"] = true;
    $_SESSION["ban_reason"] = $user["ban_reason"];
}

if ($user["role"] == "admin") {
    header("Location: " . $dashboardPage);
    exit;
}

header("Location: " . $returnUrl);
exit;