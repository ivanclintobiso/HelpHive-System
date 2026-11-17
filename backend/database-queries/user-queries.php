<?php

function getUserByEmail($connection, $email) {
    $sql = "SELECT id, first_name, last_name, email, password FROM users WHERE email = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("s", $email);
    $statement->execute();

    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    return $user;
}


function saveUser($connection, $user) {
    $sql = "INSERT INTO users (first_name, last_name, email, password, location)
            VALUES (?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "sssss",
        $user["first_name"],
        $user["last_name"],
        $user["email"],
        $user["password"],
        $user["location"]
    );

    $statement->execute();

    return $connection->insert_id;
}


function getUserById($connection, $userId) {
    $sql = "SELECT id, password FROM users WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    return $user;
}


function updateUserPassword($connection, $userId, $newPasswordHash) {
    $sql = "UPDATE users SET password = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("si", $newPasswordHash, $userId);

    return $statement->execute();
}


function getTestProfile() {
    if (!isset($_SESSION["test_user_profile"])) {
        $_SESSION["test_user_profile"] = [
            "first_name" => "Test",
            "last_name" => "User",
            "email" => TEST_ACCOUNT_EMAIL,
            "phone" => "",
            "location" => "",
            "about_me" => "",
            "causes" => "",
            "skills" => "",
            "email_notifications" => 0,
            "created_at" => date("Y-m-d H:i:s")
        ];
    }

    return $_SESSION["test_user_profile"];
}


function getUserProfile($connection, $userId) {
    if ($connection == null) {
        return getTestProfile();
    }

    $sql = "SELECT first_name, last_name, email, phone, location, about_me,
                   causes, skills, email_notifications, created_at
            FROM users
            WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    return $result->fetch_assoc();
}


function updateUserProfile($connection, $userId, $profile) {
    if ($connection == null) {
        $testProfile = getTestProfile();

        $testProfile["first_name"] = $profile["first_name"];
        $testProfile["last_name"] = $profile["last_name"];
        $testProfile["email"] = $profile["email"];
        $testProfile["phone"] = $profile["phone"];
        $testProfile["location"] = $profile["location"];
        $testProfile["about_me"] = $profile["about_me"];
        $testProfile["causes"] = $profile["causes"];
        $testProfile["skills"] = $profile["skills"];

        $_SESSION["test_user_profile"] = $testProfile;

        return true;
    }

    $sql = "UPDATE users
            SET first_name = ?, last_name = ?, email = ?, phone = ?,
                location = ?, about_me = ?, causes = ?, skills = ?
            WHERE id = ?";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "ssssssssi",
        $profile["first_name"],
        $profile["last_name"],
        $profile["email"],
        $profile["phone"],
        $profile["location"],
        $profile["about_me"],
        $profile["causes"],
        $profile["skills"],
        $userId
    );

    return $statement->execute();
}


function updateEmailNotifications($connection, $userId, $isOn) {
    if ($connection == null) {
        $testProfile = getTestProfile();
        $testProfile["email_notifications"] = $isOn;
        $_SESSION["test_user_profile"] = $testProfile;

        return true;
    }

    $sql = "UPDATE users SET email_notifications = ? WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $isOn, $userId);

    return $statement->execute();
}