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


function getTestApplicationDetails() {
    $data = getTestAdminData();
    $details = [];

    foreach (getTestApplicationRows() as $applicationRow) {
        foreach ($data["opportunities"] as $opportunity) {
            if ($opportunity["id"] != $applicationRow["opportunity_id"]) {
                continue;
            }

            $organizationStatus = "";
            $organizationPlace = "";

            foreach ($data["organizations"] as $organization) {
                if ($organization["organization_name"] == $opportunity["organization_name"]) {
                    $organizationStatus = $organization["status"];
                    $organizationPlace = $organization["city_province"];
                }
            }

            $opportunity["city_province"] = $organizationPlace;
            $opportunity["application_id"] = $applicationRow["id"];
            $opportunity["application_status"] = $applicationRow["status"];
            $opportunity["opportunity_status"] = $opportunity["status"];
            $opportunity["organization_status"] = $organizationStatus;

            $details[] = $opportunity;
        }
    }

    return $details;
}


function getApplicationDetails($connection, $userId) {
    if ($connection == null) {
        return getTestApplicationDetails();
    }

    $sql = "SELECT a.id AS application_id, a.status AS application_status,
                   p.id, p.image, p.title, p.cause, p.short_description, p.activity_date,
                   p.location_type, p.start_time, p.end_time, p.location, p.good_for,
                   p.duration, p.skills,
                   p.status AS opportunity_status,
                   o.organization_name, o.city_province,
                   o.status AS organization_status
            FROM applications a
            INNER JOIN opportunities p ON p.id = a.opportunity_id
            INNER JOIN organizations o ON o.id = p.organization_id
            WHERE a.user_id = ?
            ORDER BY a.id DESC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $details = [];

    while ($row = $result->fetch_assoc()) {
        $details[] = $row;
    }

    return $details;
}


function getMyApplications($connection, $userId) {
    $applications = [];

    foreach (getApplicationDetails($connection, $userId) as $row) {
        $card = makeOpportunityCard($row);

        $status = $row["application_status"];

        $isUnavailable = false;

        if ($row["opportunity_status"] != "published") {
            $isUnavailable = true;
        }

        if ($row["organization_status"] != "approved") {
            $isUnavailable = true;
        }

        if ($isUnavailable && ($status == "pending" || $status == "approved")) {
            $status = "cancelled";
        }

        $applications[] = [
            "id" => $row["application_id"],
            "opportunity_id" => $card["id"],
            "title" => $card["title"],
            "organization" => $card["organization"],
            "status" => $status,
            "schedule_text" => $card["date_text"],
            "city" => $card["city"],
            "image_url" => $card["image_url"]
        ];
    }

    return $applications;
}



function getMyVolunteerHistory($connection, $userId) {
    if ($connection == null) {
        return [];
    }

    $sql = "SELECT a.volunteer_hours, p.title
            FROM applications a
            LEFT JOIN opportunities p ON p.id = a.opportunity_id
            WHERE a.user_id = ? AND a.hours_verified = 1
            ORDER BY a.id DESC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $userId);
    $statement->execute();

    $result = $statement->get_result();

    $history = [];

    while ($row = $result->fetch_assoc()) {
        $title = "Volunteer activity";

        if ($row["title"] != null && $row["title"] != "") {
            $title = $row["title"];
        }

        $history[] = [
            "title" => $title,
            "hours" => $row["volunteer_hours"]
        ];
    }

    return $history;
}