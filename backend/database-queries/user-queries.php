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