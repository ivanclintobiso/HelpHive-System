<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";

$loginPage = BASE_URL . "/frontend/pages/home.php?auth=login";
$settingsPage = BASE_URL . "/frontend/pages/volunteer/account-settings.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . $loginPage);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $settingsPage);
    exit;
}


function getPostPassword($name) {
    if (!isset($_POST[$name])) {
        return "";
    }

    if (is_array($_POST[$name])) {
        return "";
    }

    return $_POST[$name];
}


$currentPassword = getPostPassword("current_password");
$newPassword = getPostPassword("new_password");
$confirmPassword = getPostPassword("confirm_password");

$errors = [];

if ($currentPassword == "") {
    $errors[] = "Please enter your current password.";
}

if ($newPassword == "") {
    $errors[] = "Please enter a new password.";
} else if (strlen($newPassword) < 8) {
    $errors[] = "Your new password must be at least 8 characters.";
} else if ($newPassword == $currentPassword) {
    $errors[] = "Your new password must be different from your current password.";
}

if ($confirmPassword != $newPassword) {
    $errors[] = "The new passwords do not match.";
}

if (count($errors) == 0) {

    if ($_SESSION["user_id"] == TEST_ACCOUNT_ID) {

        if ($currentPassword != TEST_ACCOUNT_PASSWORD) {
            $errors[] = "Your current password is incorrect.";
        }

    } else {

        include __DIR__ . "/../settings/database-connection.php";
        include __DIR__ . "/../database-queries/user-queries.php";

        $user = getUserById($connection, $_SESSION["user_id"]);

        if ($user == null) {
            $errors[] = "We could not find your account.";
        } else if (!password_verify($currentPassword, $user["password"])) {
            $errors[] = "Your current password is incorrect.";
        } else {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $isSaved = updateUserPassword($connection, $_SESSION["user_id"], $newPasswordHash);

            if (!$isSaved) {
                $errors[] = "Something went wrong while saving. Please try again.";
            }
        }

    }
}

if (count($errors) > 0) {
    $_SESSION["password_errors"] = $errors;

    header("Location: " . $settingsPage);
    exit;
}

$_SESSION["password_success"] = "Your password was changed successfully.";

header("Location: " . $settingsPage);
exit;