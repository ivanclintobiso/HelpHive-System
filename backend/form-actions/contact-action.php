<?php
session_start();

include __DIR__ . "/../settings/app-settings.php";

$contactPage = BASE_URL . "/frontend/pages/contact.php";
$successPage = BASE_URL . "/frontend/pages/contact-success.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . $contactPage);
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
$subject = getPostText("subject");
$message = getPostText("message");

$old = [
    "full_name" => $fullName,
    "email" => $email,
    "subject" => $subject,
    "message" => $message
];


$errors = [];

if ($fullName == "") {
    $errors["full_name"] = "Full name is required.";
}

if ($email == "") {
    $errors["email"] = "Email address is required.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Please enter a valid email address.";
}

if ($subject == "") {
    $errors["subject"] = "Subject is required.";
}

if ($message == "") {
    $errors["message"] = "Message is required.";
} else if (strlen($message) < 10) {
    $errors["message"] = "Message must be at least 10 characters.";
}


if (count($errors) > 0) {
    $_SESSION["contact_errors"] = $errors;
    $_SESSION["contact_old"] = $old;

    header("Location: " . $contactPage);
    exit;
}


include __DIR__ . "/../settings/database-connection.php";
include __DIR__ . "/../database-queries/contact-queries.php";

$isSaved = saveContactMessage($connection, $old);

if ($isSaved) {
    $_SESSION["contact_sent"] = true;

    header("Location: " . $successPage);
    exit;
}

$_SESSION["contact_errors"] = ["general" => "Something went wrong while sending your message. Please try again."];
$_SESSION["contact_old"] = $old;

header("Location: " . $contactPage);
exit;