<?php

include_once __DIR__ . "/opportunity-queries.php";

function getTestApplicationRows() {
    if (TEST_ACCOUNT_HAS_APPLICATIONS == false) {
        return [];
    }

    return [
        ["id" => 1, "opportunity_id" => 2, "status" => "approved"],
        ["id" => 2, "opportunity_id" => 1, "status" => "pending"],
        ["id" => 3, "opportunity_id" => 4, "status" => "approved"]
    ];
}


function getApplicationRows($connection, $userId) {
    $sql = "SELECT id, opportunity_id, status
            FROM applications
            WHERE user_id = ?
            ORDER BY id DESC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}


function getMyApplications($connection, $userId) {
    if ($connection == null) {
        $rows = getTestApplicationRows();
    } else {
        $rows = getApplicationRows($connection, $userId);
    }

    $allOpportunities = getAllOpportunities();

    $applications = [];

    foreach ($rows as $row) {

        foreach ($allOpportunities as $opportunity) {

            if ($opportunity["id"] == $row["opportunity_id"]) {
                $applications[] = [
                    "id" => $row["id"],
                    "opportunity_id" => $opportunity["id"],
                    "title" => $opportunity["title"],
                    "organization" => $opportunity["organization"],
                    "status" => $row["status"],
                    "schedule_text" => $opportunity["date_text"],
                    "city" => $opportunity["city"],
                    "image" => $opportunity["image"]
                ];
            }

        }

    }

    return $applications;
}



function getMyVolunteerHistory($connection, $userId) {
    if ($connection == null) {
        return [];
    }

    $sql = "SELECT opportunity_id, volunteer_hours
            FROM applications
            WHERE user_id = ? AND hours_verified = 1
            ORDER BY id DESC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $allOpportunities = getAllOpportunities();

    $history = [];

    while ($row = $result->fetch_assoc()) {
        $title = "Volunteer activity";

        foreach ($allOpportunities as $opportunity) {
            if ($opportunity["id"] == $row["opportunity_id"]) {
                $title = $opportunity["title"];
            }
        }

        $history[] = [
            "title" => $title,
            "hours" => $row["volunteer_hours"]
        ];
    }

    return $history;
}