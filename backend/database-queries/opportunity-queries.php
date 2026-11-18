<?php

include_once __DIR__ . "/admin-queries.php";


function getCardImageUrl($image) {
    if ($image == "") {
        return BASE_URL . "/frontend/assets/images/abstractbackground.png";
    }

    return BASE_URL . "/frontend/assets/uploads/opportunities/" . $image;
}


function makeCleanList($text) {
    $cleanList = [];

    foreach (explode(",", $text) as $part) {
        $part = trim($part);

        if ($part != "") {
            $cleanList[] = $part;
        }
    }

    return $cleanList;
}


function makeOpportunityDateText($row) {
    if ($row["activity_date"] == "") {
        return "Date to be announced";
    }

    $dateText = date("D, M j, Y", strtotime($row["activity_date"]));

    if ($row["start_time"] != "" && $row["end_time"] != "") {
        $startText = date("g:i A", strtotime($row["start_time"]));
        $endText = date("g:i A", strtotime($row["end_time"]));

        $dateText = $dateText . " · " . $startText . "–" . $endText . " PHT (UTC+8)";
    }

    return $dateText;
}


function makeOpportunityCard($row) {
    $causes = makeCleanList($row["cause"]);
    $goodForList = makeCleanList($row["good_for"]);
    $skills = makeCleanList($row["skills"]);

    $placeText = $row["location"] . " " . $row["city_province"] . " Philippines";

    $city = $row["location"];
    if ($city == "") {
        $city = $row["city_province"];
    }

    $goodForText = "everyone";
    if (count($goodForList) > 0) {
        $goodForText = implode(", ", $goodForList);
    }

    return [
        "id" => $row["id"],
        "title" => $row["title"],
        "organization" => $row["organization_name"],
        "causes" => $causes,
        "location_type" => $row["location_type"],
        "city" => $city,
        "place_text" => $placeText,
        "date_text" => makeOpportunityDateText($row),
        "date" => $row["activity_date"],
        "good_for" => $goodForText,
        "good_for_list" => $goodForList,
        "duration" => $row["duration"],
        "description" => $row["short_description"],
        "skills" => $skills,
        "skills_text" => $row["skills"],
        "image_url" => getCardImageUrl($row["image"])
    ];
}


function getTestPublishedRows() {
    $data = getTestAdminData();
    $rows = [];

    foreach ($data["opportunities"] as $opportunity) {
        if ($opportunity["status"] != "published") {
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

        if ($organizationStatus != "approved") {
            continue;
        }

        $opportunity["city_province"] = $organizationPlace;
        $rows[] = $opportunity;
    }

    return $rows;
}


function getPublishedRows($connection) {
    if ($connection == null) {
        return getTestPublishedRows();
    }

    $sql = "SELECT p.id, p.image, p.title, p.cause, p.short_description, p.activity_date,
                   p.location_type, p.start_time, p.end_time, p.location, p.good_for,
                   p.duration, p.skills, o.organization_name, o.city_province
            FROM opportunities p
            INNER JOIN organizations o ON o.id = p.organization_id
            WHERE p.status = 'published' AND o.status = 'approved'
            ORDER BY p.activity_date IS NULL, p.activity_date ASC, p.id DESC";

    $result = $connection->query($sql);

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}


function getAllOpportunities($connection) {
    $opportunities = [];

    foreach (getPublishedRows($connection) as $row) {
        $opportunities[] = makeOpportunityCard($row);
    }

    return $opportunities;
}


function searchOpportunities($connection, $filters) {
    $allOpportunities = getAllOpportunities($connection);
    $results = [];

    foreach ($allOpportunities as $opportunity) {
        $isMatch = true;

        if ($filters["keyword"] != "") {
            $searchableText = $opportunity["title"] . " "
                . $opportunity["organization"] . " "
                . $opportunity["description"] . " "
                . $opportunity["place_text"] . " "
                . $opportunity["skills_text"];

            if (stripos($searchableText, $filters["keyword"]) === false) {
                $isMatch = false;
            }
        }

        if ($filters["location"] != "") {
            $locationParts = explode(",", $filters["location"]);
            $mainLocation = trim($locationParts[0]);

            if (stripos($opportunity["place_text"], $mainLocation) === false) {
                $isMatch = false;
            }
        }

        if (count($filters["location_types"]) > 0) {
            if (!in_array($opportunity["location_type"], $filters["location_types"])) {
                $isMatch = false;
            }
        }

        if (count($filters["causes"]) > 0) {
            if (count(array_intersect($opportunity["causes"], $filters["causes"])) == 0) {
                $isMatch = false;
            }
        }

        if (count($filters["good_for"]) > 0) {
            if (count(array_intersect($opportunity["good_for_list"], $filters["good_for"])) == 0) {
                $isMatch = false;
            }
        }

        if (count($filters["durations"]) > 0) {
            if (!in_array($opportunity["duration"], $filters["durations"])) {
                $isMatch = false;
            }
        }

        if ($filters["from_date"] != "") {
            if ($opportunity["date"] < $filters["from_date"]) {
                $isMatch = false;
            }
        }

        if (count($filters["skills"]) > 0) {
            $hasSelectedSkill = false;

            foreach ($filters["skills"] as $selectedSkill) {
                if (stripos($opportunity["skills_text"], $selectedSkill) !== false) {
                    $hasSelectedSkill = true;
                }
            }

            if (!$hasSelectedSkill) {
                $isMatch = false;
            }
        }

        if ($isMatch) {
            $results[] = $opportunity;
        }
    }

    return $results;
}