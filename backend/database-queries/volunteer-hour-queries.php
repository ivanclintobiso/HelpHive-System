<?php

function getTestVolunteerRecords() {
    if (!isset($_SESSION["test_volunteer_records"])) {
        $_SESSION["test_volunteer_records"] = [];
    }

    return $_SESSION["test_volunteer_records"];
}


function getOrganizationVolunteerRecords($connection, $organizationId) {
    if ($connection == null) {
        return getTestVolunteerRecords();
    }

    $sql = "SELECT a.id, a.opportunity_id, a.attendance, a.volunteer_hours,
                   a.hours_verified, a.correction_requested,
                   u.first_name, u.last_name, u.email
            FROM applications a
            JOIN opportunities o ON o.id = a.opportunity_id
            JOIN users u ON u.id = a.user_id
            WHERE o.organization_id = ? AND a.status != 'declined'
            ORDER BY a.id ASC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $organizationId);
    $statement->execute();

    $result = $statement->get_result();

    $records = [];

    while ($row = $result->fetch_assoc()) {
        $records[] = [
            "id" => $row["id"],
            "opportunity_id" => $row["opportunity_id"],
            "name" => $row["first_name"] . " " . $row["last_name"],
            "email" => $row["email"],
            "attendance" => $row["attendance"],
            "hours" => $row["volunteer_hours"],
            "hours_verified" => $row["hours_verified"],
            "correction_requested" => $row["correction_requested"]
        ];
    }

    return $records;
}


function confirmVolunteerHours($connection, $organizationId, $recordId, $hours) {
    if ($connection == null) {
        $records = getTestVolunteerRecords();

        for ($i = 0; $i < count($records); $i++) {
            if ($records[$i]["id"] == $recordId) {
                $records[$i]["attendance"] = "present";
                $records[$i]["hours"] = $hours;
                $records[$i]["hours_verified"] = 1;
            }
        }

        $_SESSION["test_volunteer_records"] = $records;

        return true;
    }

    $sql = "UPDATE applications a
            JOIN opportunities o ON o.id = a.opportunity_id
            SET a.attendance = 'present', a.volunteer_hours = ?, a.hours_verified = 1
            WHERE a.id = ? AND o.organization_id = ?";

    $statement = $connection->prepare($sql);
    $statement->bind_param("dii", $hours, $recordId, $organizationId);

    return $statement->execute();
}


function getRecordStatus($record) {
    if ($record["hours_verified"] == 1) {
        return "verified";
    }

    if ($record["attendance"] != "not_recorded") {
        return "pending";
    }

    return "registered";
}


function countRegistrations($records, $opportunityId) {
    $count = 0;

    foreach ($records as $record) {
        if ($record["opportunity_id"] == $opportunityId) {
            $count = $count + 1;
        }
    }

    return $count;
}


function getRecordsForOpportunities($records, $opportunities) {
    $selectedRecords = [];

    foreach ($records as $record) {
        foreach ($opportunities as $opportunity) {
            if ($record["opportunity_id"] == $opportunity["id"]) {
                $selectedRecords[] = $record;
            }
        }
    }

    return $selectedRecords;
}


function getVolunteerTotals($records) {
    $totals = [
        "registrations" => count($records),
        "completed" => 0,
        "pending" => 0,
        "corrections" => 0,
        "hours" => 0
    ];

    foreach ($records as $record) {
        if ($record["hours_verified"] == 1) {
            $totals["completed"] = $totals["completed"] + 1;
            $totals["hours"] = $totals["hours"] + $record["hours"];
        }

        if (getRecordStatus($record) == "pending") {
            $totals["pending"] = $totals["pending"] + 1;
        }

        if ($record["correction_requested"] == 1) {
            $totals["corrections"] = $totals["corrections"] + 1;
        }
    }

    return $totals;
}