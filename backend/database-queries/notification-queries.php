<?php

function getTestNotifications() {
    if (!isset($_SESSION["test_notifications"])) {
        $_SESSION["test_notifications"] = [];

        if (TEST_ACCOUNT_HAS_NOTIFICATIONS) {
            $_SESSION["test_notifications"] = [
                [
                    "id" => 2,
                    "type" => "organization_suspended",
                    "title" => "Organization suspended",
                    "message" => "Your organization Sample Organization was suspended by the admin. Reason: Some of your posts did not follow the community rules.",
                    "is_read" => 0,
                    "created_at" => date("Y-m-d H:i:s", strtotime("-1 hour"))
                ],
                [
                    "id" => 1,
                    "type" => "opportunity_removed",
                    "title" => "Opportunity removed",
                    "message" => "Your opportunity Sample Beach Cleanup was removed by the admin. Reason: The activity details were incomplete.",
                    "is_read" => 0,
                    "created_at" => date("Y-m-d H:i:s", strtotime("-1 day"))
                ]
            ];
        }
    }

    return $_SESSION["test_notifications"];
}


function getNextTestNotificationId($notifications) {
    $highestId = 0;

    foreach ($notifications as $notification) {
        if ($notification["id"] > $highestId) {
            $highestId = $notification["id"];
        }
    }

    return $highestId + 1;
}


function createNotification($connection, $userId, $type, $title, $message) {
    if ($connection == null) {
        $notifications = getTestNotifications();

        array_unshift($notifications, [
            "id" => getNextTestNotificationId($notifications),
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


function markNotificationRead($connection, $userId, $notificationId) {
    if ($connection == null) {
        $notifications = getTestNotifications();

        for ($i = 0; $i < count($notifications); $i++) {
            if ($notifications[$i]["id"] == $notificationId) {
                $notifications[$i]["is_read"] = 1;
            }
        }

        $_SESSION["test_notifications"] = $notifications;

        return true;
    }

    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $notificationId, $userId);

    return $statement->execute();
}


function deleteNotification($connection, $userId, $notificationId) {
    if ($connection == null) {
        $notifications = getTestNotifications();
        $keptNotifications = [];

        foreach ($notifications as $notification) {
            if ($notification["id"] != $notificationId) {
                $keptNotifications[] = $notification;
            }
        }

        $_SESSION["test_notifications"] = $keptNotifications;

        return true;
    }

    $sql = "DELETE FROM notifications WHERE id = ? AND user_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("ii", $notificationId, $userId);

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