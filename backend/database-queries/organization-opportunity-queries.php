<?php

function getTestOpportunities() {
    if (!isset($_SESSION["test_organization_opportunities"])) {
        $_SESSION["test_organization_opportunities"] = [];
    }

    return $_SESSION["test_organization_opportunities"];
}


function getOrganizationOpportunities($connection, $organizationId) {
    if ($connection == null) {
        return getTestOpportunities();
    }

    $sql = "SELECT id, organization_id, image, title, cause, short_description, about,
                   what_youll_do, activity_date, location_type, start_time, end_time,
                   location, volunteers_needed, good_for, duration, skills, instructions, status
            FROM opportunities
            WHERE organization_id = ?
            ORDER BY activity_date IS NULL, activity_date ASC, id ASC";

    $statement = $connection->prepare($sql);
    $statement->bind_param("i", $organizationId);
    $statement->execute();

    $result = $statement->get_result();

    $opportunities = [];

    while ($row = $result->fetch_assoc()) {
        $opportunities[] = $row;
    }

    return $opportunities;
}


function getOrganizationOpportunity($connection, $organizationId, $opportunityId) {
    $opportunities = getOrganizationOpportunities($connection, $organizationId);

    foreach ($opportunities as $opportunity) {
        if ($opportunity["id"] == $opportunityId) {
            return $opportunity;
        }
    }

    return null;
}


function saveOrganizationOpportunity($connection, $organizationId, $opportunity) {
    if ($connection == null) {
        $opportunities = getTestOpportunities();

        $newId = 101;

        foreach ($opportunities as $existingOpportunity) {
            if ($existingOpportunity["id"] >= $newId) {
                $newId = $existingOpportunity["id"] + 1;
            }
        }

        $opportunity["id"] = $newId;
        $opportunity["organization_id"] = $organizationId;

        $opportunities[] = $opportunity;
        $_SESSION["test_organization_opportunities"] = $opportunities;

        return $newId;
    }

    $activityDate = $opportunity["activity_date"];
    if ($activityDate == "") {
        $activityDate = null;
    }

    $startTime = $opportunity["start_time"];
    if ($startTime == "") {
        $startTime = null;
    }

    $endTime = $opportunity["end_time"];
    if ($endTime == "") {
        $endTime = null;
    }

    $sql = "INSERT INTO opportunities (
                organization_id, image, title, cause, short_description, about,
                what_youll_do, activity_date, location_type, start_time, end_time,
                location, volunteers_needed, good_for, duration, skills, instructions, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "isssssssssssisssss",
        $organizationId,
        $opportunity["image"],
        $opportunity["title"],
        $opportunity["cause"],
        $opportunity["short_description"],
        $opportunity["about"],
        $opportunity["what_youll_do"],
        $activityDate,
        $opportunity["location_type"],
        $startTime,
        $endTime,
        $opportunity["location"],
        $opportunity["volunteers_needed"],
        $opportunity["good_for"],
        $opportunity["duration"],
        $opportunity["skills"],
        $opportunity["instructions"],
        $opportunity["status"]
    );

    if ($statement->execute()) {
        return $connection->insert_id;
    }

    return 0;
}


function updateOrganizationOpportunity($connection, $organizationId, $opportunityId, $opportunity) {
    if ($connection == null) {
        $opportunities = getTestOpportunities();
        $isUpdated = false;

        foreach ($opportunities as $index => $existingOpportunity) {
            if ($existingOpportunity["id"] == $opportunityId) {
                $opportunity["id"] = $opportunityId;
                $opportunity["organization_id"] = $organizationId;

                $opportunities[$index] = $opportunity;
                $isUpdated = true;
            }
        }

        $_SESSION["test_organization_opportunities"] = $opportunities;

        return $isUpdated;
    }

    $activityDate = $opportunity["activity_date"];
    if ($activityDate == "") {
        $activityDate = null;
    }

    $startTime = $opportunity["start_time"];
    if ($startTime == "") {
        $startTime = null;
    }

    $endTime = $opportunity["end_time"];
    if ($endTime == "") {
        $endTime = null;
    }

    $sql = "UPDATE opportunities SET
                image = ?, title = ?, cause = ?, short_description = ?, about = ?,
                what_youll_do = ?, activity_date = ?, location_type = ?, start_time = ?, end_time = ?,
                location = ?, volunteers_needed = ?, good_for = ?, duration = ?, skills = ?,
                instructions = ?, status = ?
            WHERE id = ? AND organization_id = ?";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "sssssssssssisssssii",
        $opportunity["image"],
        $opportunity["title"],
        $opportunity["cause"],
        $opportunity["short_description"],
        $opportunity["about"],
        $opportunity["what_youll_do"],
        $activityDate,
        $opportunity["location_type"],
        $startTime,
        $endTime,
        $opportunity["location"],
        $opportunity["volunteers_needed"],
        $opportunity["good_for"],
        $opportunity["duration"],
        $opportunity["skills"],
        $opportunity["instructions"],
        $opportunity["status"],
        $opportunityId,
        $organizationId
    );

    return $statement->execute();
}


function getExpectedHours($opportunity) {
    if ($opportunity["start_time"] == "" || $opportunity["end_time"] == "") {
        return 1;
    }

    $start = strtotime($opportunity["start_time"]);
    $end = strtotime($opportunity["end_time"]);

    if ($end <= $start) {
        return 1;
    }

    return round(($end - $start) / 3600, 1);
}


function isDateInPeriod($date, $period) {
    if ($period == "") {
        return true;
    }

    if ($date == "") {
        return false;
    }

    if ($period == "this_month") {
        return substr($date, 0, 7) == date("Y-m");
    }

    if ($period == "this_year") {
        return substr($date, 0, 4) == date("Y");
    }

    if ($period == "last_30") {
        $thirtyDaysAgo = date("Y-m-d", strtotime("-30 days"));
        $today = date("Y-m-d");

        if ($date >= $thirtyDaysAgo && $date <= $today) {
            return true;
        }

        return false;
    }

    return true;
}


function getReportOpportunities($opportunities, $period, $opportunityId) {
    $reportOpportunities = [];

    foreach ($opportunities as $opportunity) {
        if (!isDateInPeriod($opportunity["activity_date"], $period)) {
            continue;
        }

        if ($opportunityId > 0 && $opportunity["id"] != $opportunityId) {
            continue;
        }

        $reportOpportunities[] = $opportunity;
    }

    return $reportOpportunities;
}