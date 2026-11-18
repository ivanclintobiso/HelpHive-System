<?php

function saveContactMessage($connection, $contactMessage) {
    if ($connection == null) {
        return true;
    }

    $sql = "INSERT INTO contact_messages (
                full_name,
                email,
                subject,
                message
            ) VALUES (?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "ssss",
        $contactMessage["full_name"],
        $contactMessage["email"],
        $contactMessage["subject"],
        $contactMessage["message"]
    );

    return $statement->execute();
}