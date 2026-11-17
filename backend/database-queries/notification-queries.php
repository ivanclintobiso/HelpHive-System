<?php

function getTestNotifications() {
    if (!isset($_SESSION["test_notifications"])) {
        $_SESSION["test_notifications"] = [];
    }

    return $_SESSION["test_notifications"];
}


function createNotification($connection, $userId, $type, $title, $message) {
    if ($connection == null) {
        $notifications = getTestNotifications();

        array_unshift($notifications, [
            "id" => count($notifications) + 1,
            "type" => $type,
            "title" => $title,
            "message" => $message,
            "is_read" => 0,
            "created_at" => date("Y-m-d H:i:s")
        ]);

        $_SESSION["test_notifications"] = $notifications;

        return true;
    }

    $sql = "INSERT INTO notifications (user_id, type, title, message) VALUES (?, ?, ?, ?)";

    $statement = $connection->prepare($sql);
    $statement->bind_param("isss", $userId, $type, $title, $message);

    return $statement->execute();
}


function getMyNotifications($connection, $userId) {
    if ($connection == null) {
        return getTestNotifications();
    }

    $sql = "SELECT id, type, title, message, is_read, created_at
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC, id DESC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    return $notifications;
}


function markAllNotificationsRead($connection, $userId) {
    if ($connection == null) {
        $notifications = getTestNotifications();

        for ($i = 0; $i < count($notifications); $i++) {
            $notifications[$i]["is_read"] = 1;
        }

        $_SESSION["test_notifications"] = $notifications;

        return true;
    }

    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);

    return $statement->execute();
}


function getApplicationUserId($connection, $applicationId) {
    if ($connection == null) {
        return 0;
    }

    $sql = "SELECT user_id FROM applications WHERE id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $applicationId);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    if ($row == null) {
        return 0;
    }

    return $row["user_id"];
}