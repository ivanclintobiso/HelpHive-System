<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";

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

if (count($errors) == 0) {

    if (TEST_ACCOUNT_ENABLED && strtolower($email) == TEST_ACCOUNT_EMAIL) {

        if ($password == TEST_ACCOUNT_PASSWORD) {
            $isTestAccount = true;
        } else {
            $errors[] = "Incorrect password. Please try again.";
        }

    } else {

        include __DIR__ . "/../settings/database-connection.php";
        include __DIR__ . "/../database-queries/user-queries.php";

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

if ($isTestAccount) {
    $_SESSION["user_id"] = TEST_ACCOUNT_ID;
    $_SESSION["user_name"] = "Test";
    $_SESSION["is_test_account"] = true;
    $_SESSION["test_has_applications"] = TEST_ACCOUNT_HAS_APPLICATIONS;
} else {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = $user["first_name"];
}

header("Location: " . $returnUrl);
exit;